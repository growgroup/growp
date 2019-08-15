<?php

namespace Growp\Editor;

use Exception;
use Growp\Template\ACfComponent;
use Growp\Template\BaseComponent;
use Growp\Template\Component;
use Growp\TemplateTag\Utils;
use Routes;
use StoutLogic\AcfBuilder\FieldsBuilder;
use Symfony\Component\Finder\Finder;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use WP_Post;
use WP_Query;

class AcfBlock {

	public $post_type_slug = "growp_acf_block";

	public $post_type_label = "ブロック管理";

	public $block_default_icon = 'block-icon.svg';

	public static $instance = null;

	private function __construct() {

		$this->block_default_icon = '<svg width="94px" height="91px" viewBox="0 0 94 91"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g><rect id="Rectangle" fill="#FFFFFF" x="0" y="0" width="94" height="91" rx="8"></rect><path d="M34,23 C32.2492851,23.016762 31.0124687,24.2619372 31,26 L31,68.999989 L62,68.999989 C63.7272872,69.0042742 64.9873241,67.7530043 65,66 L65,23 L34,23 Z M58,63 L37,63 C36.2230911,62.9985419 36.0041962,62.7850405 36,62 L36,29 C36,28.2181034 36.2201192,28 37,28 L58,28 C58.7414684,28 58.9615875,28.2181034 59,29 L59,41 L50,41 C49.3717545,40.9474695 49.1516353,41.1655729 49,42 L49,52 C49.1558315,52.3570375 49.3747264,52.5705389 50,52 L54,52 C54.521062,52.5705389 54.7399569,52.3570375 54,52 L54,47 L59,47 L59,62 C59.0004175,62.6474341 58.9433802,62.7774784 59,63 C58.7420065,62.9625493 58.6068483,63.0091408 58,63 Z" fill="#5F6817" fill-rule="nonzero"></path></g></g></svg>';

		// ブロック投稿タイプの登録
		add_action( 'init', [ $this, 'register_post_type' ], 10, 1 );
		// 初期化
		add_action( 'acf/init', [ $this, 'acf_init' ] );
		// ブロックカテゴリを追加
		add_filter( 'block_categories', [ $this, "add_block_category" ], 10, 2 );
		// 静的ファイルを登録
		add_action( 'admin_enqueue_scripts', [ $this, "admin_enqueue_scripts" ] );
		// 管理画面のheadタグ内でstyle,scriptを出力
		add_action( 'admin_head', [ $this, "admin_head" ] );

	}


	/**
	 * ACFの初期化時にブロックの設定の登録、ブロックの登録を行う
	 * @throws \StoutLogic\AcfBuilder\FieldNameCollisionException
	 */
	public function acf_init() {
		$this->add_acf_block_settings();
		$this->register_blocks();
		Routes::map( "/wp-admin/preview/component", function () {
			$post_id = isset( $_GET["component_id"] ) ? esc_attr( $_GET["component_id"] ) : false;
			if ( ! $post_id ) {
				wp_die( "不正なアクセス" );
			}
			$block_render_callback = get_field( "block_render_callback", $post_id );
			?>
			<!doctype html>
			<html lang="ja">
			<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
			<meta http-equiv="X-UA-Compatible" content="ie=edge">
			<title>プレビュー</title></head>
			<?php wp_head() ?>
			<body>
			<?php echo $block_render_callback ?>
			<?php wp_footer(); ?>
			</body>
			</html>
			<?php
			exit;
		} );
	}

	/**
	 * 管理画面の head タグ内で実行
	 */
	public function admin_head() {
		$current_screen = get_current_screen();
		if ( $current_screen->base === "post" && $current_screen->id === "growp_acf_block" ) {
			?>
			<script>
				;(function ($) {
					$(function () {
						var $editor = $('#acf-field_block_settings_block_render_callback');
						wp.codeEditor.initialize($editor);
						var $button = $("<button />", {
							class: "button button-secondary",
							text: "プレビュー",
							style: "position: absolute;top: -30px;z-index: 100;right: 0;"
						});
						$button.on("click", function (e) {
							e.preventDefault();
							var $iframe = $("<iframe />", {
								src: acf.data.admin_url + "preview/component?component_id=" + acf.data.post_id,
								style: "width: 100%; height: 100%;"
							});
							new jBox('Modal', {
								title: "プレビュー",
								content: $iframe,
								zIndex: 10000000,
								color: 'black',
								width: '90vw',
								height: '90vh',
								onClose: () => {
								}
							}).open();
						});
						$editor.before($button);
						$editor.closest(".acf-input").css("position", "relative");
					});
				})(jQuery)
			</script>
			<?php
			if ( ( isset( $_GET["edittype"] ) && $_GET["edittype"] === "inblock" ) ) {
				?>
				<style>
					#wpwrap {
						background: #fff !important;
					}

					html.wp-toolbar {
						padding-top: 0 !important;
					}

					#wpcontent {
						margin-left: 0 !important;
					}

					#show-settings-link,
					.page-title-action,
					#wpfooter,
					#screen-meta,
					#adminmenumain,
					#wpadminbar {
						display: none !important;
					}
				</style>
				<?php
			}
		}
	}

	/**
	 * 管理画面で呼び出すスクリプト
	 */
	public function admin_enqueue_scripts() {
		$current_screen = get_current_screen();
		if ( $current_screen->base === "post" && $current_screen->id === "growp_acf_block" ) {
			wp_enqueue_script( "codemirror-twig", get_theme_file_uri( "/assets/js/twig.js" ), [ "wp-codemirror" ] );
			wp_enqueue_code_editor(
				array_merge(
					array(
						'type'       => "html",
						'codemirror' => array(
							'indentUnit' => 2,
							'tabSize'    => 2,
							'mode'       => "php"
						),
					)
				)
			);
		}

		wp_enqueue_script(
			'growp_jbox',
			'https://cdn.jsdelivr.net/npm/jbox@1.0.5/dist/jBox.all.min.js',
			[ 'jquery', 'customize-preview' ],
			"",
			true
		);
		wp_enqueue_style(
			"growp_jbox",
			"https://cdn.jsdelivr.net/npm/jbox@1.0.5/dist/jBox.all.css",
			[],
			"",
			"all"
		);
		wp_localize_script( "growp_theme_customizer", "GROWP_THEMECUSTOMIZER", [
			"nonce" => wp_create_nonce( __FILE__ ),
		] );
	}

	/**
	 * シングルトン
	 * @return null
	 */
	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * ブロックカテゴリを追加
	 *
	 * @param $categories
	 * @param $post
	 *
	 * @return array
	 */
	public function add_block_category( $categories, $post ) {
		return array_merge(
			$categories,
			self::get_block_category()
		);
	}

	public static function get_block_category() {
		return [
			[
				'slug'  => Utils::get_theme_name() . '-blocks-layout',
				'title' => '[G] レイアウト',
			],
			[
				'slug'  => Utils::get_theme_name() . '-blocks-component',
				'title' => '[G] コンポーネント',
			],
			[
				'slug'  => Utils::get_theme_name() . '-blocks-project',
				'title' => '[G] プロジェクトコンポーネント',
			],
		];
	}

	/**
	 * ブロックを登録
	 * @throws \StoutLogic\AcfBuilder\FieldNameCollisionException
	 */
	public function register_blocks() {
		// block を取得
		$blocks_query = new WP_Query( [
			'post_type'              => $this->post_type_slug,
			'no_found_rows'          => true,
			'posts_per_page'         => 200,
			'cache_results'          => true,
			"update_post_meta_cache" => true,
		] );
		$blocks       = $blocks_query->get_posts();
		$self         = $this;
		$_field_data  = [];
		foreach ( $blocks as $block ) {
			$b = get_fields( $block->ID );
			/**
			 * ACFブロックとして登録するための設定を加工
			 */
			$category = $b['block_category'];
			$title    = $b['block_title'];
			if ( ! $title ) {
				continue;
			}
			$block_config = [
				'name'        => $b['block_name'],
				'title'       => $title,
				'description' => $b['block_description'],
				'category'    => ( $category ) ? $category : "growp-blocks-component",
				'icon'        => ( $b['block_icon'] ) ? $b['block_icon'] : $this->block_default_icon,
				'mode'        => $b['block_mode'],
				'align'       => $b['block_align'],
				'post_types'  => $b['block_post_types'],
				'supports'    => $b['block_supports'],
			];
			if ( isset( $b["block_render_template"] ) && $b["block_render_template"] !== "none" && $b["block_render_template"] != "" ) {
//				$block_config["render_template"] = $b["block_render_template"];
				$block_config['render_callback'] = function ( $_block ) use ( $b, $self, $block ) {
					$fields          = get_fields();
					$fields["align"] = "align" . $_block["align"];
					ACfComponent::get( $b["block_render_template"], $fields );
				};
			} else {
				$block_config['render_callback'] = function ( $b ) use ( $self, $block ) {
					$self->compile_render_callback( $b, $block );
				};
			}

			acf_register_block_type( $block_config );

			/**
			 * ブロックに対するカスタムフィールドを登録
			 */
			$block_acf_settings = $b['block_acf_settings'];
			$block_name         = $b['block_name'];
			$block_template     = isset( $b['block_render_callback'] ) ? $b['block_render_callback'] : "";

			$acf_block = new FieldsBuilder( "block_meta_" . $block_name, [
				'title' => 'ブロック設定'
			] );

			if ( is_array( $block_acf_settings ) ) {
				foreach ( $block_acf_settings as $acf_setting ) {
					$acf_block = $this->parse_acf_block_setting_field( $acf_setting, $acf_block );
				}
			}
			$acf_block
				->addField( "block_custom_template_condition", "radio", [
					'label'         => "テンプレート上書き設定",
					'default_value' => "0",
					'layout'        => 'horizontal',
					'choices'       => [
						'1' => '上書きする',
						'0' => 'デフォルト',
					],
				] )->addField( "block_custom_template", "textarea", [
					'label'         => "カスタムテンプレート",
					'default_value' => $block_template
				] )->conditional( "block_custom_template_condition", "==", "1" );
			$acf_block->addGroup( "block_margin", [
				'label' => "間隔設定",
			] )->addRadio( "size", [
				'label'   => "サイズ",
				'layout'  => 'horizontal',
				'default' => 'md',
				'choices' => [
					'xs'  => '最小',
					'sm'  => '小',
					'md'  => '中',
					'lg'  => '大',
					'xlg' => '最大',
				]
			] )->addRadio( "position", [
				'label'   => "方向",
				'layout'  => 'horizontal',
				'default' => 'none',
				'choices' => [
					'top'    => '上',
					'bottom' => '下',
					'all'    => '両方',
					'none'   => 'なし',
				]
			] );
			$acf_block->addMessage( "このブロックを編集",
				'<a href="' . admin_url( "post.php?post=" . $block->ID . "&action=edit" ) . '" target="_blank" class="button-primary js-growp-edit-block" data-block-id="' . $block->ID . '">このブロックを編集</a>' );
			$acf_block->setLocation( "block", "==", "acf/" . $block_name );
			$_field_data[ $block->ID ] = $acf_block->build();
			unset( $acf_block );
		}
		foreach ( $_field_data as $block_data ) {
			acf_add_local_field_group( $block_data );
		}
	}

	/**
	 * ACFの設定をサブフィールド含め再帰的に解決する
	 *
	 * @param $acf_setting
	 * @param $acf_block
	 *
	 * @return mixed
	 */
	public function parse_acf_block_setting_field( $acf_setting, $acf_block ) {
		switch ( $acf_setting["block_type"] ) {
			case "group" :
				$group = $acf_block->addGroup( $acf_setting["block_key"], [
					'label'  => $acf_setting["block_label"],
					'layout' => 'block'
				] );
				if ( $acf_setting["block_sub_fields"] ) {
					foreach ( $acf_setting["block_sub_fields"] as $sub_acf_block ) {
						$group = $this->parse_acf_block_setting_field( $sub_acf_block, $group );
					}
				}
				break;
			case "repeater" :
				$repeater = $acf_block->addRepeater( $acf_setting["block_key"], [
					'label'  => $acf_setting["block_label"],
					'layout' => 'block'
				] );
				if ( $acf_setting["block_sub_fields"] ) {
					foreach ( $acf_setting["block_sub_fields"] as $sub_acf_block ) {
						$repeater = $this->parse_acf_block_setting_field( $sub_acf_block, $repeater );

					}
				}
				break;
			case "select":
			case "checkbox":
			case "radio" :
				$choices_text = preg_split( "/\r\n|\n|\r/", $acf_setting["block_choices"] );
				$_choices     = [];
				array_map( function ( $a ) use ( &$_choices ) {
					$b                 = trim( $a );
					$b                 = explode( " : ", $b );
					$b                 = array_map( function ( $c ) {
						return trim( $c );
					}, $b );
					$_choices[ $b[0] ] = $b[1];

					return $b;
				}, $choices_text );

				$acf_block->addChoiceField( $acf_setting["block_key"], $acf_setting["block_type"], [
					'label'         => $acf_setting["block_label"],
					'choices'       => $_choices,
					'default_value' => $acf_setting["block_default"]
				] );
				break;
			case "textarea":
				$acf_block->addField( $acf_setting["block_key"], $acf_setting["block_type"], [
					'label'         => $acf_setting["block_label"],
					'new_lines'     => "br",
					'default_value' => $acf_setting["block_default"]
				] );
				break;
			default :
				// 通常のフィールドの場合
				$acf_block->addField( $acf_setting["block_key"], $acf_setting["block_type"], [
					'label'         => $acf_setting["block_label"],
					'default_value' => $acf_setting["block_default"]
				] );
				break;
		}

		return $acf_block;
	}

	/**
	 * ブロックテンプレートをコンパイル
	 *
	 * @param $b acf_block instance
	 * @param $block WP_Post
	 */
	public function compile_render_callback( $b, $block ) {
		$fields = get_fields();

		$template                        = get_field( "block_render_callback", $block->ID );
		$block_custom_template           = get_field( "block_custom_template" );
		$block_custom_template_condition = get_field( "block_custom_template_condition" );
		array_merge( [ 'id' => $b["id"], 'className' => isset( $b["className"] ) ? $b["className"] : "", "align" => ( isset( $b["align"] ) ? $b["align"] : "" ) ] );
		if ( ! $fields ) {
			$fields = [];
		}
		if ( $template ) {
			try {
				// 上書きの場合
				if ( $block_custom_template_condition === "1" ) {
					try {
						$twig = new Environment( new ArrayLoader( [
							'index.html' => $this->insert_classname_and_align( $b, $block_custom_template )
						] ) );
						$twig->addExtension( new BlockTwigExtension() );
						echo $twig->render( "index.html", $fields );
					} catch ( Exception $e ) {
						echo $e->getMessage();

					}
				} else {
					try {
						// そのままの場合
						$twig = new Environment( new ArrayLoader( [
							'index.html' => $this->insert_classname_and_align( $b, $template )
						] ) );
						$twig->addExtension( new BlockTwigExtension() );
						echo $twig->render( "index.html", $fields );
					} catch ( Exception $e ) {
						echo $e->getMessage();
					}

				}
			} catch ( Exception $e ) {
				echo $e->getMessage();
			}
		}
	}


	/**
	 * 追加クラス名と位置情報をデフォルトで挿入する
	 *
	 * @param $b
	 * @param $template
	 *
	 * @return mixed
	 */
	public function insert_classname_and_align( $b, $template ) {
		preg_match( '/(?:nav|form|div|a|p|section|main|header|footer|aside|span|b|i|).*?class=(?:\'|")(.*?)(?:\'|")/', $template, $matches );
		$class_name   = ( isset( $b["className"] ) ? " " . $b["className"] : "" );
		$align        = ( isset( $b["align"] ) ? " align" . $b["align"] : "" );
		$block_margin = get_field( "block_margin" );
		$margin_size  = "";
		if ( $block_margin["position"] !== "none" ) {
			$margin_size = ( $block_margin["size"] ? "<div class='u-mbs is-" . $block_margin["size"] . " is-" . $block_margin["position"] . "'>" : "" );
		}
		if ( ! $matches ) {
			$template = $margin_size . $template . "</div>";

			return $template;
		}
		$template = preg_replace( "/" . mb_substr( $matches[0], 0, - 1 ) . "/", mb_substr( $matches[0], 0, - 1 ) . $class_name . $align, $template, 1 );
		if ( $margin_size ) {
			$template = $margin_size . $template . "</div>";
		}

		return $template;
	}

	/**
	 * ブロック管理でのカスタムフィールドを定義
	 *
	 * @throws \StoutLogic\AcfBuilder\FieldNameCollisionException
	 */
	public function add_acf_block_settings() {
		$_acf_fields = [];
		foreach ( acf_get_field_types() as $type_name => $type ) {
			$_acf_fields[ $type_name ] = $type->label;
		};
		unset( $_acf_fields["flexible_content"] );
		unset( $_acf_fields["google_map"] );
		unset( $_acf_fields["gallery"] );
		unset( $_acf_fields["clone"] );
		unset( $_acf_fields["oembed"] );
		unset( $_acf_fields["accordion"] );
		unset( $_acf_fields["tab"] );

		$acf_block = new FieldsBuilder( "block_settings", [ 'title' => 'ブロック設定' ] );

		$finder = new Finder();
		$finder->in( get_template_directory() . "/views/" );
		$finder->exclude( "templates" );
		$finder->exclude( "foundation" );
		$finder->files();
		$block_templates = [ "none" => "なし" ];
		foreach ( $finder as $file_id => $file ) {
			$block_templates[ "views/" . $file->getRelativePathname() ] = "views/" . $file->getRelativePathname();
		}
		$acf_block->addText( "block_name", [ 'label' => '名称' ] )
		          ->addText( "block_title", [ 'label' => 'ラベル' ] )
		          ->addTextarea( "block_description", [ 'label' => '説明文' ] )
		          ->addSelect( "block_render_template", [ 'label' => 'テンプレートを選択', 'choices' => $block_templates ] )
		          ->addTextarea( "block_render_callback", [ 'label' => 'テンプレートを記述' ] )->conditional( "block_render_template", "==", "" )->orCondition( "block_render_template", "==", "none" )
		          ->addRepeater( "block_acf_settings", [
			          'label'  => 'ACFフィールド',
			          'layout' => 'block'
		          ] )
		          ->addText( "block_key", [ 'label' => 'キー', ] )->setWidth( 15 )
		          ->addText( "block_label", [ 'label' => 'ラベル', ] )->setWidth( 15 )
		          ->addText( "block_default", [ 'label' => 'デフォルト値', ] )->setWidth( 15 )
		          ->addSelect( "block_type", [
			          'label'   => 'フィールドタイプ',
			          'choices' => $_acf_fields,
		          ] )
		          ->setWidth( 30 )
		          ->addTextarea( "block_choices", [ 'label' => "選択肢" ] )->setWidth( 30 )
		          ->conditional( "block_type", "==", "checkbox" )
		          ->orCondition( "block_type", "==", "radio" )
		          ->orCondition( "block_type", "==", "select" )
		          ->addRepeater( "block_sub_fields", [ 'label' => 'サブフィールド', 'layout' => 'table' ] )
		          ->conditional( "block_type", "==", "repeater" )
		          ->orCondition( "block_type", "==", "group" )
		          ->addText( "block_key", [ 'label' => 'キー', ] )->setWidth( 15 )
		          ->addText( "block_label", [ 'label' => 'ラベル', ] )->setWidth( 15 )
		          ->addText( "block_default", [ 'label' => 'デフォルト値', ] )->setWidth( 15 )
		          ->addSelect( "block_type", [
			          'label'   => 'フィールドタイプ',
			          'choices' => $_acf_fields,
		          ] )->addTextarea( "block_choices", [ 'label' => "選択肢" ] )->setWidth( 30 )
		          ->conditional( "block_type", "==", "checkbox" )
		          ->orCondition( "block_type", "==", "radio" )
		          ->orCondition( "block_type", "==", "select" )
		          ->endRepeater();

		$_category_choice = self::get_block_category();
		$category_choice  = [];
		foreach ( $_category_choice as $_category_choice_key => $_cc ) {
			$category_choice[ $_cc["slug"] ] = $_cc["title"];
		}
		$acf_block->addRadio( "block_category", [
			'label'   => 'カテゴリ',
			'choices' => $category_choice,
		] )->setWidth( 33 );
		$acf_block->addText( "block_icon", [
			'label'         => 'アイコン',
			'instructions'  => '<a href="https://developer.wordpress.org/resource/dashicons/#editor-break" target="_blank">ダッシュアイコンから入力</a>',
			'default_value' => 'admin-site'
		] )->setWidth( 33 );
		$acf_block->addRadio( "block_mode", [
			'label'         => 'デフォルトの表示モード',
			'default_value' => 'preview',
			'instructions'  => '',
			'choices'       => [
				[ 'preview' => 'プレビューモード' ],
				[ 'edit' => '編集モード' ],
			],
		] )->setWidth( 33 );
		$acf_block->addCheckbox( "block_align", [
			'label'         => 'デフォルトの位置',
			'default_value' => '',
			'instructions'  => '',
			'choices'       => [
				[ 'left' => '左' ],
				[ 'right' => '右' ],
				[ 'wide' => 'ワイド' ],
				[ 'full' => 'フル' ],
			],
		] )->setWidth( 33 );
		$acf_block->addCheckbox( "block_post_types", [
			'label'         => '有効にする投稿タイプ',
			'default_value' => [ 'post', 'page' ],
			'instructions'  => '',
			'choices'       => get_post_types( [ 'public' => true ] )
		] )->setWidth( 33 );
		$acf_block->addGroup( 'block_supports', [
			'label' => '有効にするサポート',
		] )->setWidth( 33 )->addCheckbox( "align", [
			'label'         => 'サポートする位置',
			'default_value' => [ 'left', 'right', 'wide', 'full' ],
			'instructions'  => '',
			'choices'       => [
				[ 'left' => '左' ],
				[ 'right' => '右' ],
				[ 'wide' => 'ワイド' ],
				[ 'full' => 'フル' ],
			]
		] )->addRadio( "mode", [
			'label'         => '表示モードの切り替え',
			'default_value' => true,
			'instructions'  => '',
			'choices'       => [
				[ "1" => '許可する' ],
				[ "0" => '許可しない' ],
			]
		] )->addRadio( "multiple", [
			'label'         => '複数ブロック追加の許可',
			'default_value' => 1,
			'instructions'  => '',
			'choices'       => [
				[ "1" => '許可する' ],
				[ "0" => '許可しない' ],
			]
		] );

		$acf_block->setLocation( 'post_type', '==', 'growp_acf_block' );
		acf_add_local_field_group( $acf_block->build() );
	}

	/**
	 * ブロック管理用の投稿タイプを追加
	 */
	public function register_post_type() {
		if ( class_exists( 'Classic_Editor' ) && get_option( "classic-editor-allow-users" ) !== "allow" ) {
			return false;
		}
		$labels = array(
			'name'                  => $this->post_type_label,
			'singular_name'         => $this->post_type_label,
			'menu_name'             => $this->post_type_label,
			'name_admin_bar'        => $this->post_type_label,
			'archives'              => "ブロック一覧",
			'attributes'            => "ブロック一覧",
			'parent_item_colon'     => __( '親のブロック', 'growp' ),
			'all_items'             => __( 'すべてのブロック', 'growp' ),
			'add_new_item'          => __( '新しいブロックを追加', 'growp' ),
			'add_new'               => __( '新しく追加', 'growp' ),
			'new_item'              => __( '新しいブロックを追加', 'growp' ),
			'edit_item'             => __( 'ブロックを編集', 'growp' ),
			'update_item'           => __( 'ブロックを更新', 'growp' ),
			'view_item'             => __( 'ブロックを見る', 'growp' ),
			'view_items'            => __( 'ブロックを見る', 'growp' ),
			'search_items'          => __( 'ブロックを検索', 'growp' ),
			'not_found'             => __( 'ブロックが見つかりませんでした', 'growp' ),
			'not_found_in_trash'    => __( 'ゴミ箱にはブロックが見つかりませんでした', 'growp' ),
			'featured_image'        => __( 'アイキャッチ画像', 'growp' ),
			'set_featured_image'    => __( 'アイキャッチ画像をセットする', 'growp' ),
			'remove_featured_image' => __( 'アイキャッチ画像を削除', 'growp' ),
			'use_featured_image'    => __( 'アイキャッチ画像として使う', 'growp' ),
			'insert_into_item'      => __( 'ブロックに挿入', 'growp' ),
			'uploaded_to_this_item' => __( 'すでにアップロードされています', 'growp' ),
			'items_list'            => __( 'ブロック一覧', 'growp' ),
			'items_list_navigation' => __( 'ブロック一覧ナビ', 'growp' ),
			'filter_items_list'     => __( '', 'growp' ),
		);
		$args   = array(
			'label'               => $this->post_type_label,
			'description'         => __( 'ブロックを登録', 'growp' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'post',
			'show_in_rest'        => false,
		);
		register_post_type( 'growp_acf_block', $args );
	}
}

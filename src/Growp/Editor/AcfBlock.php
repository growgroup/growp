<?php

namespace Growp\Editor;

use function add_action;
use function array_map;
use DOMDocument;
use Exception;
use function explode;
use function get_current_screen;
use function preg_match;
use StoutLogic\AcfBuilder\FieldsBuilder;
use function str_replace;
use Symfony\Component\DomCrawler\Crawler;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class AcfBlock {

	public $post_type_slug = "growp_acf_block";
	public $post_type_label = "ブロック管理";
	public $block_default_icon = '<svg width="94px" height="91px" viewBox="0 0 94 91"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g><rect id="Rectangle" fill="#FFFFFF" x="0" y="0" width="94" height="91" rx="8"></rect><path d="M34,23 C32.2492851,23.016762 31.0124687,24.2619372 31,26 L31,68.999989 L62,68.999989 C63.7272872,69.0042742 64.9873241,67.7530043 65,66 L65,23 L34,23 Z M58,63 L37,63 C36.2230911,62.9985419 36.0041962,62.7850405 36,62 L36,29 C36,28.2181034 36.2201192,28 37,28 L58,28 C58.7414684,28 58.9615875,28.2181034 59,29 L59,41 L50,41 C49.3717545,40.9474695 49.1516353,41.1655729 49,42 L49,52 C49.1558315,52.3570375 49.3747264,52.5705389 50,52 L54,52 C54.521062,52.5705389 54.7399569,52.3570375 54,52 L54,47 L59,47 L59,62 C59.0004175,62.6474341 58.9433802,62.7774784 59,63 C58.7420065,62.9625493 58.6068483,63.0091408 58,63 Z" fill="#5F6817" fill-rule="nonzero"></path></g></g></svg>';

	public static $instance = null;

	private function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ], 10, 1 );
		$self = $this;
		add_action( 'acf/init', function () use ( $self ) {
			$self->add_acf_block_settings();
			$self->register_blocks();
		} );
		add_filter( 'block_categories', [ $this, "add_block_category" ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ $this, "admin_enqueue_scripts" ] );
		add_action( 'admin_head', [ $this, "admin_head" ] );

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
						wp.codeEditor.initialize($('#acf-field_block_settings_block_render_callback'));
					})
				})(jQuery)
			</script>
			<?php
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
			[
				[
					'slug'  => 'growp-blocks-layout',
					'title' => '[G] レイアウト',
				],
				[
					'slug'  => 'growp-blocks-component',
					'title' => '[G] コンポーネント',
				],
				[
					'slug'  => 'growp-blocks-project',
					'title' => '[G] プロジェクトコンポーネント',
				],
			],
		);
	}


	/**
	 * ブロックを登録
	 * @throws \StoutLogic\AcfBuilder\FieldNameCollisionException
	 */
	public function register_blocks() {

		// block を取得
		$blocks = get_posts( [
			'post_type'      => $this->post_type_slug,
			'no_found_rows'  => true,
			'posts_per_page' => - 1
		] );
		$self   = $this;
		foreach ( $blocks as $block ) {
			/**
			 * ACFブロックとして登録するための設定を加工
			 */
			$category = get_field( 'block_category', $block->ID );
			$title    = get_field( 'block_title', $block->ID );
			if ( ! $title ) {
				continue;
			}
			$block_config = [
				'name'            => get_field( 'block_name', $block->ID ),
				'title'           => $title,
				'description'     => get_field( 'block_description', $block->ID ),
				'category'        => ( $category ) ? $category : "growp-blocks-component",
				'icon'            => ( get_field( "block_icon", $block->ID ) ) ? get_field( "block_icon", $block->ID ) : $this->block_default_icon,
				'mode'            => get_field( 'block_mode', $block->ID ),
				'align'           => get_field( 'block_align', $block->ID ),
				'post_types'      => get_field( 'block_post_types', $block->ID ),
				'supports'        => get_field( 'block_supports', $block->ID ),
				'render_callback' => function ( $b ) use ( $self, $block ) {
					$self->compile_render_callback( $b, $block );
				},
			];

			acf_register_block_type( $block_config );

			/**
			 * ブロックに対するカスタムフィールドを登録
			 */
			$block_acf_settings = get_field( 'block_acf_settings', $block->ID );
			$block_name         = get_field( 'block_name', $block->ID );
			$block_template     = get_field( 'block_render_callback', $block->ID );

			$acf_block = new FieldsBuilder( "block_meta_" . $block_name, [
				'title' => 'ブロック設定'
			] );
			if ( is_array( $block_acf_settings ) ) {
				foreach ( $block_acf_settings as $acf_setting ) {
					switch ( $acf_setting["block_type"] ) {
						case "repeater" :
							$repeater = $acf_block->addRepeater( $acf_setting["block_key"], [
								'label'  => $acf_setting["block_label"],
								'layout' => 'block'
							] );

							if ( $acf_setting["block_sub_fields"] ) {
								foreach ( $acf_setting["block_sub_fields"] as $sub_acf_block ) {
									$repeater->addField( $sub_acf_block["block_name"], $sub_acf_block["block_subtype"], [
										'label' => $sub_acf_block["block_label"]
									] );
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
								'label'   => $acf_setting["block_label"],
								'choices' => $_choices,
							] );
							break;
						default :
							// 通常のフィールドの場合
							$acf_block->addField( $acf_setting["block_key"], $acf_setting["block_type"], [
								'label' => $acf_setting["block_label"]
							] );
							break;
					}

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

			$acf_block->setLocation( "block", "==", "acf/" . $block_name );
			acf_add_local_field_group( $acf_block->build() );
		}
	}

	/**
	 * ブロックテンプレートをコンパイル
	 *
	 * @param $b acf_block instance
	 * @param $block growp_block
	 */
	public function compile_render_callback( $b, $block ) {
		$fields                          = get_fields();
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
					$twig = new Environment( new ArrayLoader( [
						'index.html' => $this->insert_classname_and_align( $b, $block_custom_template )
					] ) );
					$twig->addExtension( new BlockTwigExtension() );
					echo $twig->render( "index.html", $fields );
				} else {
					// そのままの場合
					$twig = new Environment( new ArrayLoader( [
						'index.html' => $this->insert_classname_and_align( $b, $template )
					] ) );
					$twig->addExtension( new BlockTwigExtension() );
					echo $twig->render( "index.html", $fields );
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
		$acf_block->addText( "block_name", [ 'label' => '名称' ] )
		          ->addText( "block_title", [ 'label' => 'ラベル' ] )
		          ->addTextarea( "block_description", [ 'label' => '説明文' ] )
		          ->addTextarea( "block_render_callback", [ 'label' => 'テンプレート' ] )
		          ->addRepeater( "block_acf_settings", [
			          'label'  => 'ACFフィールド',
			          'layout' => 'block'
		          ] )
		          ->addText( "block_key", [ 'label' => 'キー', ] )->setWidth( 15 )
		          ->addText( "block_label", [ 'label' => 'ラベル', ] )->setWidth( 15 )
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
		          ->addText( "block_name", [ 'label' => 'キー', ] )->setWidth( 15 )
		          ->addText( "block_label", [ 'label' => 'ラベル', ] )->setWidth( 15 )
		          ->addSelect( "block_subtype", [
			          'label'   => 'フィールドタイプ',
			          'choices' => $_acf_fields,
		          ] )
		          ->endRepeater();

		$acf_block->addText( "block_category", [ 'label' => 'カテゴリ' ] );
		$acf_block->addText( "block_icon", [
			'label'         => 'アイコン',
			'instructions'  => '<a href="https://developer.wordpress.org/resource/dashicons/#editor-break" target="_blank">ダッシュアイコンから入力</a>',
			'default_value' => 'admin-site'
		] );
		$acf_block->addRadio( "block_mode", [
			'label'         => 'デフォルトの表示モード',
			'default_value' => 'preview',
			'instructions'  => '',
			'choices'       => [
				[ 'preview' => 'プレビューモード' ],
				[ 'edit' => '編集モード' ],
			],
		] );
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
		] );
		$acf_block->addCheckbox( "block_post_types", [
			'label'         => '有効にする投稿タイプ',
			'default_value' => [ 'post', 'page' ],
			'instructions'  => '',
			'choices'       => get_post_types( [ 'public' => true ] )
		] );
		$acf_block->addGroup( 'block_supports', [
			'label' => '有効にするサポート',
		] )->addCheckbox( "align", [
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
			'description'         => __( 'ACFブロックを登録', 'growp' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions' ),
			'hierarchical'        => true,
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
			'capability_type'     => 'page',
			'show_in_rest'        => false,
		);
		register_post_type( 'growp_acf_block', $args );
	}
}

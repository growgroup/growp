<?php

namespace Growp\Editor;

use StoutLogic\AcfBuilder\FieldsBuilder;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class AcfBlock {

	public $post_type_slug = "growp_acf_block";
	public $post_type_label = "ブロック管理";

	public static $instance = null;

	private function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ], 10, 1 );
		$self = $this;
		add_action( 'acf/init', function () use ( $self ) {
			$self->add_acf_block_settings();
			$self->register_blocks();
		} );
		add_filter( 'block_categories', [ $this, "add_block_category" ], 10, 2 );
	}

	function add_block_category( $categories, $post ) {
		return array_merge(
			$categories,
			[
				[
					'slug'  => 'growp-blocks',
					'title' => 'オリジナルブロック',
				],
			]
		);
	}

	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * ブロックを登録
	 * @throws \StoutLogic\AcfBuilder\FieldNameCollisionException
	 */
	public function register_blocks() {
		$blocks = get_posts( [
			'post_type'      => $this->post_type_slug,
			'no_found_rows'  => true,
			'posts_per_page' => - 1
		] );
		$self   = $this;
		foreach ( $blocks as $block ) {
			$block_meta = get_fields( $block->ID );
			$category   = get_field( "block_category", $block->ID );
			$title      = get_field( "block_title", $block->ID );
			if ( ! $title ) {
				continue;
			}

			$block_config = [
				'name'            => get_field( "block_name", $block->ID ),
				'title'           => get_field( "block_title", $block->ID ),
				'description'     => get_field( "block_description", $block->ID ),
				'category'        => ( $category ) ? $category : "growp-blocks",
				'icon'            => ( $block_meta["block_icon"] ) ? $block_meta["block_icon"] : '<svg width="94px" height="91px" viewBox="0 0 94 91" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="logo"><rect id="Rectangle" fill="#FFFFFF" x="0" y="0" width="94" height="91" rx="8"></rect><path d="M34,23 C32.2492851,23.016762 31.0124687,24.2619372 31,26 L31,68.999989 L62,68.999989 C63.7272872,69.0042742 64.9873241,67.7530043 65,66 L65,23 L34,23 Z M58,63 L37,63 C36.2230911,62.9985419 36.0041962,62.7850405 36,62 L36,29 C36,28.2181034 36.2201192,28 37,28 L58,28 C58.7414684,28 58.9615875,28.2181034 59,29 L59,41 L50,41 C49.3717545,40.9474695 49.1516353,41.1655729 49,42 L49,52 C49.1558315,52.3570375 49.3747264,52.5705389 50,52 L54,52 C54.521062,52.5705389 54.7399569,52.3570375 54,52 L54,47 L59,47 L59,62 C59.0004175,62.6474341 58.9433802,62.7774784 59,63 C58.7420065,62.9625493 58.6068483,63.0091408 58,63 Z" fill="#5F6817" fill-rule="nonzero"></path></g></g></svg>',
				'mode'            => get_field( "block_mode", $block->ID ),
				'align'           => get_field( "block_align", $block->ID ),
				'post_types'      => get_field( "block_post_types", $block->ID ),
				'supports'        => get_field( "block_supports", $block->ID ),
				'render_callback' => function ( $b ) use ( $self, $block ) {
					$template                        = get_field( "block_render_callback", $block->ID );
					$fields                          = get_fields();
					$block_custom_template           = get_field( "block_custom_template" );
					$block_custom_template_condition = get_field( "block_custom_template_condition" );
					array_merge( [ 'id' => $b["id"], 'className' => isset( $b["className"] ) ? $b["className"] : "", "align" => ( isset( $b["align"] ) ? $b["align"] : "" ) ] );
					if ( $template ) {
						// 上書きの場合
						if ( $block_custom_template_condition === "1" ) {
							if ( ! $fields ) {
								echo $block_custom_template;

							} else {
								$twig = new Environment( new ArrayLoader( [
									'index.html' => $block_custom_template
								] ) );
								echo $twig->render( "index.html", $fields );
							}
						} else {
							if ( ! $fields ) {
								echo $template;
							} else {
								$twig = new Environment( new ArrayLoader( [
									'index.html' => $template
								] ) );
								echo $twig->render( "index.html", $fields );
							}
						}
					}
				},
			];

			acf_register_block_type( $block_config );
			$block_acf_settings = get_field( "block_acf_settings", $block->ID );
			$block_name         = get_field( "block_name", $block->ID );
			$block_template     = get_field( "block_render_callback", $block->ID );

			$acf_block = new FieldsBuilder( "block_meta_" . $block_name, [ 'title' => 'ブロック設定' ] );
			if ( is_array( $block_acf_settings ) ) {
				foreach ( $block_acf_settings as $acf_setting ) {
					if ( $acf_setting["block_type"] === "repeater" ) {
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
//						$acf_block->endRepeater();
					} else {
						$acf_block->addField( $acf_setting["block_key"], $acf_setting["block_type"], [
							'label' => $acf_setting["block_label"]
						] );
					}
				}
			}
			$acf_block->addField( "block_custom_template_condition", "radio", [
				'label'         => "テンプレート上書き設定",
				'default_value' => "0",
				'choices'       => [
					'1' => '上書きする',
					'0' => 'デフォルト',
				],
			] );
			$acf_block->addField( "block_custom_template", "wysiwyg", [
				'label'         => "カスタムテンプレート",
				'default_value' => $block_template
			] )->conditional( "block_custom_template_condition", "==", "1" );

			$acf_block->setLocation( "block", "==", "acf/" . $block_name );
			acf_add_local_field_group( $acf_block->build() );

		}
	}

	/**
	 * ACFブロックの設定の追加
	 * @throws \StoutLogic\AcfBuilder\FieldNameCollisionException
	 */
	public
	function add_acf_block_settings() {
		$_acf_fields = [];
		foreach ( acf_get_field_types() as $type_name => $type ) {
			$_acf_fields[ $type_name ] = $type->label;
		};
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
		          ->setWidth( 20 )
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

	public
	function register_post_type() {
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
			'set_featured_image'    => __( 'Set featured image', 'growp' ),
			'remove_featured_image' => __( 'Remove featured image', 'growp' ),
			'use_featured_image'    => __( 'Use as featured image', 'growp' ),
			'insert_into_item'      => __( 'Insert into item', 'growp' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'growp' ),
			'items_list'            => __( 'Items list', 'growp' ),
			'items_list_navigation' => __( 'Items list navigation', 'growp' ),
			'filter_items_list'     => __( 'Filter items list', 'growp' ),
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

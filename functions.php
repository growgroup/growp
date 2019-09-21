<?php
/**
 * テーマのための関数
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */

use Growp\Config\Config;
use Growp\Customizer\Customizer;
use Growp\Devtools\Devtools;
use Growp\Editor\AcfBlock;
use Growp\Editor\Acf;
use Growp\Editor\BlockEditor;
use Growp\Hooks\Backend;
use Growp\Hooks\Comments;
use Growp\Hooks\Frontend;
use Growp\Hooks\Plugins;
use Growp\Template\Foundation;

/**
 * バージョン情報の出力
 * キャッシュ対策
 */
define( 'GROWP_VERSION', '1.0.0' );

// テンプレートのパス
define( 'GROWP_TEMPLATE_PATH', __DIR__ );

// カスタマイザー
define( 'GROWP_USE_STYLE_CUSTOMIZE', true );

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/src/Growp/TemplateTag/Proxy.php";
require_once __DIR__ . "/vendor/aristath/kirki/kirki.php";


add_action( "after_setup_theme", function () {
	Config::get_instance();
	Comments::get_instance();
	Plugins::get_instance();
	Frontend::get_instance();
	Backend::get_instance();
	Foundation::get_instance();
	Acf::get_instance();
	AcfBlock::get_instance();
	Customizer::get_instance();
	BlockEditor::get_instance();

	if ( Config::get( "use_devtools" ) ) {
		Devtools::get_instance();
	}
} );


/**
 * テンプレートタグ定義
 */

// テンプレートを固定ページとして作成
// growp-setup を利用した際に有効
function growp_create_pages() {
	if ( ! get_option( "growp_create_pages" ) ) {
		$files = glob( __DIR__ . "/page-*.php" );
		foreach ( $files as $file ) {
			$fileheaders = get_file_data( $file, [ "Page Slug", "Template Name", "Page Template Name" ] );
			$post_id     = wp_insert_post( [
				'post_type'    => "page",
				'post_title'   => $fileheaders[1],
				'post_name'    => $fileheaders[0],
				'post_content' => "",
				'post_status'  => "publish",
			] );
			update_post_meta( $post_id, "_wp_page_template", $fileheaders[2] );
		}
		update_option( "growp_create_pages", true );
	}
}

// add_action("init", "growp_create_pages");

function growp_get_post_types( $in_use_theme_customizer = false ) {
	$post_types = [
		'blog'  => [
			"label"    => "企業ブログ",
			"taxonomy" => [
				'name'  => 'blog_category',
				'label' => 'ブログカテゴリ',
			],
		],
		'media' => [
			"label"    => "メディア",
			"taxonomy" => [
				'name'  => 'media_category',
				'label' => 'メディアカテゴリ',
			],
		],
		'cases' => [
			"label"    => "導入事例",
			"taxonomy" => [
				'name'  => 'cases_category',
				'label' => '導入事例カテゴリ',
			],
		],
	];
	if ( $in_use_theme_customizer ) {
		$_post_types = [];
		array_map( function ( $a, $key ) use ( &$_post_types ) {
			$_post_types[ $key ] = $a["label"];
		}, $post_types, array_keys( $post_types ) );

		return $_post_types;
	}

	return $post_types;
}

add_action( "init", function () {
//	$post_types          = growp_get_post_types();
//	$enable_post_types   = GTag::get_option( "growp_posttypes_swtich" );
//	$post_types_settings = GTag::get_option( "growp_posttypes_asset" );
//	if ( ! $enable_post_types ) {
//		$enable_post_types = [];
//	}
//
//	if ( ! $post_types_settings ) {
//		$post_types_settings = [];
//	}
//	foreach ( $post_types as $post_type_key => $post_type ) {
//		if ( ! in_array( $post_type_key, $enable_post_types ) ) {
//			unset( $post_types[ $post_type_key ] );
//		}
//		foreach ( $post_types_settings as $setting ) {
//			if ( $setting["post_type"] === $post_type_key ) {
//				$post_types[ $post_type_key ]["label"] = $setting["label"];
//			}
//		}
//	}
//	foreach ( $post_types as $post_type_name => $post_type ) {
//		$labels = array(
//			"name"          => $post_type["label"],
//			"singular_name" => $post_type["label"],
//		);
//		$args   = array(
//			"label"               => $post_type["label"],
//			"labels"              => $labels,
//			"description"         => "",
//			"public"              => true,
//			"publicly_queryable"  => true,
//			"show_ui"             => true,
//			"show_in_rest"        => false,
//			"rest_base"           => "",
//			"has_archive"         => true,
//			"show_in_menu"        => true,
//			"show_in_nav_menus"   => true,
//			"exclude_from_search" => false,
//			"capability_type"     => "post",
//			"map_meta_cap"        => true,
//			"hierarchical"        => false,
//			"rewrite"             => array( "slug" => $post_type_name, "with_front" => false ),
//			"query_var"           => true,
//			"supports"            => array( "title", "editor", "thumbnail" ),
//		);
//		register_post_type( $post_type_name, $args );
//		$labels = array(
//			"name"          => $post_type["taxonomy"]["label"],
//			"singular_name" => $post_type["taxonomy"]["label"],
//		);
//		$args   = array(
//			"label"              => $post_type["taxonomy"]["label"],
//			"labels"             => $labels,
//			"public"             => true,
//			"hierarchical"       => true,
//			"show_ui"            => true,
//			"show_in_menu"       => true,
//			"show_in_nav_menus"  => true,
//			"query_var"          => true,
//			"rewrite"            => array( 'slug' => $post_type["taxonomy"]["name"], 'with_front' => false, ),
//			"show_admin_column"  => true,
//			"show_in_rest"       => false,
//			"rest_base"          => $post_type["taxonomy"]["name"],
//			"show_in_quick_edit" => true,
//		);
//		register_taxonomy( $post_type["taxonomy"]["name"], array( $post_type_name ), $args );
//	}
} );


function growp_html_url() {
	return GUrl::asset();
}

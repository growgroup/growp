<?php
/**
 * テーマのための関数
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */

use Growp\Customizer\Customizer;
use Growp\Editor\AcfBlock;
use Growp\Editor\Acf;
use Growp\Editor\BlockEditor;
use Growp\Hooks\Backend;
use Growp\Hooks\Frontend;
use Growp\Resource\Resource;
use Growp\Template\Foundation;

/**
 * バージョン情報の出力
 * キャッシュ対策
 */
define( 'GROWP_VERSION', '1.0.0' );

// テンプレートのパス
define( 'GROWP_TEMPLATE_PATH', __DIR__ );

// CSSファイル
define( "GROWP_STYLESHEET_URL", get_stylesheet_directory_uri() . "/assets/css/style.css" );

// テーマのJavaScriptファイル
define( "GROWP_JAVASCRIPT_URL", get_stylesheet_directory_uri() . "/assets/js/scripts.js" );

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/src/Growp/TemplateTag/Proxy.php";
require_once __DIR__ . "/vendor/aristath/kirki/kirki.php";

//require_once __DIR__ . "/vendor/";

add_action( "registered_taxonomy", function () {
	Frontend::get_instance();
	Backend::get_instance();
	Foundation::get_instance();
	Resource::get_instance();
	Acf::get_instance();
	AcfBlock::get_instance();
	Customizer::get_instance();
	BlockEditor::get_instance();
} );

function register_components() {
	$resource   = Resource::get_instance();
	$components = $resource->html_metadata["components"];
	foreach ( $components as $component_key => $component ) {
		foreach ( $component as $cname => $c ) {

			$block_post_id = wp_insert_post( [
				'post_title'   => trim( $cname ),
				'post_type'    => "growp_acf_block",
				'post_status'  => "publish",
				'post_content' => $c,
			] );

			update_field( "block_name", str_replace( " ", "_", trim( $cname ) ), $block_post_id );
			update_field( "block_title", $cname, $block_post_id );
			update_field( "block_render_callback", $c, $block_post_id );
			update_field( "block_category", "growp-blocks", $block_post_id );
			update_field( "block_icon", '', $block_post_id );
			update_field( "block_mode", "preview", $block_post_id );
			update_field( "block_post_types", get_post_types( [ "public" => true ] ), $block_post_id );
			update_field( "block_custom_template", $c, $block_post_id );
			update_field( "block_acf_settings", false, $block_post_id );
		}
	}
}

//register_components();
/**
 * テンプレートタグ定義
 */

/**
 * アクションフック定義
 */
require_once __DIR__ . "/src/hooks/comment.php";
require_once __DIR__ . "/src/hooks/default-plugins.php";
require_once __DIR__ . "/src/hooks/extras.php";
//require_once __DIR__ . "/src/hooks/setup.php";
require_once __DIR__ . "/src/hooks/sidebar.php";


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
	$post_types          = growp_get_post_types();
	$enable_post_types   = GTag::get_option( "growp_posttypes_swtich" );
	$post_types_settings = GTag::get_option( "growp_posttypes_asset" );
	if ( ! $enable_post_types ) {
		$enable_post_types = [];
	}


	if ( ! $post_types_settings ) {
		$post_types_settings = [];
	}
	foreach ( $post_types as $post_type_key => $post_type ) {
		if ( ! in_array( $post_type_key, $enable_post_types ) ) {
			unset( $post_types[ $post_type_key ] );
		}
		foreach ( $post_types_settings as $setting ) {
			if ( $setting["post_type"] === $post_type_key ) {
				$post_types[ $post_type_key ]["label"] = $setting["label"];
			}
		}
	}
	foreach ( $post_types as $post_type_name => $post_type ) {
		$labels = array(
			"name"          => $post_type["label"],
			"singular_name" => $post_type["label"],
		);
		$args   = array(
			"label"               => $post_type["label"],
			"labels"              => $labels,
			"description"         => "",
			"public"              => true,
			"publicly_queryable"  => true,
			"show_ui"             => true,
			"show_in_rest"        => false,
			"rest_base"           => "",
			"has_archive"         => true,
			"show_in_menu"        => true,
			"show_in_nav_menus"   => true,
			"exclude_from_search" => false,
			"capability_type"     => "post",
			"map_meta_cap"        => true,
			"hierarchical"        => false,
			"rewrite"             => array( "slug" => $post_type_name, "with_front" => false ),
			"query_var"           => true,
			"supports"            => array( "title", "editor", "thumbnail" ),
		);
		register_post_type( $post_type_name, $args );
		$labels = array(
			"name"          => $post_type["taxonomy"]["label"],
			"singular_name" => $post_type["taxonomy"]["label"],
		);
		$args   = array(
			"label"              => $post_type["taxonomy"]["label"],
			"labels"             => $labels,
			"public"             => true,
			"hierarchical"       => true,
			"show_ui"            => true,
			"show_in_menu"       => true,
			"show_in_nav_menus"  => true,
			"query_var"          => true,
			"rewrite"            => array( 'slug' => $post_type["taxonomy"]["name"], 'with_front' => false, ),
			"show_admin_column"  => true,
			"show_in_rest"       => false,
			"rest_base"          => $post_type["taxonomy"]["name"],
			"show_in_quick_edit" => true,
		);
		register_taxonomy( $post_type["taxonomy"]["name"], array( $post_type_name ), $args );
	}
} );

/*
Plugin Name: ACF Customizer Patch
Plugin URI: https://gist.github.com/fabrizim/9c0f36365f20705f7f73
Description: A class to allow acf widget fields to be stored with normal widget settings and allow for use in customizer.
Author: Mark Fabrizio
Version: 1.0
Author URI: http://owlwatch.com/
*/
class acf_customizer_patch
{

	protected $_capture_options = false;
	protected $_captured_options = array();
	protected $_instance;

	public function __construct()
	{
		add_filter('widget_display_callback', array($this, 'before_widget_display'), 20, 3);
		add_filter('in_widget_form',          array($this, 'before_edit_form'),       9, 3);
		add_filter('widget_update_callback',  array($this, 'before_save_acf_fields'), 9, 3);
		add_filter('pre_update_option',       array($this, 'pre_update_option'),      8, 3);
		add_filter('widget_update_callback',  array($this, 'after_save_acf_fields'), 11, 3);

		// need to include the acf input scripts
		// This could be included in the 'acf_input_listener' class
		add_action('customize_controls_print_footer_scripts', array( $this, 'admin_footer'), 20 );
	}

	public function admin_footer() {
		do_action('acf/input/admin_footer');
	}

	/**
	 * There may be a better way to find out if we are in the customizer,
	 * but this works for now. If someone has a better way, let me know.
	 */
	protected function in_customizer()
	{
		return @$_REQUEST['wp_customize'] == 'on' || basename($_SERVER['SCRIPT_NAME']) == 'customize.php';
	}

	/**
	 * @wp.filter       widget_display_callback
	 * @wp.priority     20
	 */
	public function before_widget_display( $instance, $widget, $args )
	{
		$this->prepare_widget_options( $widget, $instance );
		return $instance;
	}

	/**
	 * @wp.filter       in_widget_form
	 * @wp.priority     9
	 */
	public function before_edit_form( $widget, $return, $instance )
	{
		$this->prepare_widget_options( $widget, $instance );
		return $widget;
	}

	public function prepare_widget_options( $widget, $instance )
	{
		$this->_instance = $instance;
		$field_groups = acf_get_field_groups(array(
			'widget'  => $widget->id_base
		));

		if( count( $field_groups ) ) foreach( $field_groups as $group  ){
			$fields = acf_get_fields( $group );
			if( count($fields) ) foreach( $fields as $field ){
				$name = $field['name'];
				add_filter("pre_option_widget_{$widget->id}_{$name}", array($this, 'pre_get_option') );
			}
		}
		return $widget;
	}

	public function pre_get_option( $value )
	{
		$filter = current_filter();
		$name = str_replace('pre_option_widget_', '', $filter);
		preg_match('/(.+\-\d+?)_(.+)/', $name, $matches );

		list( $full, $widget_id, $var ) = $matches;

		if( $this->_instance && isset( $this->_instance[$var] ) ) return $this->_instance[$var];
		return $value;
	}

	/**
	 * @wp.filter       widget_update_callback
	 * @wp.priority     9
	 */
	public function before_save_acf_fields( $instance, $new_instance, $old_instance )
	{
		global $wp_customize;
		$this->_capture_options = true;
		if( $this->in_customizer() )
			remove_filter( 'pre_update_option', array( $wp_customize->widgets, 'capture_filter_pre_update_option' ), 10);
		return $instance;
	}

	/**
	 * @wp.filter       pre_update_option
	 * @wp.priority     8
	 */
	public function pre_update_option( $value, $option, $old_value )
	{
		global $wp_customize;

		if( !$this->_capture_options ) return $value;

		if( preg_match('/^([^_].+\-\d+?)_(.+)/', $option, $matches ) ){
			$this->_captured_options[$matches[2]] = $value;
		}
		// if this is not an acf field, this should be the actual
		// widget option (which needs to be captured by the customizer)
		if( !preg_match('/^(.+\-\d+?)_(.+)/', $option ) && $this->in_customizer() ){
			$wp_customize->widgets->capture_filter_pre_update_option( $value, $option, $old_value );
		}

		return $this->in_customizer() ? $old_value : $value;
	}

	/**
	 * @wp.filter       widget_update_callback
	 * @wp.priority     11
	 */
	public function after_save_acf_fields( $instance, $new_instance, $old_instance )
	{
		$instance = array_merge( $instance, $this->_captured_options );
		$this->_capture_options = false;
		return $instance;
	}

}
$acf_customizer_patch = new acf_customizer_patch();

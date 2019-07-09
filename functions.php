<?php
/**
 * テーマのための関数
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */

use Growp\Acf\AcfBlock;
use Growp\Hooks\Backend;
use Growp\Hooks\Frontend;
use Growp\Resource\Resource;
use Growp\Template\Foundation;
use Growp\Acf\Acf;
use Growp\Mock\FrontAndHome;
use Growp\Mock\MwWpForm;
use Growp\Mock\PrivacyPolicy;
use Growp\Mock\Sitemap;
use Growp\Mock\TinymceAdvanced;
use Growp\Mock\WpAdminUiCustomize;


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

// composer
require_once __DIR__ . "/vendor/autoload.php";

Frontend::get_instance();
Backend::get_instance();
Foundation::get_instance();
$resource = Resource::get_instance();

function register_components() {
	$resource   = Resource::get_instance();
	$components = $resource->html_metadata["components"];
	foreach ( $components as $component_key => $component ) {
		foreach ( $component as $cname => $c ) {

			$block_post_id = wp_insert_post( [
				'post_title'   => $cname,
				'post_type'    => "growp_acf_block",
				'post_status'  => "publish",
				'post_content' => $c,
			] );

			update_field( "block_name", str_replace( " ", "_", $cname ), $block_post_id );
			update_field( "block_title", $cname, $block_post_id );
			update_field( "block_render_callback", $c, $block_post_id );
			update_field( "block_category", "layout", $block_post_id );
			update_field( "block_icon", "admin-site", $block_post_id );
			update_field( "block_mode", "preview", $block_post_id );
			update_field( "block_post_types", ["post","page"], $block_post_id );
			update_field( "block_custom_template", $c, $block_post_id );
		}
	}
}
//register_components();

Acf::get_instance();
new AcfBlock();
require_once __DIR__ . "/src/Growp/TemplateTag/Proxy.php";

/**
 * テンプレートタグ定義
 */

/**
 * アクションフック定義
 */
require_once __DIR__ . "/src/hooks/comment.php";
require_once __DIR__ . "/src/hooks/default-plugins.php";
require_once __DIR__ . "/src/hooks/extras.php";
require_once __DIR__ . "/src/hooks/scripts.php";
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
class GG_BlockEditor {

	public function __construct() {
		add_filter( "init", [ $this, "setup" ] );
//		$this->add_assets();
//		$this->print_scripts();
//		$this->use_post_type();
	}

	/**
	 * テーマサポートの追加、
	 * エディタースタイルの設定
	 *
	 * @return void
	 */
	public function setup() {
		add_theme_support( 'editor-styles' );
		add_theme_support( 'align-wide' );

//		add_editor_style( "overwrite.css" );
	}

	/**
	 * 追加のCSSを読み込み
	 *
	 * @return void
	 */
	public function add_assets() {
//		add_action( 'enqueue_block_editor_assets', function () {
//			wp_enqueue_style( 'growp_site_css', get_theme_file_uri("hoge.css"), [ 'wp-block-library' ] );
//			wp_enqueue_script( 'growp_site_javascript', get_theme_file_uri("hoge.js"), [ 'wp-block-library' ] );
//		} );
	}

	/**
	 * 管理画面で有効化
	 *
	 * @return void
	 */
	public function print_scripts() {
		add_action( 'admin_print_footer_scripts', function () {
			?>
			<script>
				// wp.domReady(function () {
				// 	$('#editor .editor-writing-flow').addClass('l-post-content');
				// });
			</script>
			<?php
		} );
	}

	public function use_post_type() {
		add_filter( 'use_block_editor_for_post', function ( $user_block_editor, $post ) {
			// 固定ページか、works 投稿タイプの時はブロックエディターをオフにする
			// if (
			//  ( $post->post_type === 'page' )
			//  ||( $post->post_type === 'works' )
			// ) {
			//  $use_block_editor = false;
			// } else {
			//  $use_block_editor = true;
			// }
			return $use_block_editor;
		}
			, 10, 2 );
	}
}

new GG_BlockEditor();

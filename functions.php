<?php
/**
 * テーマのための関数
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */

/**
 * バージョン情報の出力
 * キャッシュ対策
 */
define( 'GROWP_VERSIONING', '1.0.0' );

// テンプレートのパス
define( 'GROWP_TEMPLATE_PATH', dirname( __FILE__ ) );

// CSSファイル
define( "GROWP_STYLESHEET_URL", get_stylesheet_directory_uri() . "/assets/css/style.css" );

// テーマのJavaScriptファイル
define( "GROWP_JAVASCRIPT_URL", get_stylesheet_directory_uri() . "/assets/js/scripts.js" );

// composer
require_once dirname( __FILE__ ) . "/vendor/autoload.php";

/**
 * テーマのための class
 */
require_once dirname( __FILE__ ) . "/src/classes/class-theme-wrapper.php";
require_once dirname( __FILE__ ) . "/src/classes/class-menu-posts.php";
require_once dirname( __FILE__ ) . "/src/classes/class-post-type.php";
require_once dirname( __FILE__ ) . "/src/classes/class-tgm-plugin-activation.php";
require_once dirname( __FILE__ ) . "/src/classes/class-walker-comment.php";
require_once dirname( __FILE__ ) . "/src/classes/class-walker-nav.php";
require_once dirname( __FILE__ ) . "/src/classes/class-sitemap.php";

/**
 * 初期コンテンツの作成
 */
require_once dirname( __FILE__ ) . "/src/mock/mock.php";

/**
 * テンプレートタグ定義
 */
require_once dirname( __FILE__ ) . "/src/tags/nav.php";
require_once dirname( __FILE__ ) . "/src/tags/tag.php";
require_once dirname( __FILE__ ) . "/src/tags/template.php";
require_once dirname( __FILE__ ) . "/src/tags/url.php";

/**
 * アクションフック定義
 */
require_once dirname( __FILE__ ) . "/src/hooks/comment.php";
require_once dirname( __FILE__ ) . "/src/hooks/default-plugins.php";
require_once dirname( __FILE__ ) . "/src/hooks/extras.php";
require_once dirname( __FILE__ ) . "/src/hooks/scripts.php";
require_once dirname( __FILE__ ) . "/src/hooks/setup.php";
require_once dirname( __FILE__ ) . "/src/hooks/sidebar.php";


// テンプレートを固定ページとして作成
// growp-setup を利用した際に有効
function growp_create_pages()
{
   if (!get_option("growp_create_pages")) {
        $files = glob(__DIR__ . "/page-*.php");
        foreach ($files as $file) {
            $fileheaders = get_file_data($file, ["Page Slug", "Template Name", "Page Template Name"]);
            $post_id = wp_insert_post([
                'post_type' => "page",
                'post_title' => $fileheaders[1],
                'post_name' => $fileheaders[0],
                'post_content' => "",
                'post_status' => "publish",
            ]);
            update_post_meta($post_id, "_wp_page_template", $fileheaders[2]);
        }
        update_option("growp_create_pages", true);
   }
}

// add_action("init", "growp_create_pages");

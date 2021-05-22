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
require_once dirname( __FILE__ ) . "/src/classes/class-yoast-seo-index-clear.php";
// require_once dirname( __FILE__ ) . "/src/classes/class-acfadminbar.php";

/**
 * モデル
 */
require_once dirname( __FILE__ ) . "/src/models/base_post.php";
require_once dirname( __FILE__ ) . "/src/models/base_term.php";
require_once dirname( __FILE__ ) . "/src/models/related_posts.php";
require_once dirname( __FILE__ ) . "/src/models/category.php";
require_once dirname( __FILE__ ) . "/src/models/post.php";

// 開発ツール (不要な際はコメントアウト)
// require_once dirname( __FILE__ ) . "/src/classes/class-devtool.php";

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
        update_option("growp_create_pages", true);
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
   }
}

// add_action("init", "growp_create_pages");


function growp_acf_op_init() {

	// Check function exists.
	if ( function_exists( 'acf_add_options_page' ) ) {
		// Register options page.
		$option_page = acf_add_options_page( array(
			'page_title' => "サイトオプション",
			'menu_title' => "サイトオプション",
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'edit_posts',
			'redirect'   => false
		) );
	}
}

// ACF オプションページを利用する場合は以下のコメントアウトを外す
add_action( 'acf/init', 'growp_acf_op_init' );


function growp_fontloader() {
	// TODO: 以下にWebフォントロード用のスクリプトを記述する
	?>
	<script>
	</script>
	<?php
}

add_action('wp_head', 'growp_fontloader',1);

function growp_editor_webfont() {
	// TODO: 以下にGoogleFontsのインポート用URLを記述する
	$font_url = 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Roboto+Condensed:wght@700&display=swap';
	add_editor_style( $font_url );
}
add_action( 'after_setup_theme', 'growp_editor_webfont',1 );



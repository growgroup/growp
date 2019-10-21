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
use Growp\Shortcode\Shortcode;
use Growp\Template\Foundation;
use Growp\TemplateTag\Utils;

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

	Shortcode::get_instance();
	Foundation::get_instance();

	Utils::load_modules( "active_editor" );

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

function growp_html_url() {
	return GUrl::asset();
}


add_shortcode( "growp_children_pagenav", function () {
	global $post;
	if ( $post->post_parent === 0 ) {
		return "";
	}
	$parent_id      = $post->post_parent;
	$parent         = get_post( $parent_id );
	$parent_title   = $parent->post_title;
	$children_pages = get_pages( [
		'sort_column' => 'menu_order',
		'parent'      => $parent_id,
	] );
	if ( ! $children_pages ) {
		return "";
	}
	ob_start();
	?>
	<div class="c-relation">
		<div class="l-container">
			<div class="c-relation__title"><span>NEXT CONTENT</span>
				<small>その他<?php echo $parent_title ?></small>
			</div>
			<div class="c-relation__button">
				<ul class="row">
					<?php
					foreach ( $children_pages as $page ) {
						?>
						<div class="large-3 small-6">
							<li class="<?php echo is_page( $page->ID ) ? "is-active" : "" ?>">
								<a class="c-button" href="<?php echo get_the_permalink( $page->ID ) ?>"><span><?php echo get_the_title( $page->ID ) ?></span></a>
							</li>
						</div>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
	</div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
} );

<?php
/**
 * Setup script for this theme
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================''
 */

/**
 * テーマのセットアップ
 * @return void
 */


function growp_setup() {

	load_theme_textdomain( 'growp', get_template_directory() . '/languages' );
	load_theme_textdomain( 'tgmpa', get_template_directory() . '/languages' );

	// automatic feed をサポート
	add_theme_support( 'automatic-feed-links' );

	// パンくず をサポート
	add_theme_support( 'growp-breadcrumbs' );

	// ページネーション をサポート
	add_theme_support( 'growp-pagination' );

	// アイキャッチ画像のサポート
	add_theme_support( 'post-thumbnails' );

	// メニューのサポート
	add_theme_support( 'menus' );

	// タイトルタグをサポート
	add_theme_support( 'title-tag' );


	// HTML5構造化マークアップで出力
	add_theme_support(
		'html5',
		array(
			'comment-list',
			'search-form',
			'comment-form',
			'gallery',
			'caption',
		)
	);

	// editor-style を登録
	add_editor_style( GROWP_STYLESHEET_URL );

	add_filter( 'growp_asset_url', function ( $url ) {
		return $url . '?ver=' . GROWP_VERSIONING;
	} );
}

add_action( 'after_setup_theme', 'growp_setup' );

/**
 * wp_head() で出力されるタグの調整
 *
 * @return void
 */
function growp_head_cleanup() {

	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

	global $wp_widget_factory;

	remove_action( 'wp_head',
		array(
			$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
			'recent_comments_style',
		)
	);
}

add_filter( 'init', 'growp_head_cleanup', 10 );


/**
 * 著者一覧を表示しない
 */
function growp_protect_authorpage() {
	if ( is_author() ) {
		wp_redirect( home_url( '/' ) );
		exit;
	}
}

add_action( 'template_redirect', 'growp_protect_authorpage' );


// 登録のサンプル
function growp_register_menus() {
	new GROWP_MenuPosts( 'global_nav', 'グローバルナビゲーション' );
}

add_action( "registered_taxonomy", "growp_register_menus" );


/**
 * 編集者の権限を変更し、ユーザーを追加することができるように
 */
function growp_add_editor_roles() {
	$role = get_role( 'editor' );
	$role->add_cap( 'delete_users' );
	$role->add_cap( 'create_users' );
	$role->add_cap( 'remove_users' );
	$role->add_cap( 'edit_users' );
	$role->add_cap( 'edit_user' );
	$role->add_cap( 'promote_users' );
	$role->add_cap( 'promote_user' );
	$role->add_cap( 'list_users' );
}

add_action( 'admin_init', 'growp_add_editor_roles' );

/**
 * 管理者以外は新規ユーザー登録で管理者権限アカウントを追加できないように
 */
function growp_filter_editable_roles( $all_roles ) {

	$current_user = wp_get_current_user();
	if ( isset( $current_user->roles[0] ) && $current_user->roles[0] !== "administrator" ) {
		unset( $all_roles["administrator"] );
	}

	return $all_roles;
}

add_filter( "editable_roles", "growp_filter_editable_roles" );

/**
 * TinyMCEのspanタグ等の自動削除を停止
 */
function growp_override_mce_options( $init_array ) {
	global $allowedposttags;

	$init_array['valid_elements']          = '*[*]';
	$init_array['extended_valid_elements'] = '*[*]';
	$init_array['valid_children']          = '+a[' . implode( '|', array_keys( $allowedposttags ) ) . ']';
	$init_array['indent']                  = true;
	$init_array['wpautop']                 = false;
	$init_array['force_p_newlines']        = false;

	return $init_array;
}

add_filter( 'tiny_mce_before_init', 'growp_override_mce_options' );

/**
 * 保存時のiframe等の自動削除を停止
 *
 * @param $content
 *
 * @return mixed
 */
function growp_content_save_pre( $content ) {
	global $allowedposttags;

	// iframeとiframeで使える属性を指定する
	$allowedposttags['iframe'] = array(
		'class'        => array(),
		'src'          => array(),
		'width'        => array(),
		'height'       => array(),
		'frameborder'  => array(),
		'scrolling'    => array(),
		'marginheight' => array(),
		'marginwidth'  => array(),
		'style'        => array()
	);
	$allowedposttags['script'] = array(
		'async'        => array(),
		'class'        => array(),
		'src'          => array(),
		'charset'      => array(),
		'width'        => array(),
		'height'       => array(),
		'frameborder'  => array(),
		'scrolling'    => array(),
		'marginheight' => array(),
		'marginwidth'  => array(),
		'style'        => array()
	);

	return $content;
}
add_filter( 'content_save_pre', 'growp_content_save_pre' );


/**
 * ページヘッダーのフィルター
 *
 * @param $init_array
 */
function growp_page_headers( $pageheaders ) {
	// ACF での更新をサポートする際
	// if ( is_page() ) {
	// 	$_pageheaders            = array(
	// 		'title'    => get_field( "title", get_the_ID() ),
	// 		'subtitle' => get_field( "subtitle", get_the_ID() ),
	// 		'image'    => get_field( "image", get_the_ID() ),
	// 	);
	// 	$pageheaders["title"]    = ( isset( $_pageheaders["title"] ) && $_pageheaders["title"] ) ? $_pageheaders["title"] : $pageheaders["title"];
	// 	$pageheaders["subtitle"] = ( isset( $_pageheaders["subtitle"] ) && $_pageheaders["subtitle"] ) ? $_pageheaders["subtitle"] : $pageheaders["subtitle"];
	// 	$pageheaders["image"]    = ( isset( $_pageheaders["image"] ) && $_pageheaders["image"] ) ? wp_get_attachment_image_url( $_pageheaders["image"], 'full' ) : $pageheaders["image"];
	// }
	return $pageheaders;
}

add_filter( "growp/page_header", 'growp_page_headers' );

function growp_icpo_admin_style() {
?>
<style>#the-list .ui-sortable-placeholder {display:none;}</style>
<?php
}
add_action( 'admin_head', 'growp_icpo_admin_style' );
 



add_action( 'admin_footer', 'mw_wp_form_format_mail' );

function mw_wp_form_format_mail() {
	$screen = get_current_screen();
	if ( $screen->id === "mw-wp-form" && $screen->base === "post" ) {
	?>
	<script type="html/template" id="mw_wp_form_copymailtext">
		<div id="mw-wp-form_copymailtext" class="postbox">
			<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">パネルを閉じる: アドオン</span><span class="toggle-indicator" aria-hidden="true"></span></button>
			<h2 class="hndle ui-sortable-handle"><span>メール内容</span></h2>
			<div class="inside">
				<p>現在のフォームからメール返信文の一部を自動生成します。</p>
				<p>
					<textarea name="copymailtext" id="" cols="32" rows="10"></textarea>
					<a href="#" class="js-copymailtext button button-primary">メール文面を生成</a>
				</p>
			</div>
		</div>
	</script>
	<script>
		(function ($) {
			var GenerateMWWPFormMailText = function () {
				return this;
			};
			GenerateMWWPFormMailText.prototype = {
				init: function () {
					$("#mw-wp-form_addon").before($("#mw_wp_form_copymailtext").html());
					this.generate();
				},
				generate: function () {
					var $form = $("#content");
					var form_html = $form.val();
					var lines = form_html.match(/\[mwform.*?name=\"(.*?)\"/gi);
					var $names = ["【 お問い合わせ内容 】"];
					var _list = [];
					for (var i = 0; i < lines.length; i++) {
						var line = lines[i].match(/\[mwform.*?name=\"(.*?)\"/i);
						if (line[1] === "submit") {
							continue;
						}
						if (line[1] === "個人情報の取り扱いについて同意する") {
							continue;
						}
						if (line[1] === "個人情報保護方針について同意する") {
							continue;
						}
						var _name = line[1];
						$names.push('');
						$names.push('[' + _name + ']');
						$names.push('{' + _name + '}');
					}
					var $textarea = $("textarea[name=copymailtext]");
					$textarea.val($names.join("\n"));
					$textarea.html($names.join("\n"));
				},
				trigger: function () {
					var self = this;
					$(".js-copymailtext").on("click", function (e) {
						e.preventDefault();
						self.generate();
						$("#mw-wp-form_validation .add-btn").click();
					});
				}
			}
			window.GenerateMWWPFormMailTextObject = new GenerateMWWPFormMailText();
			GenerateMWWPFormMailTextObject.init();
		})(jQuery)
	</script>
	<?php
	}
}

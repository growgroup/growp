<?php
/**
 * Setup script for this theme
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================''
 */

/**
 * テーマのセットアップ
 * @return void
 */


function epigone_setup(){

	load_theme_textdomain( 'epigone', get_template_directory() . '/languages' );
	load_theme_textdomain( 'tgmpa', get_template_directory() . '/languages' );

	// automatic feed をサポート
	add_theme_support( 'automatic-feed-links' );

	// パンくず をサポート
	add_theme_support( 'epigone-breadcrumbs' );

	// ページネーション をサポート
	add_theme_support( 'epigone-pagination' );

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

	// ヘッダーナビゲーションを登録
	register_nav_menus( array( 'primary' => __( 'Header Primary Navigation', 'epigone' ) ) );

	// editor-style を登録
	add_editor_style( 'assets/css/editor-style.css' );

}

add_action( 'after_setup_theme', 'epigone_setup' );

/**
 * wp_head() で出力されるタグの調整
 *
 * @return void
 */
function epigone_head_cleanup(){

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

add_filter( 'init', 'epigone_head_cleanup', 10 );


/**
 * アバターの取得
 * @param  string $avatar
 * @param  string $type
 * @return string
 */

function epigone_get_avatar( $avatar, $type ){

	if ( ! is_object( $type ) ) {
		return $avatar;
	}

	$avatar = str_replace( "class='avatar", "class='avatar left media-object", $avatar );

	return $avatar;

}
add_filter( 'get_avatar', 'epigone_get_avatar', 10, 2 );

/**
 * 検索テンプレートを変更
 *
 * @param $form string
 * @return string フォームのHTML
 */
function epinoge_search_form( $form ) {

	ob_start();
	get_template_part( 'modules/searchform' );
	$form = ob_get_clean();
	return $form;

}
add_filter( 'get_search_form', 'epinoge_search_form' );


/**
 * パンくずの読み込み
 *
 * @return void
 */
function epigone_include_breadcrumbs(){

	if ( current_theme_supports( 'epigone-breadcrumbs' ) ) {
		get_template_part( 'modules/breadcrumbs' );
	}

}

add_action( 'get_footer', 'epigone_include_breadcrumbs' );


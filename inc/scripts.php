<?php
/**
 * テーマで使用する js, cssの登録
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

add_action( 'wp_enqueue_scripts', 'epigone_scripts', 100 );

function epigone_scripts() {

	/**
	 * メインとなるCSS
	 * @since 1.0.0
	 */
	wp_enqueue_style( 'epigone_main', EPIGONE_STYLESHEET_URL, false, null );

	/**
	 * 追加用CSS
	 */
	wp_enqueue_style( 'epigone_main', get_template_directory_uri() . "/shame.css", false, null );

	/**
	 * テーマのメインjsファイル
	 * @since 1.0.0
	 */
	wp_register_script( 'epigone_scripts', EPIGONE_JAVASCRIPT_URL, array('jquery'), null, true );

	/**
	 * コメント欄が有効なページでは、
	 * 返信用のjsを登録
	 */
	if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'epigone_plugins' );
	wp_enqueue_script( 'epigone_scripts' );

}

/**
 * link タグに付与されるid属性を削除
 * @since 1.2.0
 */
function epigone_clean_style_tag( $input ) {

	preg_match_all( "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches );

	$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
	return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}
add_filter( 'style_loader_tag', 'epigone_clean_style_tag' );

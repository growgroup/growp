<?php
/**
 * テーマで使用する js, cssの登録
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

add_action( 'wp_enqueue_scripts', 'growp_scripts', 100 );

function growp_scripts() {
	/**
	 * 読み込むスタイルシートを定義
	 */
	$styles = array(
		// 子テーマの方のスタイルシートを読み込み
		array(
			'handle' => "main",
			'src'    => GROWP_STYLESHEET_URL,
			'deps'   => array(),
			'media'  => "all",
		),
		// app.css
		array(
			'handle' => "overwrite",
			'src'    => get_theme_file_uri("/assets/css/app.css"),
			'deps'   => array(),
			'media'  => "all",
		),
		// 上書き用のスタイルシートを登録
		array(
			'handle' => "overwrite",
			'src'    => get_theme_file_uri("/overwrite.css"),
			'deps'   => array(),
			'media'  => "all",
		),
	);

	foreach ( $styles as $style_key => $style ) {
		$style = wp_parse_args( $style, array(
			'handle' => $style_key,
			"src"    => "",
			'deps'   => array(),
			'media'  => "all",
			'ver'    => GROWP_VERSIONING,
		) );
		extract( $style );
		wp_enqueue_style( "growp_" . $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media'] );
	}

	/**
	 * 読み込むJsファイルを定義
	 */
	$javascripts = array(

		// 子テーマの app.js を登録
		array(
			'handle'    => "app", // ハンドル名
			'src'       => get_theme_file_uri("/assets/js/app.js"), // ファイルのURL
			'deps'      => array( "jquery" ), // 依存するスクリプトのハンドル名
			'in_footer' => true, // wp_footer に出力
		),

		// 子テーマのメインとなる scripts.js を登録
		array(
			'handle'    => "scripts",
			'src'       => GROWP_JAVASCRIPT_URL,
			'deps'      => array( "jquery" ),
			'in_footer' => true,
		),
	);

	foreach ( $javascripts as $js_key => $js ) {
		$js = wp_parse_args( $js, array(
			'handle'    => $js_key,
			'deps'      => array(),
			'media'     => "all",
			'in_footer' => true,
			'ver'       => GROWP_VERSIONING,
		) );

		wp_enqueue_script( "growp_" . $js['handle'], $js['src'], $js['deps'], $js['ver'], $js['in_footer'] );
	}

	/**
	 * コメント欄が有効なページでは、
	 * 返信用のjsを登録
	 */
	if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

/**
 * link タグに付与されるid属性を削除
 *
 * @params $input string
 * @since 1.2.0
 */
function growp_clean_style_tag( $input ) {
	preg_match_all( "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input,
		$matches );
	$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';

	return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}

add_filter( 'style_loader_tag', 'growp_clean_style_tag' );

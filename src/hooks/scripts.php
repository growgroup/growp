<?php
/**
 * テーマで使用する js, cssの登録
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

use Growp\Resource\Resource;


add_action( 'wp_enqueue_scripts', 'growp_scripts', 10 );

function growp_scripts() {


	$resource = Resource::get_instance();


	$styles = [];
	foreach ( $resource->css_files as $key => $file ) {
		$styles[] = [
			'handle' => basename( $file ) . $key,
			'src'    => get_theme_file_uri( $file ),
			'deps'   => [],
			'media'  => 'all',
		];
	}

	foreach ( $styles as $style_key => $style ) {
		$style = wp_parse_args( $style, array(
			'handle' => $style_key,
			"src"    => "",
			'deps'   => array(),
			'media'  => "all",
			'ver'    => GROWP_VERSION,
		) );
		extract( $style );
		wp_enqueue_style( "growp_" . $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media'] );
	}

	/**
	 * 読み込むJsファイルを定義
	 */
	foreach ( $resource->js_files as $key => $file ) {
		$javascripts[] = [
			'handle' => basename( $file ) . $key,
			'src'    => get_theme_file_uri( $file ),
			'deps'      => array( "jquery" ), // 依存するスクリプトのハンドル名
			'in_footer' => true, // wp_footer に出力
		];
	}

	foreach ( $javascripts as $js_key => $js ) {
		$js = wp_parse_args( $js, array(
			'handle'    => $js_key,
			'deps'      => array(),
			'media'     => "all",
			'in_footer' => true,
			'ver'       => GROWP_VERSION,
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


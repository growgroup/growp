<?php
/**
 * その他必要な設定
 *
 * @package growp
 */

/**
 * body に付与するタグをカスタマイズ
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function growp_body_classes( $classes ) {
	global $post;

	// スラッグが設定されている場合出力
	if ( isset( $post->post_name ) ) {
		$classes[] = $post->post_name;
	}

	return $classes;
}



/**
 * 抜粋文をカスタマイズ
 *
 * @return void
 * @since 1.2.1
 */

add_action( 'excerpt_more', 'growp_change_more' );

function growp_change_more( $more ) {
	if ( 0 == get_theme_mod( 'single_char_num', 50 ) ) {
		return "";
	}
	$more = ' &hellip; <span class="c-button is-more">' . __( 'More', 'growp' ) . '</span>';

	return apply_filters( 'growp_readmore', $more );

}

/**
 * 抜粋文の長さをカスタマイズ
 *
 * @param $length
 *
 * @return int
 */

add_filter( 'excerpt_length', function ( $length ) {
	return 80;
}, 999 );


/**
 * コンポーネントをショートコードで呼び出し
 */
function growp_shortcode_get_component( $atts ) {
	$atts = shortcode_atts( array(
		'name' => '',
	), $atts, 'growp_component' );
	if ( empty( $atts["name"] ) ) {
		return "";
	}
	ob_start();
	GTemplate::get_component( $atts["name"] );
	$content = ob_get_contents();
	ob_clean();

	return $content;
}

add_shortcode( 'growp_component', 'growp_shortcode_get_component' );

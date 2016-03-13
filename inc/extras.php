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
 * @return array
 */
function growp_body_classes( $classes ) {
	global $post;

	// スラッグが設定されている場合出力
	if ( isset($post->post_name) ) {
		$classes[] = $post->post_name;
	}

	return $classes;
}
add_filter( 'body_class', 'growp_body_classes' );


/**
 * 抜粋文をカスタマイズ
 *
 * @since 1.2.1
 * @return void
 */

add_action( 'excerpt_more', 'growp_change_more' );

function growp_change_more( $more ) {
	if ( 0 == get_theme_mod('single_char_num', 50) ) {
		return "";
	}
	$more = ' &hellip; <a href="' . get_permalink() . '" class="btn btn-more">' . __( 'More', 'growp' ) . '</a>';
	return apply_filters( 'growp_readmore', $more );

}

/**
 * 抜粋文の長さをカスタマイズ
 * @param $length
 *
 * @return int
 */
function growp_excerpt_length( $length ) {
	return 80;
}
add_filter( 'excerpt_length', 'growp_excerpt_length', 999 );

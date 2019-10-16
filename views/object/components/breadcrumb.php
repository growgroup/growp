<?php
/**
 * パンくずコンポーネント
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
if ( is_front_page() ) {
	return false;
}
// WordPress SEO by Yoast のパンくずを利用する
if ( function_exists( 'yoast_breadcrumb' ) ) {
	yoast_breadcrumb( '<div class="l-container"><div class="c-breadcrumb">', '</div></div>' );
}

<?php
/**
 * サイト共通 ページヘッダー
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */

/**
 * トップページでは非表示に
 */
if ( is_front_page() ) {
	return false;
}

?>
<header class="c-page-header">
	<h1 class="heading is-xlg c-page-header__text">
		<?php
		// 固定ページ
		if ( is_page() ) {
			echo get_the_title();
		} else if ( is_single() ) {
			echo "ブログ";
		} else {
			echo GTag::get_archive_title();
		}
		?>
	</h1>
</header>

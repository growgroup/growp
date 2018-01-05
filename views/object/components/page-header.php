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

global $post;
$title    = apply_filters( 'growp/page_header/title', get_the_title() );
$subtitle = apply_filters( 'growp/page_header/subtitle', mb_strtoupper( $post->post_name ) );
$image    = apply_filters( 'growp/page_header/image', GUrl::asset( '/assets/images/bg-page-header' . $post->post_name . '.jpg' ) );
?>
<header class="c-page-header" style="background-image: url(<?php $image ?>)">
	<h1 class="heading is-xlg c-page-header__text">
		<?php echo $title ?>
	</h1>
	<p><?php echo $subtitle ?></p>
</header>

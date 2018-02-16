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
$title       = apply_filters( 'growp/page_header/title', get_the_title() );
$subtitle    = apply_filters( 'growp/page_header/subtitle', mb_strtoupper( $post->post_name ) );
$image       = apply_filters( 'growp/page_header/image', GUrl::asset( '/assets/images/bg-page-header-' . $post->post_name . '.jpg' ) );
$pageheaders = apply_filters( 'growp/page_header', array(
	'title'    => $title,
	'subtitle' => $subtitle,
	'image'    => $image,
) );
?>
<!-- ページヘッダー-->
<div class="c-page-header" style="background-image: url(<?php echo $pageheaders["image"] ?>)">
	<div class="c-page-header__inner">
		<h1><?php echo $pageheaders["title"] ?>
			<small><?php echo $pageheaders["subtitle"] ?></small>
		</h1>
	</div>
</div>

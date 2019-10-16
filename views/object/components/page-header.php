<?php
/**
 * サイト共通 ページヘッダー
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */

use Growp\Template\Foundation;

/**
 * トップページでは非表示に
 */
if ( is_front_page() ) {
	return false;
}
$vars = Foundation::get_vars();
global $post;

$title = apply_filters( 'growp/page_header/title', get_the_title() );

$subtitle = apply_filters( 'growp/page_header/subtitle', isset( $post->post_name ) ? strtoupper( $post->post_name ) : "" );

$image = apply_filters( 'growp/page_header/image', GUrl::asset( '/assets/images/img-page-header-format.jpg' ) );
if ( isset( $vars["pageheader_title"] ) && $vars["pageheader_title"] ) {
	$title = $vars["pageheader_title"];
}
if ( isset( $vars["pageheader_subtitle"] ) && $vars["pageheader_subtitle"] ) {
	$subtitle = $vars["pageheader_subtitle"];
}
if ( isset( $vars["pageheader_image"] ) && $vars["pageheader_image"] ) {
	$image = $vars["pageheader_image"];
}
if ( get_field( "page_header_title" ) ) {
	$title = get_field( "page_header_title" );
}
if ( get_field( "page_header_subtitle" ) ) {
	$subtitle = get_field( "page_header_subtitle" );
}
$pageheaders = apply_filters( 'growp/page_header', array(
	'title'    => $title,
	'subtitle' => $subtitle,
	'image'    => $image,
) );


?>
<div class="l-page-header">
	<div class="l-page-header__image">
		<img src="<?php echo $pageheaders["image"] ?>" alt="<?php echo $title ?>" />
	</div>
	<div class="l-page-header__inner">
		<h1 class="l-page-header__title"><?php echo $title ?></h1>
		<div class="l-page-header__subtitle"><?php echo $subtitle ?></div>
	</div>
</div>

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
$title       = apply_filters( 'growp/page_header/title', get_the_title() );
$subtitle    = apply_filters( 'growp/page_header/subtitle', mb_strtoupper( $post->post_name ) );
$image       = apply_filters( 'growp/page_header/image', GUrl::asset( '/assets/images/img-page-header-format.jpg' ) );
$pageheaders = apply_filters( 'growp/page_header', array(
	'title'    => $title,
	'subtitle' => $subtitle,
	'image'    => $image,
) );
return false;
?>
<div class="l-page-header">
	<div class="l-page-header__image">
		<img src="<?php echo $image ?>" alt="<?php echo $title ?>" />
	</div>
	<div class="l-page-header__inner">
		<h1 class="l-page-header__title"><?php echo $title ?></h1>
		<div class="l-page-header__subtitle"><?php echo $subtitle ?></div>
	</div>
</div>

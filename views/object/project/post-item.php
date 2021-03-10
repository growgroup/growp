<?php
/**
 * 記事一覧時の1記事分のテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
if ( GTag::is_info_link_enable() ) {
	echo '<a class="c-news__block" href="' . GTag::get_link_url() . '" target="' . GTag::get_link_target() . '">';

} else {
	echo '<div class="c-news__block">';
}
?>
<div class="c-news__sup">
	<div class="c-news__label">
		<?php echo GTag::get_first_term( get_the_ID(), "category", "name" ) ?>
	</div>
	<div class="c-news__date">
		<?php the_time( 'Y.m.d' ) ?>
	</div>
</div>
<div class="c-news__text">
	<?php the_title() ?><?php echo GTag::get_link_icon(); ?>
</div>
<?php
if ( GTag::is_info_link_enable() ) {
	echo '</a>';
} else {
	echo '</div>';
}
?>


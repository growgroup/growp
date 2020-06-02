<?php
/**
 * 記事一覧時の1記事分のテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>
<a class="c-news__block" href="<?php the_permalink() ?>">
	<div class="c-news__sup">
		<div class="c-news__label">
			<?php echo GTag::get_first_term( get_the_ID(), "category", "name" ) ?>
		</div>
		<div class="c-news__date">
			<?php the_time( 'Y.m.d' ) ?>
		</div>
	</div>
	<div class="c-news__text">
		<?php the_title() ?>
	</div>
</a>

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
	<div class="c-news__inner">
		<div class="c-news__text">
			<?php the_title() ?>
		</div>
	</div>
</a>

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
<a href="<?php the_permalink() ?>" id="post-<?php the_ID(); ?>" <?php post_class( 'p-post-item is-horizon' ); ?>>
	<div class="p-post-item__thumbnail" <?php GTag::the_thumbnail_style_attribute( get_the_ID() ) ?>></div>
	<h3 class="p-post-item__title"><?php the_title() ?></h3>
	<p class="p-post-item__detail">
		<?php the_excerpt() ?>
	</p>
	<div class="p-post-item__category c-label">
		<?php echo GTag::get_first_term( get_the_ID(), "category", "name" ) ?>
	</div>
</a>


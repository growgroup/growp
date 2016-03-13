<?php
/**
 * 投稿ページテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
	<header class="entry__header">
		<div class="entry__meta">
			<?php growp_posted_on(); ?>
		</div>
		<!-- .entry-meta -->

		<h1 class="entry__title"><?php the_title(); ?></h1>
	</header>
	<!-- .entry-header -->
	<?php growp_dynamic_sidebar('single-under-title'); ?>
	<div class="entry__content">
		<?php
		if (has_post_thumbnail() && 'true' === get_theme_mod( 'single_thumbnail', 'false' )  ) { ?>
			<div class="entry__thumbnail">
				<?php the_post_thumbnail(); ?>
			</div>
			<?php
		}

		the_content();

		if (!current_theme_supports('growp-pagination')) {
			wp_link_pages(array(
				'before' => '<div class="page-links">' . __('Pages:', 'growp'),
				'after' => '</div>',
			));
		}

		?>
	</div>
	<!-- .entry-content -->

	<footer class="entry__footer">
		<?php
		/* translators: used between list items, there is a space after the comma */
		$category_list = get_the_category_list(__(', ', 'growp'));

		/* translators: used between list items, there is a space after the comma */
		$tag_list = get_the_tag_list('', __(', ', 'growp'));

		if ( ! growp_categorized_blog()) {
			// This blog only has 1 category so we just need to worry about tags in the meta text
			if ('' != $tag_list) {
				$meta_text = __('This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'growp');
			} else {
				$meta_text = __('Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'growp');
			}

		} else {
			// But this blog has loads of categories so we should probably display them here
			if ('' != $tag_list) {
				$meta_text = __('This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'growp');
			} else {
				$meta_text = __('This entry was posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'growp');
			}

		} // end check for categories on this blog

		printf(
			$meta_text,
			$category_list,
			$tag_list,
			get_permalink()
		);
		?>

		<?php edit_post_link(__('Edit', 'growp'), '<span class="edit-link">', '</span>'); ?>
	</footer>
	<!-- .entry-footer -->
</article><!-- #post-## -->

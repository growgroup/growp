<?php
/**
 * デフォルトのコンテンツテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry--listcard' ); ?>>

	<header class="hentry__header">
		<?php if ('post' == get_post_type()) : ?>
			<div class="hentry__meta">
				<?php growp_posted_on(); ?>
			</div><!-- .hentry__meta -->
		<?php endif; ?>

		<h3 class="hentry__title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h3>

	</header>
	<!-- .hentry__header -->

	<?php if (is_search() || is_archive() || is_home() || is_front_page()) :  ?>
		<div class="hentry__summary clearfix">
			<?php
			if (has_post_thumbnail() && 'true' === get_theme_mod( 'single_thumbnail', 'false' )  ) {  ?>
				<?php the_post_thumbnail( 'thumbnail',array( 'class' => 'hentry__thumbnail left' ) ); ?>
			<?php
			} ?>
			<?php the_excerpt(); ?>
		</div><!-- .hentry-summary -->
	<?php else : ?>
		<div class="hentry__content">
			<?php
			the_post_thumbnail();
			the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'growp'));

			if (!current_theme_supports('growp-pagination')) {
				wp_link_pages(array(
					'before' => '<div class="page-links">' . __('Pages:', 'growp'),
					'after' => '</div>',
				));
			}
			?>
		</div><!-- .hentry-content -->
	<?php endif;

	if ( "true" ==  get_theme_mod( 'single_post_category', 'true' ) || "true" ==  get_theme_mod( 'single_post_tags', 'true' ) ){ ?>


		<footer class="hentry__footer">
			<?php
			if (!post_password_required() && (comments_open() || '0' !== get_comments_number())) : ?>
				<span class="comments-link"><?php comments_popup_link(__('Leave a comment', 'growp'), __('1 Comment', 'growp'), __('% Comments', 'growp')); ?></span>        <?php
			endif; ?>

			<?php edit_post_link(__('Edit', 'growp'), '<span class="edit-link button tiny round"><i class="fa fa-pencil"></i> ', '</span>'); ?>
		</footer>
		<!-- .hentry-footer -->
		<?php
	}
	?>
</article><!-- #post-<?php the_ID(); ?> -->


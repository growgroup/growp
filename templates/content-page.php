<?php
/**
 * 固定ページテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry__header">
		<h1 class="entry__title"><?php the_title(); ?></h1>
	</header>

	<div class="entry__content">
		<?php
		the_content();

		if ( ! current_theme_supports( 'growp-pagination' ) ) {
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'growp' ),
				'after'  => '</div>',
			) );
		}
		?>
	</div><!-- .entry-content -->
	<?php
	edit_post_link( __( 'Edit', 'growp' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' );
	?>
</article><!-- #post-## -->

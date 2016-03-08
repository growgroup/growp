<?php
/**
 * Template Name: One Column Page
 */
/**
 * Template of the one-column page
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.2.0
 * =====================================================
 */
the_post();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header>

	<div class="entry-content">
		<?php

		the_content();

		if ( ! current_theme_supports( 'epigone-pagination' ) ) {
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'epigone' ),
				'after'  => '</div>',
			) );
		}
		?>
	</div><!-- .entry-content -->
	<?php
	edit_post_link( __( 'Edit', 'epigone' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' );
	?>
</article><!-- #post-## -->

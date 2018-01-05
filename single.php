<?php
/**
 * 投稿テンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */

while ( have_posts() ) :
	the_post();
	?>
	<div class="l-container">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>

			<h1 class="heading is-xlg"><?php the_title(); ?></h1>
			<div class="p-post__meta">
				<?php echo GTag::get_the_terms_label_list() ?>
			</div>
			<div class="l-post-content">
				<?php
				the_content();
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'growp' ),
					'after'  => '</div>',
				) );
				?>
			</div>
		</article><!-- #post-## -->

		<?php
		GNav::the_post_nav();
		?>
	</div>
	<?php
	if ( ( comments_open() || '0' != get_comments_number() ) ) {
		comments_template();
	}
	?>
<?php

endwhile; // end of the loop.

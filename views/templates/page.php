<?php
/**
 * Template Name: デフォルトテンプレート
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
	<div class="l-section is-bottom">
		<div class="l-container">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'page' ); ?>>
				<div class="l-post-content">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'growp' ),
						'after'  => '</div>',
					) );
					?>
				</div>
			</article>
		</div>
	</div>
	<?php

endwhile; // end of the loop.

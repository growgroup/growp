<?php
/**
 * Template Name: 幅狭テンプレート
 * Foundation: free
 * Title: タイトル
 * Subtitle: サブタイトル
 * Formatting: false
 */

use Growp\Template\Component;

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'page' ); ?>>
		<?php
		the_content();
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'growp' ),
			'after'  => '</div>',
		) );
		?>
	</article>
<?php


endwhile; // end of the loop.

<?php
/**
 * Template Name: 自動整形なし
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */

the_post();
remove_filter( 'the_content', 'wpautop' );
?>
	<div class="l-section">
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

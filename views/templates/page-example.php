<?php
/**
 * Template Name: サンプルページ
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */


the_post();
?>
<div class="l-section">
	<div class="l-container">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page' ); ?>>
			<div class="l-post-content">
				<?php
				the_content();
				?>
			</div>
		</article>
	</div>
</div>

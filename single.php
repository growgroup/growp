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
	<div class="post-wrapper">
		<?php

		get_template_part( 'templates/content', 'single' );

		growp_post_nav();

		// 関連する記事を取得
		growp_related_post();

		if ( "true" == get_theme_mod('single_comment_num', 'true') && ( comments_open() || '0' != get_comments_number() ) ) {
			comments_template();
		}
		?>

	</div><!--/.post-wrapper-->
	<?php

endwhile; // end of the loop.

<?php
/**
 * ブログのトップページ
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */

if ( have_posts() ) :
	?>
	<div class="l-container">
		<?php
		while ( have_posts() ) :
			the_post();
			GTemplate::get_project( "post-item" );
		endwhile;
		?>
	</div>

	<?php
	// ページネーション
	echo GNav::get_paging_nav();
else :
	get_template_part( 'templates/content', 'none' );
endif;


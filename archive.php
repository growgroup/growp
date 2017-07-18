<?php
/**
 * アーカイブテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */
?>
<div class="l-container">
	<?php
	if ( have_posts() ) :
		/* ループをスタート */
		while ( have_posts() ) : the_post();
			GTemplate::get_project( "post-item" );
		endwhile;
		echo GNav::get_paging_nav();
	else :
		get_template_part( 'content', 'none' );
	endif;

	?>
</div>

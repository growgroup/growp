<?php
/**
 * メインインデックステンプレート
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		get_template_part( 'templates/content', get_post_format() );
	endwhile;
	growp_paging_nav();
else :
	get_template_part( 'templates/content', 'none' );
endif;

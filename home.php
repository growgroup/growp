<?php
/**
 * ブログのトップページ
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */

if ( have_posts() ) :
	?>
	<div class="clearfix">
		<?php
		while ( have_posts() ) :
			the_post();
			if ( 'normal' == get_theme_mod( 'home_post_list', 'normal' ) ) {
				get_template_part( 'templates/content', get_post_format() );
			} else {
				get_template_part( 'templates/content-tile', get_post_format() );
			}
		endwhile;
		?>
	</div>
	<?php
	epigone_paging_nav();
else :
	get_template_part( 'templates/content', 'none' );
endif;


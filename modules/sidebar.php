<?php
/**
 * サイドバーテンプレート
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>
<div id="secondary" class="widget-area" role="complementary">
	<?php
	if ( ! epigone_dynamic_sidebar( 'sidebar-primary' ) ) : ?>

		<aside id="search" class="widget widget_search">
			<?php get_search_form(); ?>
		</aside>

		<aside id="archives" class="widget">
			<h1 class="widget-title"><?php _e( 'Archives', 'epigone' ); ?></h1>
			<ul>
				<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
			</ul>
		</aside>

		<aside id="meta" class="widget">
			<h1 class="widget-title"><?php _e( 'Meta', 'epigone' ); ?></h1>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li> <?php wp_meta(); ?>
			</ul>
		</aside>

		<?php
	endif; // end sidebar widget area ?>
</div><!-- #secondary -->

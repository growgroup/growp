<?php
/**
 * ベーステンプレート
 * : テンプレート階層を上書きし、
 * 基本的にこのテンプレートを先に読み込みます。
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

get_template_part( 'modules/head' );

epigone_get_header(); ?>


	<div class="<?php echo esc_html( epigone_layout_class() ); ?> row wrapper">

		<div class="large-12 columns main-visual">
		 	<?php
			epigone_dynamic_sidebar( 'main-visual' ); ?>
		</div>

		<section class="l-main main columns">

			<main role="main">

				<?php
				epigone_dynamic_sidebar( 'content-primary' );

				// Action hook before loading the main template.
				do_action( 'get_main_template_before' );

				load_template( epigone_template_path() ); ?>

				<?php
				epigone_dynamic_sidebar( 'content-secondary' );
				// Action hook after loading the main template
				do_action( 'get_main_template_after' ); ?>

			</main>
		</section>

		<aside class="l-sidebar sidebar columns" role="aside">

			<?php
			/**
			 * Action Hook
			 */
			do_action( 'get_sidebar_template' );

			// Get sidebar
			get_template_part( 'modules/sidebar' ); ?>

		</aside>

	</div>

<?php
/**
 * Action hook "get_footer"
 */
do_action( 'get_footer' );

get_template_part( 'modules/footer' );

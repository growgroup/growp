<?php
/**
 * ワンカラム用のベーステンプレート
 * : wrapper-{template-slug}.php
 *   例 ) wrapper-archive.php
 *   というテンプレートを作ることで、
 *   ベーステンプレートをオリジナルで作ることができます。
 * =====================================================
 * @package  epigone
 * @license  GPLv2 or later
 * @since 1.2.0
 * =====================================================
 */

get_template_part( 'modules/head' );

epigone_get_header();

dynamic_sidebar( 'main-visual' );
?>

	<section class="l-one-col layout container wrapper">

		<main class="l-main main grid-lg-12" role="main">

		<?php
		dynamic_sidebar( 'content-primary' );

		/**
		 * @action メインテンプレートを読み込む前のアクションフック
		 */
		do_action( 'get_main_template_before' );

		load_template( epigone_template_path() ); ?>

		<?php
		/**
		 * @action メインテンプレートを読み込んだ後のアクションフック
		 */
		do_action( 'get_main_template_after' ); ?>
		</main>

	</section>

<?php
/**
 * @action ヘッダーテンプレートを呼び出す前のアクションフック
 */
do_action( 'get_footer' );

get_template_part( 'modules/footer' );


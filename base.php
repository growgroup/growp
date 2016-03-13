<?php
/**
 * ベーステンプレート
 * : テンプレート階層を上書きし、
 * 基本的にこのテンプレートを先に読み込みます。
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

// modules/head.php を取得
get_template_part( 'modules/head' );

// ヘッダーを取得
growp_get_header();

do_action( 'get_main_template_before' );

?>
	<div class="l-container">
		<?php
		load_template( growp_template_path() );
		?>
	</div>
<?php
// テンプレート取得後のアクションフック
do_action( 'get_main_template_after' );

// サイドバー取得時のアクションフック
do_action( 'get_sidebar_template' );

// サイドバーを取得
get_template_part( 'modules/sidebar' );

// フッター取得前のアクションフック
do_action( 'get_footer' );

// フッターを取得
get_template_part( 'modules/footer' );

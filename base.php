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

// modules/head.php を呼び出す
get_template_part( 'modules/head' );

// ヘッダーを呼び出す
get_template_part( 'modules/header' );

// グローバルナビゲーションを呼び出す
get_template_part( 'modules/global-nav' );

// ページヘッダーを呼び出す
get_template_part( 'modules/page-header' );

?>
	<div class="l-main">
		<?php
		load_template( growp_template_path() );
		?>
	</div>
<?php

// サイドバーを取得
get_template_part( 'modules/sidebar' );

// フッター取得前のアクションフック
do_action( 'get_footer' );

// フッターを取得
get_template_part( 'modules/footer' );

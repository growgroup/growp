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
GTemplate::get_template("foundation/head");
GTemplate::get_layout("header");
GTemplate::get_layout("global-nav");
GTemplate::get_component("page-header");
?>
    <div class="l-main">
        <?php
        load_template(GTag::get_template_path());
        ?>
    </div>
<?php

GTemplate::get_layout("sidebar");

// フッター取得前のアクションフック
do_action('get_footer');

// フッターを取得
GTemplate::get_layout("footer");

<?php
/**
 * このテーマオリジナルのテンプレートタグ
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

/**
 * テンプレートのパスを取得する
 *
 * @return template path
 * @since 0.0.1
 */
function growp_template_path()
{
    return Theme_Wrapper::$main_template;
}

/**
 * テンプレートベースとなるファイルを取得する
 *
 * @return string template path
 * @since 0.0.1
 */

function growp_template_base()
{
    return Theme_Wrapper::$base;
}

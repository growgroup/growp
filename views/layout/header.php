<?php
/**
 * [レイアウト]
 * ヘッダー
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
// 管理画面＞サイトオプションより設定
$header_html = get_field("o_site_header","option");
echo do_shortcode($header_html);

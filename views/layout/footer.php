<?php
/**
 * [レイアウト]
 * フッター
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
?>

<?php GTemplate::get_component( "offer"); ?>

<?php
// 管理画面＞サイトオプションより設定
// 分岐がある場合は、ショートコード[growp_component name="util/year"]などで記載下さい。
$footer_html = get_field("o_site_footer","option");
echo do_shortcode($footer_html);
?>

<?php wp_footer(); ?>

</body>
</html>

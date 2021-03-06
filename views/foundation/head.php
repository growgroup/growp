<?php
/**
 * サイト共通 head 内 タグ
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="<?php GUrl::the_asset() ?>/assets/images/favicon.ico" rel="icon" />
<link href="<?php GUrl::the_asset() ?>/assets/images/favicon.ico" rel="shortcut icon" />
<link href="<?php GUrl::the_asset() ?>/assets/images/web-clipicon.png" rel="apple-touch-icon" />
<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
// 管理画面＞サイトオプションより設定
// スライドバー出力
$slidebar_html = get_field("o_site_slidebar","option");
echo do_shortcode($slidebar_html);
?>

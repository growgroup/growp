<?php
/**
 * Template Name: トップページ
 * Layout: twocolumn
 * Title: タイトル
 * Subtitle: サブタイトル
 * PageHeaderTitle: サブタイトル
 * PageHeaderSubtitle: サブタイトル
 * Formatting: false
 */
//$vars = Foundation::get_vars();
remove_filter( "the_content", "wpautop");
the_content();
?>

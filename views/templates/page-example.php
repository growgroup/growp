<?php
/**
 * Template Name: サンプルページ
 * Title: タイトル
 * Description: サブタイトル
 * PageHeaderTitle: サブタイトル
 * PageHeaderSubtitle: サブタイトル
 * PageHeaderImage: サブタイトル
 * Formatting: false
 */

the_post();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page' ); ?>>
	<div class="l-post-content">
		<?php
		the_content();
		?>
	</div>
</article>

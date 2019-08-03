<?php
/**
 * Template Name: テスト
 * Template Post Type: post
 */
while ( have_posts() ) {
	the_post();
	?>
	<div class="l-section">
		<div class="l-container">
			<?php
			GROWP_Sitemap::output_css(); // CSSを出力する
			GROWP_Sitemap::output( array(
				'active'               => array(
					'pages'            => true, // true: 固定ページの出力を有効に false: 無効
					'posts'            => true, // true: 投稿の出力を有効に false: 無効
					'custom_post_type' => true, // true: カスタム投稿タイプの出力を有効に false: 無効
					'taxonomy'         => true, // true: タクソノミーの出力を有効に false: 無効
				),
				'posts_per_page'       => 100, // 取得件数
				'exclude_ids'          => array(), // 除外するID:  eg. array(25,10,500)
				'exclude_post_type'    => array(), // 除外する投稿タイプ eg. array("shop")
				'exclude_taxonomy'     => array(), // 除外するタクソノミー eg array("tags")
				'cache'                => true,    // キャッシュを有効にするか
				'transient_key'        => "growp_sitemap", // Transient APIのキー
				'transient_expiration' => 60 * 60 * 24, // Transient APIの有効期限
			) );
			?>
		</div>
	</div>
	<?php

}


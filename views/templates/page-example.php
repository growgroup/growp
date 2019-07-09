<?php
/**
 * Template Name: サンプルページ
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */

// ページヘッダーの設定
add_filter( 'growp/page_header/title', function () {
	return 'テスト';
} );
add_filter( 'growp/page_header/subtitle', function () {
	return 'sub title';
} );
add_filter( 'growp/page_header/image', function () {
	return GUrl::asset( '/assets/images/exmaple01.jpg' );
} );

// GTemplateで呼び出すテンプレートを無効化
add_filter( 'growp/template/filepath', function ( $file ) {
	if ( $file === 'object/components/page-header' ) {
		return false;
	}
	return $file;
} );

while ( have_posts() ) :
	the_post();
	?>
	<div class="l-section">
		<div class="l-container">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'page' ); ?>>
				<div class="l-post-content">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'growp' ),
						'after'  => '</div>',
					) );
					?>
				</div>
			</article>
		</div>
	</div>
<?php

endwhile; // end of the loop.

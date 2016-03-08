<?php
/**
 * カテゴリーのタイル
 * @var [type]
 */
$categories = get_categories();
$category_iterate = 0;
foreach( $categories as $c ){
	if ( $category_iterate > 10 ){
		break;
	}
	$query = new WP_Query( array(
		'post_type' => 'post',
		'cat' => $c->term_id,
		'post_per_page' => 4,
	) );

	if ( $query->have_posts() ){
		while( $query->have_posts() ){
			$query->the_post();
			?>
			<h2 class="widget-title">最新の記事 : カテゴリ「<?php echo $c->name ?>」</h2>
			<?php
			get_template_part('templates/content-tile' );
		}
	}
	wp_reset_query();
	$category_iterate++;
}

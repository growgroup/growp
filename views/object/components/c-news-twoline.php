<?php

use Growp\TemplateTag\Tags;
$default_vars = [
	'title'          => "",
	'subtitle'       => "",
	'url'            => "",
	'post_type'      => "",
	'posts_per_page' => 5,
	'terms'          => "",
];
$vars         = wp_parse_args( $vars, $default_vars );

$taxonomy     = "";
if ( $vars["terms"] ) {
	$term     = get_term( $vars["terms"][0] );
	$taxonomy = $term->taxonomy;
}
?>
<div class="c-news-twoline">
	<div class="c-news-twoline__head">
		<h2 class="c-news-twoline__title c-heading is-xlg">
			<span><?php echo $vars["title"] ?></span>
			<small><?php echo $vars["subtitle"] ?></small>
		</h2>
		<div class="c-news-twoline__button">
			<a class="c-button is-sm" href="<?php echo $vars["url"] ?>">一覧を見る</a>
		</div>
	</div>
	<div class="c-news-twoline__content">
		<?php
		$args = [
			'post_type'      => $vars["post_type"],
			'posts_per_page' => $vars["posts_per_page"],
		];
		if ( $taxonomy ) {
			$args["tax_query"][] = [
				'taxonomy' => $taxonomy,
				'terms'    => $vars["terms"],
				'operator' => 'IN'
			];
		}
		$news_query = new WP_Query( $args );
		if ( $news_query->have_posts() ) {
			while ( $news_query->have_posts() ) {
				$news_query->the_post();
				?>
				<a class="c-news-twoline__block is-image-display" href="#">
					<div class="c-news-twoline__image">
						<img src="<?php echo Tags::get_thumbnail_url( get_the_ID(), "thumbnail" ) ?>" alt="">
					</div>
					<div class="c-news-twoline__item">
						<div class="c-news-twoline__date"><?php echo get_the_date( "Y.m.d" ) ?></div>
						<div class="c-news-twoline__text"><?php the_title() ?></div>
					</div>
				</a>
				<?php
			}
		} else {
			?>
			<p>まだ記事がありません。</p>
			<?php
		}
		?>

	</div>
</div>

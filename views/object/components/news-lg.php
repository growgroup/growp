<?php
/**
 * 記事一覧
 */

use Growp\TemplateTag\Tags;

$fields = $vars;
?>
<div class="c-news-lg <?php echo ( $vars["image_display"] == "show" ) ? 'is-image-display' : "" ?>">
	<?php
	$newslg_query     = new WP_Query( [
		'post_type'      => $fields["post_type"],
		'posts_per_page' => $fields["posts_per_page"],
	] );
	$post_type_object = get_post_type_object( $fields["post_type"] );
	//	dump($post_type_object);
	if ( $newslg_query->have_posts() ) {
		while ( $newslg_query->have_posts() ) {
			$newslg_query->the_post();
			$_post = get_post( get_the_ID() );
			$taxes = get_object_taxonomies( $_post );
			?>
			<a class="c-news-lg__block" href="<?php the_permalink() ?>">
				<?php
				if ( $vars["image_display"] == "show" ) {
				?>
				<div class="c-news-lg__image">
					<img src="<?php echo Tags::get_thumbnail_url( get_the_ID(), "thumbnail" ) ?>" alt="" />
				</div>
				<div class="c-news-lg__content">
					<?php
					}
					?>
					<div class="c-news-lg__sup">
						<div class="c-news-lg__label c-label is-sm">
							<?php
							if ( $taxes && isset( $taxes[0] ) ) {
								$primary_term = Tags::get_primary_term( $taxes[0] );
								if ( $primary_term ) {
									echo $primary_term->name;
								}
							}
							?>
						</div>
						<div class="c-news-lg__date"><?php echo get_the_date( "Y.m.d" ) ?></div>
					</div>
					<div class="c-news-lg__title"><?php the_title() ?></div>
					<div class="c-news-lg__excerpt">
						<?php the_excerpt() ?>
					</div>
					<?php if ( $vars["image_display"] == "show" ) { ?>
				</div>
			<?php } ?>
			</a>
			<?php
		}
	}
	wp_reset_query();
	?>

</div>

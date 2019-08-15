<?php
/**
 * Template Name: アーカイブ
 * Layout: twocolumn
 * PageHeaderTitle: お知らせ
 * PageHeaderSubtitle: NEWS
 * Formatting: false
 */

use Growp\Template\Component;
use Growp\TemplateTag\Nav;
use Growp\TemplateTag\Tags;

?>
<main class="l-main">
	<section class="l-section is-lg">
		<div class="l-container is-sm">
			<h2 class=" c-heading is-md is-line-under is-text-center is-color-primary"><?php echo Tags::get_archive_title() ?></h2>
			<div class="c-news-lg is-image-display ">
				<div class="c-news__content">
					<?php
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							?>
							<a class="c-news-lg__block" href="<?php the_permalink() ?>">
								<div class="c-news-lg__image">
									<img src="<?php echo Tags::get_thumbnail_url( get_the_ID(), "thumbnail" ) ?>" alt="" />
								</div>
								<div class="c-news-lg__content">
									<div class="c-news-lg__sup">
										<div class="c-news-lg__label c-label is-sm">
											<?php
											$primary_term = Tags::get_primary_term( "category" );
											if ( $primary_term ) {
												echo $primary_term->name;
											}
											?>
										</div>
										<div class="c-news-lg__date"><?php echo get_the_date( "Y.m.d" ) ?></div>
									</div>
									<div class="c-news-lg__title"><?php the_title() ?></div>
									<div class="c-news-lg__excerpt">
										<?php the_excerpt() ?>
									</div>
								</div>
							</a>
							<?php
						}
					}
					?>
				</div>
				<?php Nav::the_paging_nav() ?>
				<?php
				Component::get( "box-archive", [
					'post_type' => "post",
					'taxonomy'  => "category",
				] );
				?>
			</div>
		</div>
	</section>
</main>

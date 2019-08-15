<?php
/**
 * Template Name: 投稿詳細
 * Title: Blog
 * Subtitle: ブログ
 * PageHeaderTitle: ブログ
 * PageHeaderSubtitle: Blog
 * Formatting: false
 */
the_post();
?>
<main class="l-main">
	<section class="l-section is-lg">
		<div class="l-container">
			<div class="row">
				<div class="large-8 is-push-lg-2 small-12">
					<div class="l-post-content is-twocolumns">
						<div class="c-news-header">
							<h1 class="c-news-header__title"><?php the_title(); ?></h1>
							<div class="c-news-header__sup">
								<div class="c-news-header__label  c-label">
									<?php echo GTag::get_the_terms_label_list() ?>
								</div>
								<div class="c-news-header__date">
									<?php echo get_the_date( "Y.m.d" ) ?>
								</div>
								<div class="c-news-header__tag">
									<?php
									the_tags();
									?>
								</div>
							</div>
						</div>
						<div class="l-post-content__content">
							<?php
							the_content();
							wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'growp' ),
								'after'  => '</div>',
							) );
							?>
						</div>
						<hr class="c-hr">
						<?php
						GNav::the_post_nav();
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

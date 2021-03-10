<?php
/**
 * 投稿テンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */

the_post();
?>

<section class="l-section is-lg">
	<div class="l-container">
		<div class="row">
			<div class="large-8 is-push-lg-2 small-12">
				<div class="c-news-header">
					<h1 class="c-news-header__title">
						<?php the_title(); ?>
					</h1>
					<div class="c-news-header__sup">
						<div class="c-news-header__label">
							<?php echo GTag::get_first_term( get_the_ID(), "category", "name" ) ?>
						</div>
						<div class="c-news-header__date">
							<?php the_time( "Y.m.d" ); ?>
						</div>
					</div>
				</div>
				<?php
				if ( has_post_thumbnail() ) {
					?>
					<div class="u-text-center u-mbs is-sm">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
					<?php
				}
				?>
				<?php
				if ( GTag::is_external_info() ) {
					?>
					<div class="u-text-center u-mbs is-xxlg">

						<a class="c-button is-lg is-file" href="<?php echo GTag::get_link_url(); ?>" target="<?php echo GTag::get_link_target(); ?>">
							<?php
							echo GTag::get_link_label();
							?>
						</a>

					</div>
					<?php
				}
				?>
				<div class="l-post-content u-mbs is-sm">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'growp' ),
						'after'  => '</div>',
					) );
					?>
				</div>
				<div class="u-mbs">
					<div class="c-button-social">
						<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) {
							ADDTOANY_SHARE_SAVE_KIT();
						} ?>
					</div>
				</div>
				<hr class="c-hr">
				<?php
				GNav::the_post_nav();
				?>

				<?php
				if ( function_exists( "yarpp_get_related" ) ):
					$related_posts = yarpp_get_related( array( "post_type" => "post" ) );
					if ( $related_posts ) :
						?>
						<div class="u-mbs is-top is-lg">
							<div class="u-mbs is-bottom is-sm">
								<h2 class="c-heading is-lg is-border"><span>関連コンテンツ</span></h2>
							</div>
							<div class="c-card-post  is-tag-hidden">
								<div class="row">
									<?php
									global $post;
									foreach ( $related_posts as $post ) :
										setup_postdata( $post );
										GTemplate::get_project( "post-item" );
									endforeach;
									wp_reset_postdata();
									?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

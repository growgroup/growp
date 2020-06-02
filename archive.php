<?php
/**
 * アーカイブテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */
global $post;
$cat_slug    = "";
$current_cat = get_queried_object();
if ( $current_cat ) {
	$cat_slug = $current_cat->slug;
}
$terms     = GTag::get_all_term( "category", "", true );
$all_class = $cat_slug == "" ? "is-active" : "";
?>
<section class="l-section is-lower-case">
	<div class="l-container">
		<div class="row">
			<div class="large-10 is-push-lg-1 small-12">
				<div class="u-mbs is-bottom is-lg">
					<div class="c-box-archive">
						<div class="c-box-archive__block">
							<div class="c-box-archive__title">カテゴリ
							</div>
							<ul>
								<li>
									<a class=" <?php echo $all_class; ?>" href="<?php echo get_post_type_archive_link( 'post' ); ?>"><span>すべて</span></a>
								</li>
								<?php
								$count = 1;
								foreach ( $terms as $term ) {
									$class = "";
									$title = esc_html( $term->name );
									$link  = get_term_link( $term );
									$class = $cat_slug == $term->slug ? "is-active" : "";
									echo "<li><a class=\" {$class}\" href=\"{$link}\" ><span>{$title}</span></a></li>";
									$count ++;
								}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="c-news  is-onecolumn">
			<div class="row">
				<div class="large-10 is-push-lg-1 small-12">
					<div class="c-news__content">
						<?php
						if ( have_posts() ) :
							/* ループをスタート */
							while ( have_posts() ) : the_post();
								GTemplate::get_project( "post-item" );
							endwhile;
						else :
							get_template_part( 'content', 'none' );
						endif;
						?>
					</div>
					<?php
					echo GNav::get_paging_nav();
					?>
					<div class="u-mbs is-top is-lg">
						<div class="c-box-archive">
							<div class="c-box-archive__block">
								<div class="c-box-archive__title">年月アーカイブ
								</div>
								<ul>
									<?php
									$archive_str = wp_get_archives( [
										'type' => "monthly",
										'echo' => false
									] );

									echo $archive_str;
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

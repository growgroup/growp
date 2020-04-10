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
?>
<section class="l-section is-lg">
	<div class="l-container">
		<div class="c-tabs  is-index">
			<ul class="c-tabs__navs">
				<li>
					<a <?php echo ( is_home() ) ? 'class="is-active"' : "" ?> href="<?php echo home_url( "/column/" ) ?>">すべて</a>
				</li>
				<?php
				get_terms([
					'taxonomy' => 'category'
				]);
				$current_category = get_queried_object();
				foreach ( $items as $item ) {
					$active_class = "";
					if ( $item->term_id === $current_category->term_id ) {
						$active_class = "is-active";
					}
					?>
					<li>
						<a class="<?php echo $active_class ?>" href="<?php echo $item->get_archive_link(); ?>"><?php echo $item->get_name(); ?></a>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<div class="u-mbs is-top is-lg">
			<div class="c-card-post  is-tag-hidden">
				<?php
				if ( have_posts() ) {
					?>
					<div class="row">
						<?php
						while ( have_posts() ) {
							?>
							<div class="large-4 small-6">
								<?php
								the_post();
								GTemplate::get_component( "card-post-block" );
								?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				} ?>
			</div>
			<?php echo GNav::get_paging_nav(); ?>
			<div class="c-box-archive">
				<div class="c-box-archive__block">
					<div class="c-box-archive__title">年月アーカイブ
					</div>
					<ul>
						<?php
						wp_get_archives();
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

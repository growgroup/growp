<?php
/**
 * ヘッダーテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
?>

<header class="l-header" role="banner">
	<div class="l-container">
		<div class="row">
			<div class="large-12">
				<p class="l-header__description"><?php bloginfo( 'description' ) ?></p>
				<h1 class="l-header__logo">
					<a href="<?php echo home_url(); ?>">
						<?php
						if ( get_theme_mod( 'logo_image', '' ) ) {
							?>
							<img src="<?php echo get_theme_mod( 'logo_image','' ) ?>" alt="<?php bloginfo( 'name' ); ?>"/>
							<?php
						} else {
							bloginfo( 'name' );
						} ?>
					</a>
				</h1>
			</div>
		</div>
	</div>
</header>

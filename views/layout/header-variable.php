<?php
/**
 * [レイアウト]
 * ヘッダー
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */

use Growp\Menu\Menu;
use Growp\TemplateTag\Tags;
use Growp\TemplateTag\Url;

?>
<div class="l-header-variable">
	<div class="l-header-variable__content">
		<h1 class="l-header-variable__heading">
			<a href="<?php echo home_url() ?>">
				<img src="<?php Tags::the_option( "growp_base_logo_image_in_header" ) ?>" alt="<?php the_title() ?>" />
			</a>
		</h1>
		<div class="l-header-variable__text"><?php bloginfo( "description" ) ?></div>
		<nav class="l-header-variable__nav">
			<div class="l-header-variable__inner">
				<a class="l-header-variable__tel" href="tel: <?php Tags::option( "growp_base_tel_number01" ) ?>">
					<span><?php Tags::option( "growp_base_tel_number01" ) ?></span>
				</a>
			</div>
			<?php
			echo wp_nav_menu( [
				'location'   => "header_nav",
				'container'  => false,
				'menu_class' => 'l-header-nav'
			] );
			?>
		</nav>
		<a class="l-header-variable__button c-button is-sm" href="/contact/">
			<i class="fa fa-envelope" aria-hidden="true"></i>お問い合わせ
		</a>
	</div>
</div>

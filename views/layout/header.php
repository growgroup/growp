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
<header class="l-header">
	<div class="l-container">
		<div class="l-header__inner">
			<div class="l-header__heading">
				<a href="<?php echo home_url() ?>">
					<img src="<?php Tags::the_option( "growp_base_logo_image_in_header" ) ?>" alt="<?php the_title() ?>" />
				</a>
			</div>
			<div class="l-header__content">
				<ul class="l-header__submenu">
					<li>
						<a href="#"><i class="fa fa-hospital-o" aria-hidden="true"></i>ナビ01</a>
					</li>
					<li>
						<a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i>ナビ02</a>
					</li>
				</ul>
				<a class="l-header__tel" href="tel:<?php Tags::option( "growp_base_tel_number01" ) ?>">
					<span><i class="fa fa-phone" aria-hidden="true"></i><?php Tags::option( "growp_base_tel_number01" ) ?></span>
					<small><?php Tags::option( "growp_base_tel_time" ) ?></small>
				</a>
				<a class="l-header__button c-button is-sm" href="/contact/">
					<i class="fa fa-envelope" aria-hidden="true"></i>お問い合わせ
				</a>
			</div>
		</div>
	</div>
</header>
<nav class="l-global-nav">
	<div class="l-container">
		<div class="l-header__nav">
			<?php
			echo wp_nav_menu( [
				'location'   => "header_nav",
				'container'  => false,
				'menu_class' => 'l-header-nav'
			] );
			?>
		</div>
	</div>
</nav>

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
<div class="l-header-minimal">
	<div class="l-header-minimal__inner">
		<div class="l-header-minimal__text"><?php bloginfo( "description" ) ?></div>
		<a class="l-header-minimal__tel" href="tel: <?php Tags::option( "growp_base_tel_number01" ) ?>">
			<span><i class="fa fa-phone" aria-hidden="true"></i><?php Tags::option( "growp_base_tel_number01" ) ?></span>
			<small><?php Tags::option( "growp_base_tel_time" ) ?></small>
		</a>
	</div>
	<div class="l-header-minimal__content">
		<h1 class="l-header-minimal__heading">
			<a href="<?php echo home_url() ?>">
				<img src="<?php Tags::the_option( "growp_base_logo_image_in_header" ) ?>" alt="<?php the_title() ?>" />
			</a>
		</h1>
		<nav class="l-header-minimal__nav">
			<?php
			echo wp_nav_menu( [
				'location' => "header_nav",
				'container' => false,
				'menu_class' => 'l-header-nav'
			] );
			?>
		</nav>
		<a class="l-header-minimal__button c-button is-sm" href="/contact/">
			<i class="fa fa-envelope" aria-hidden="true"></i>お問い合わせ
		</a>
	</div>
</div>

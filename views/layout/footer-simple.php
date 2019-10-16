<?php
/**
 * [レイアウト]
 * フッター
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */

use Growp\TemplateTag\Tags;

?>
<footer class="l-footer-simple">
	<div class="l-container">
		<div class="l-footer-simple__content">
			<a class="l-footer-simple__logo" href="<?php echo home_url() ?>">
				<img src="<?php Tags::the_option( "growp_base_logo_image_in_footer" ) ?>" alt="<?php bloginfo( "name" ) ?>">
			</a>
			<address class="l-footer-simple__address">
				〒464-0850 愛知県名古屋市千種区今池3丁目12-20 KAビル6F
				<br>TEL：<?php Tags::the_option( "growp_base_tel_number01" ) ?> / FAX：<?php Tags::the_option( "growp_base_fax_number" ) ?></address>
			<div class="l-footer-simple__copyright">
				<small><?php Tags::the_option( "growp_base_copyright" ) ?></small>
			</div>
		</div>
	</div>
</footer>

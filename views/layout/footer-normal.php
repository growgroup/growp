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
<footer class="l-footer-normal">
	<div class="l-footer-normal__inner">
		<div class="l-footer-normal__content">
			<a class="l-footer-normal__logo" href="<?php echo home_url( "/" ) ?>">
				<img src="<?php Tags::the_option( "growp_base_logo_image_in_footer" ) ?>" alt="<?php bloginfo( "name" ) ?>" />
			</a>
			<address class="l-footer-normal__address">
				〒464-0850 愛知県名古屋市千種区今池3丁目12-20 KAビル6F<br>TEL：052-753-6413 / FAX：052-753-6414
			</address>
		</div>
		<div class="l-footer-normal__menu">
			<div class="l-footer-normal__block">
				<ul class="l-footer-normal__menulist">
					<li>
						<a href="/">ホーム</a>
					</li>
					<li>
						<a href="/aboutus/">私たちについて</a>
					</li>
					<li>
						<a href="/company/">会社案内</a>
					</li>
				</ul>
			</div>
			<div class="l-footer-normal__block">
				<div class="l-footer-normal__menutitle"><span>事業内容</span></div>
				<ul class="l-footer-normal__menulist is-sub">
					<li>
						<a href="/service/service1/">サービス1</a>
					</li>
					<li>
						<a href="/service/service2">サービス2</a>
					</li>
					<li>
						<a href="/service/service3/">サービス3</a>
					</li>
				</ul>
			</div>
			<div class="l-footer-normal__block">
				<div class="l-footer-normal__menutitle"><span>採用情報</span></div>
				<ul class="l-footer-normal__menulist is-sub">
					<li>
						<a href="/recruit/staff/">社員紹介</a>
					</li>
					<li>
						<a href="/recruit/offer/">募集要項</a>
					</li>
					<li>
						<a href="/recruit/entry/">エントリー</a>
					</li>
				</ul>
			</div>
			<div class="l-footer-normal__block">
				<ul class="l-footer-normal__menulist">
					<li>
						<a href="/news/">お知らせ</a>
					</li>
					<li>
						<a href="/faq/">よくあるご質問</a>
					</li>
					<li>
						<a href="/contact/">お問い合わせ</a>
					</li>
				</ul>
				<ul class="l-footer-normal__menulist is-sub is-menu-bottom">
					<li>
						<a href="/privacy-policy/">個人情報保護方針</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="l-footer-normal__copyright">
		<small><?php Tags::the_option( "growp_base_copyright" ) ?></small>
	</div>
</footer>

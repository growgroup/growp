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
<footer class="l-footer">
	<div class="l-container">
		<div class="l-footer__menu">
			<div class="l-footer__block">
				<ul class="l-footer__menulist">
					<li>
						<a href="/">ホーム</a>
					</li>
					<li>
						<a href="/aboutus/">私たちについて</a>
					</li>
					<li>
						<a href="/case/">導入事例</a>
					</li>
				</ul>
			</div>
			<div class="l-footer__block">
				<div class="l-footer__menutitle"><span>企業情報</span></div>
				<ul class="l-footer__menulist is-sub">
					<li>
						<a href="/company/greeting/">代表あいさつ</a>
					</li>
					<li>
						<a href="/company/profile/">会社概要</a>
					</li>
					<li>
						<a href="/company/history/">沿革</a>
					</li>
					<li>
						<a href="/recruit/">採用情報</a>
					</li>
				</ul>
			</div>
			<div class="l-footer__block">
				<div class="l-footer__menutitle"><span>事業紹介</span></div>
				<ul class="l-footer__menulist is-sub">
					<li>
						<a href="/service/traning/">社員研修事業</a>
					</li>
					<li>
						<a href="/service/project-management/">プロジェクトマネジメント事業</a>
					</li>
					<li>
						<a href="/service/system/">システム開発事業</a>
					</li>
				</ul>
			</div>
			<div class="l-footer__block">
				<div class="l-footer__menutitle"><span>お知らせ</span></div>
				<ul class="l-footer__menulist is-sub">
					<li>
						<a href="/news/">更新情報</a>
					</li>
					<li>
						<a href="/news/">ニュースリリース</a>
					</li>
				</ul>
			</div>
			<div class="l-footer__block">
				<ul class="l-footer__menulist">
					<li>
						<a href="/blog/">企業ブログ</a>
					</li>
					<li>
						<a href="/media/">企業メディア</a>
					</li>
					<li>
						<a href="/faq/">よくあるご質問</a>
					</li>
					<li>
						<a href="/voice/">お客様の声</a>
					</li>
					<li>
						<a href="/contact/">お問い合わせ</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="l-footer__content">
			<a class="l-footer__logo" href="<?php echo home_url() ?>">
				<img src="<?php Tags::the_option( "growp_base_logo_image_in_footer" ) ?>" alt="<?php bloginfo( "name" ) ?>" />
			</a>
			<address class="l-footer__address">〒464-0850 <br>愛知県名古屋市千種区今池3丁目12-20 KAビル6F<br>TEL：052-753-6413 / FAX：052-753-6414</address>
			<small class="l-footer__copyright">
				<?php Tags::the_option( "growp_base_copyright" ) ?>
			</small>
		</div>
	</div>
</footer>

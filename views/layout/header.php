<?php
/**
 * [レイアウト]
 * ヘッダー
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */

use Growp\TemplateTag\Url;


?>
<div class="l-header-minimal">
	<div class="l-header-minimal__inner">
		<div class="l-header-minimal__text">テキストテキストテキストテキストテキスト</div>
		<a class="l-header-minimal__tel" href="tel: 00-0000-0000"><span><i class="fa fa-phone" aria-hidden="true"></i>00-0000-0000</span>
			<small>受付時間 / 平日00：00～00：00</small>
		</a>
	</div>
	<div class="l-header-minimal__content">
		<h1 class="l-header-minimal__heading">
			<a href="/">
				<img src="<?php Url::the_asset() ?>/assets/images/logo.png" alt="株式会社サンプル" />
			</a>
		</h1>
		<nav class="l-header-minimal__nav">
			<ul>
				<li>
					<a href="/onecolumn/">1COLUMN</a>
				</li>
				<li>
					<a href="/twocolumns/">2COLUMN</a>
				</li>
				<li>
					<a href="/format/">FORMAT</a>
				</li>
				<li>
					<a href="/archive-onecolumn/">ARCHIVE1</a>
				</li>
				<li>
					<a href="/archive-twocolumns/">ARCHIVE2</a>
				</li>
			</ul>
		</nav>
		<a class="l-header-minimal__button c-button is-sm" href="/contact/">
			<i class="fa fa-envelope" aria-hidden="true"></i>お問い合わせ
		</a>
	</div>
</div>

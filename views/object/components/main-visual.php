<?php
/**
 * メインビジュアル
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
if ( ! is_front_page() ) {
	return false;
}
?>
<div class="c-main-visual">
	<div class="c-main-visual__slider  owl-carousel">
		<div class="c-main-visual__image" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-main-visual-01.jpg)">
		</div>
		<div class="c-main-visual__image" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-main-visual-01.jpg)">
		</div>
		<div class="c-main-visual__image" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-main-visual-01.jpg)">
		</div>
	</div>
	<div class="c-main-visual__inner">
		<div class="c-main-visual__text">
			<img src="<?php GUrl::the_asset() ?>/assets/images/img-main-visual-text.png" alt="キャッチコピーが入ります" />
		</div>
		<div class="c-main-visual__button">
			<a class="c-button is-lg" href="/aboutus/">詳細はこちら</a>
		</div>
	</div>
</div>

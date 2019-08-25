<?php
/**
 * Template Name: トップページ
 * Title: タイトル
 * Description: サブタイトル
 * PageHeaderTitle: サブタイトル
 * PageHeaderSubtitle: サブタイトル
 * Formatting: false
 */

//$vars = Foundation::get_vars();
use Growp\Hooks\SearchForm;
use Growp\Hooks\SearchModel;
use Growp\Template\Component;

global $post;

//echo $input->html();
//echo $select->html();
//exit;
?>
<div class="l-section is-xlg">
	<div class="l-container">

	</div>
</div>
<?php
if ( $post->post_content ) {
	remove_filter( "the_codntent", "wpautop" );
	the_content();
} else {

	?>
	<section class="l-section is-lg is-color-secondary">
		<div class="l-container">

			<h2 class="c-heading is-xlg is-bottom"><span>ABOUT</span>
				<small>私たちについて</small>
			</h2>
			<p class="u-text-center">テキストテキストテキストテキストテキストテキストテキストテキストテキスト<br>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
			<div class="c-card  u-mbs is-top is-lg">
				<div class="row">
					<div class="large-4 small-12">
						<div class="c-card__block">
							<div class="c-card__image">
								<img src="<?php GUrl::the_asset() ?>/assets/images/img-card-01.jpg" alt="" />
							</div>
							<div class="c-card__content">
								<h3 class="c-card__title">コンセプトタイトル</h3>
								<p class="c-card__text">ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。</p>
							</div>
						</div>
					</div>
					<div class="large-4 small-12">
						<div class="c-card__block">
							<div class="c-card__image">
								<img src="<?php GUrl::the_asset() ?>/assets/images/img-card-01.jpg" alt="" />
							</div>
							<div class="c-card__content">
								<h3 class="c-card__title">コンセプトタイトル</h3>
								<p class="c-card__text">ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。</p>
							</div>
						</div>
					</div>
					<div class="large-4 small-12">
						<div class="c-card__block">
							<div class="c-card__image">
								<img src="<?php GUrl::the_asset() ?>/assets/images/img-card-01.jpg" alt="" />
							</div>
							<div class="c-card__content">
								<h3 class="c-card__title">コンセプトタイトル</h3>
								<p class="c-card__text">ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="l-section is-lg is-top">
		<div class="l-container">
			<h2 class="c-heading is-xlg is-bottom"><span>SERVICE</span>
				<small>事業内容</small>
			</h2>
		</div>
		<div class="c-hero-block-square">
			<div class="c-hero-block-square__block">
				<div class="c-hero-block-square__image">
					<img src="<?php GUrl::the_asset() ?>/assets/images/img-hero-block-01.jpg" alt="" />
				</div>
				<div class="l-container">
					<div class="c-hero-block-square__content">
						<div class="c-hero-block-square__inner">
							<h3 class="c-heading is-sm is-bottom">タイトルがここに入ります。</h3>
							<p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
							<div class="c-hero-block-square__button">
								<a class="c-button is-sm" href="#">ボタンテキスト</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="c-hero-block-square__block">
				<div class="c-hero-block-square__image">
					<img src="<?php GUrl::the_asset() ?>/assets/images/img-hero-block-01.jpg" alt="" />
				</div>
				<div class="l-container">
					<div class="c-hero-block-square__content">
						<div class="c-hero-block-square__inner">
							<h3 class="c-heading is-sm is-bottom">タイトルがここに入ります。</h3>
							<p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
							<div class="c-hero-block-square__button">
								<a class="c-button is-sm" href="#">ボタンテキスト</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="c-hero-block-square__block">
				<div class="c-hero-block-square__image">
					<img src="<?php GUrl::the_asset() ?>/assets/images/img-hero-block-01.jpg" alt="" />
				</div>
				<div class="l-container">
					<div class="c-hero-block-square__content">
						<div class="c-hero-block-square__inner">
							<h3 class="c-heading is-sm is-bottom">タイトルがここに入ります。</h3>
							<p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
							<div class="c-hero-block-square__button">
								<a class="c-button is-sm" href="#">ボタンテキスト</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="l-section is-lg">
		<div class="l-container">
			<div class="c-news">
				<div class="row">
					<div class="large-3 small-12">
						<div class="c-news__head">
							<h2 class="c-news__title c-heading is-xlg"><span>NEWS</span>
								<small>お知らせ</small>
							</h2>
							<div class="c-news__button">
								<a class="c-button is-sm" href="/news/">一覧を見る</a>
							</div>
						</div>
					</div>
					<div class="large-9 small-12">
						<div class="c-news__content">
							<a class="c-news__block" href="/news/page/">
								<div class="c-news__date">2018.10.10
								</div>
								<div class="c-news__label  c-label is-white">カテゴリ
								</div>
								<div class="c-news__text">お知らせの投稿タイトルがここに入ります。
								</div>
							</a>
							<a class="c-news__block" href="/news/page/">
								<div class="c-news__date">2018.10.10
								</div>
								<div class="c-news__label  c-label is-white">カテゴリ
								</div>
								<div class="c-news__text">お知らせの投稿タイトルがここに入ります。
								</div>
							</a>
							<a class="c-news__block" href="/news/page/">
								<div class="c-news__date">2018.10.10
								</div>
								<div class="c-news__label  c-label is-white">カテゴリ
								</div>
								<div class="c-news__text">お知らせの投稿タイトルがここに入ります。
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
} ?>

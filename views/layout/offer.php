<?php
$default_vars = [
	'backgroundimage' => [
		'url' => GUrl::asset( "/assets/images/bg-offer.jpg" )
	],
	'title'           => "オファータイトルオファータイトル",
	'desc'            => "お問い合わせは下記からお願いします。",
	'tel'             => "00-000-0000",
	'time'            => "お電話での受付時間は平日 9:00〜18:00 （土日祝除く）",
	'button_url'      => "/contact/",
	'button_text'     => "メールでのお問い合わせはこちら",
	'button_label'    => "メールでのお問い合わせはこちら",
];
$vars         = wp_parse_args( $vars, $default_vars );
if ( ! $vars["backgroundimage"] ){
	$vars["backgroundimage"]["url"] = GUrl::asset( "/assets/images/bg-offer.jpg" );
}
?>
<div class="l-offer <?php echo $vars["align"] ?>" style="background-image: url(<?php echo $vars["backgroundimage"]["url"] ?>)">
	<div class="l-container">
		<div class="l-offer__inner">
			<h2 class="c-heading is-md is-bottom">
				<?php echo $vars["title"] ?>
			</h2>
			<div class="l-offer__text"><?php echo $vars["desc"] ?></div>
			<div class="l-offer__items">
				<div class="row">
					<div class="large-5 is-push-lg-1 small-12">
						<a class="l-offer__button is-tel" href="tel:<?php echo $vars["tel"] ?>">
							<i class="fa fa-phone" aria-hidden="true"></i><?php echo $vars["tel"] ?></a>
						<div class="l-offer__subtext"><?php echo $vars["time"] ?></div>
					</div>
					<?php
					if ( $vars["button_url"] ) {
						?>
						<div class="large-5 small-12">
							<a class="l-offer__button" href="<?php echo $vars["button_url"] ?>"><?php echo $vars["button_label"] ?></a>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$items = get_sub_field( 'items' );
if ( ! $items ) {
	return;
}
?>
<div class="l-container">

	<div class="c-block-container">
		<?php
		foreach ( $items as $item ) {
			$image_url = GTag::get_attachment_url( $item["image"] );
			?>
			<div class="c-block-container__block">

				<div class="c-block-container__content">
					<div class="c-block-container__title">
						<?php echo $item["title"]; ?>
					</div>
					<div class="c-block-container__text">
						<?php echo $item["text"]; ?>
					</div>
				</div>
				<div class="c-block-container__image">
					<img src="<?php echo $image_url; ?>" alt="">
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>

<?php
$items     = get_sub_field( 'items' );
$col_count = get_sub_field( 'number' );
if ( ! $items ) {
	return;
}
?>
<div class="l-container">
	<div class="c-number-cards  is-grid-system">
		<div class="row">
			<?php
			$col_class = "large-" . ( 12 / $col_count );
			foreach ( $items as $item ) {
				$image_url = GTag::get_attachment_url( $item["image"] );
				?>
				<div class="<?php echo $col_class; ?> small-12">
					<div class="c-number-cards__card">
						<div class="c-number-cards__image-wrap">
							<div class="c-number-cards__image" style="background-image: url(<?php echo $image_url; ?>)">
							</div>
						</div>
						<div class="c-number-cards__content">
							<div class="c-number-cards__title">
								<?php echo $item["title"]; ?>
							</div>
							<div class="c-number-cards__text">
								<?php echo $item["text"]; ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

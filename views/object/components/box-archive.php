<?php
/**
 * カテゴリ一覧
 */

use Growp\Template\Component;

$post_type = $vars["post_type"];
$taxonomy  = $vars["taxonomy"];
$terms     = get_terms( [
	'taxonomy' => $taxonomy,
] );
?>
<div class="c-box-archive">
	<?php
	if ( $terms ) {
		?>
		<div class="c-box-archive__block">
			<div class="c-box-archive__title">カテゴリ</div>
			<ul>
				<?php
				wp_list_categories( [
					'title_li' => false,
					'style'    => 'list',
					'taxonomy' => $taxonomy
				] );
				?>
			</ul>
		</div>
	<?php } ?>
	<div class="c-box-archive__block">
		<div class="c-box-archive__title">年月アーカイブ</div>
		<ul>
			<?php
			echo wp_get_archives( [
				'post_type' => $post_type
			] );
			?>

		</ul>
	</div>
</div>

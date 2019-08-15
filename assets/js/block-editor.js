(function ($) {
	if ($("*[data-name=block_custom_template] textarea").length) {
		wp.codeEditor.initialize($("*[data-name=block_custom_template] textarea"));
	}

	if (window.acf) {
		$(function () {
			$(".c-main-visual").each(function () {
				window.gappself = new window.GAppClass();
			});
		});

		function initialSlider($block) {
			window.gappself = new window.GAppClass();
		}

		window.acf.addAction('render_block_preview/type=c-main-visual', initialSlider);
		window.acf.addAction('render_block_preview/type=c-main-visual--is-slider', initialSlider);
		window.acf.addAction('render_block_preview/type=c-main-visual--is-carousel', initialSlider);
		$(document).on("click", ".js-growp-edit-block", function (e) {
			e.preventDefault();
			var $iframe = $('<iframe />', {
				src: acf.data.admin_url + '/post.php?post=' + $(this).data("block-id") + "&action=edit&edittype=inblock",
				style: "width: 100%; height: 90vh; border: none"
			});
			new jBox('Modal', {
				title: "ブロックを編集",
				content: $iframe,
				zIndex: 10000000,
				color: 'black',
				width: '90vw',
				height: '90vh',
				onClose: () => {
					// 閉じる際に強制的に読み込みし更新する
					let $selection = $(".wp-block.is-selected");
					let currentBlock = $selection.data("type");
					let currentBlockClientId = $selection.find("*[data-block]").data("block");
					let newblock = wp.blocks.createBlock(currentBlock, {});
					wp.data.dispatch('core/editor').replaceBlock(currentBlockClientId, newblock)
				}
			}).open();
		});
	}
})(jQuery);

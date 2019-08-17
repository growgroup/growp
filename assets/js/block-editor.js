;(function ($) {

	function initialize_code_field($el) {

		if ($el.parents(".acf-clone").length > 0) {
			return;
		}

		var $textarea = $el.find('.acf-input>textarea');

		var editor = window.CodeMirror.fromTextArea($textarea[0], {
			lineNumbers: true,
			fixedGutter: true,

			mode: {
				name: $textarea.attr("mode"),
				htmlMode: true
			},
			theme: $textarea.attr("theme"),
			extraKeys: {"Ctrl-Space": "autocomplete"},
			matchBrackets: true,
			styleSelectedText: true,
			smartIndent: true,
			autoRefresh: true,
			value: document.documentElement.innerHTML,
			viewportMargin: Infinity,
			autoCloseBrackets: true,
			autoCloseTags: true,
			continueComments: true,
			indentUint: 4,
			// indentWithTabs: true,
			styleActiveLine: true,
			inputStyle: "contenteditable",
		});
		console.log(editor);

		editor.on('change', function () {
			editor.save();
		});
	}

	$(function(){
		if (typeof acf.add_action !== 'undefined') {
			acf.add_action('ready_field/type=acf_code_field', initialize_code_field);
			acf.add_action('append_field/type=acf_code_field', initialize_code_field);
		} else {
			$(document).on('acf/setup_fields', function (e, postbox) {
				// find all relevant fields
				$(postbox).find('.field[data-field_type="acf_code_field"]').each(function () {
					// initialize
					initialize_code_field($(this));

				});
			});

		}
	});

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

(function ($) {
	if ($("*[data-name=block_custom_template] textarea").length) {
		wp.codeEditor.initialize($("*[data-name=block_custom_template] textarea"));
	}

	if( window.acf ) {
		$(function(){
			$(".c-main-visual").each(function(){
				window.gappself = new window.GAppClass();
			});
		});
		function initialSlider($block){
			window.gappself = new window.GAppClass();
		}
		window.acf.addAction( 'render_block_preview/type=c-main-visual', initialSlider );
	}
})(jQuery);

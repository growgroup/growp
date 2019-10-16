;(function ($) {

	var register_lock = false;

	$(document).on("click", "#growp_register_components", function (e) {
		e.preventDefault();
		if (register_lock) {
			return false;
		}
		register_lock = true;
		var $button = $(this);
		var $spinner = $button.find(".spinner").addClass("is-active");
		$.ajax({
			type: "post",
			url: ajaxurl,
			data: {
				action: "growp_register_components",
				nonce: GROWP_THEMECUSTOMIZER.nonce
			}
		}).done(function (res) {
			$spinner.removeClass("is-active");
			new jBox('Notice', {
				content: 'コンポーネントの登録に成功しました',
				zIndex: 10000000,
				color: 'black',
			}).open();
			register_lock = false;
		})
	})

})(jQuery);

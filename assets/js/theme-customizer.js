;(function ($) {

	$(document).on("click", "#growp_register_components", function (e) {
		e.preventDefault();
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
			alert(res.data.message);
		})
	})

})(jQuery);

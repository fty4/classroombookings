window.crbs.formToggles = (function($) {

	var pub = {

		isActive: true,

		hideField: function($form, input) {
			$form.find(input).hide();
		},

		showField: function($form, input) {
			$form.find(input).show();
		},

		disableField: function($form, input) {
			$form.find(input)
				.prop("checked", false)
				.removeAttr("checked")
				.prop("disabled", true);
		},

		enableField: function($form, input) {
			$form.find(input)
				.prop("disabled", false)
				.removeAttr("disabled");
		}

	};

	return pub;

})(window.jQuery);

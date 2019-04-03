window.crbs.settingsVisual = (function($) {

	var pub = {

		isActive: true,

		init: function() {
			initShowIf();
		},
	};

	// Handle for form
	var $from;

	function initShowIf() {
		$form = $("form[data-form='settings_visual']");
		$form.on('change', "input[name='displaytype']", toggleDisplayAxis);
		toggleDisplayAxis();
	}

	function toggleDisplayAxis() {
		var checkedVal = $form.find("input[name='displaytype']:checked").val();
		switch (checkedVal) {
			case 'day':
				enableField("input[data-name='d_columns'][data-value='periods']");
				enableField("input[data-name='d_columns'][data-value='rooms']");
				disableField("input[data-name='d_columns'][data-value='days']");
			break;
			case 'room':
				enableField("input[data-name='d_columns'][data-value='periods']");
				enableField("input[data-name='d_columns'][data-value='days']");
				disableField("input[data-name='d_columns'][data-value='rooms']");
			break;
		}
	}

	function disableField(input) {
		$form.find(input)
			.prop("checked", false)
			.removeAttr("checked")
			.prop("disabled", true);
	}

	function enableField(input) {
		$form.find(input)
			.prop("disabled", false)
			.removeAttr("disabled");
	}

	return pub;

})(window.jQuery);

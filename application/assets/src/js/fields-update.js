window.crbs.fieldsUpdate = (function($) {

	var pub = {

		isActive: true,

		init: function() {
			initShowIf();
		},
	};

	// Handle for form
	var $fieldsForm,
		formToggles = window.crbs.formToggles;

	function initShowIf() {
		$fieldsForm = $("form[data-form='field_update']");
		$fieldsForm.on('change', "input[name='type']", toggleOptions);
		toggleOptions();
	}

	function toggleOptions() {
		var target = '[data-field="options"]';
		var checkedVal = $fieldsForm.find("input[name='type']:checked").val();
		if (checkedVal == 'LS') {
			formToggles.showField($fieldsForm, target);
		} else {
			formToggles.hideField($fieldsForm, target);
		}
	}

	return pub;

})(window.jQuery);

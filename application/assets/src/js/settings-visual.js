window.crbs.settingsVisual = (function($) {

	var pub = {

		isActive: true,

		init: function() {
			initShowIf();
		},
	};

	// Handle for form
	var $settingsFrom = null,
		formToggles = window.crbs.formToggles;


	function initShowIf() {
		$settingsFrom = $("form[data-form='settings_visual']");
		$settingsFrom.on('change', "input[name='displaytype']", toggleDisplayAxis);
		toggleDisplayAxis();
	}

	function toggleDisplayAxis() {
		var checkedVal = $settingsFrom.find("input[name='displaytype']:checked").val();

		switch (checkedVal) {
			case 'day':
				formToggles.enableField($settingsFrom, "input[data-name='d_columns'][data-value='periods']");
				formToggles.enableField($settingsFrom, "input[data-name='d_columns'][data-value='rooms']");
				formToggles.disableField($settingsFrom, "input[data-name='d_columns'][data-value='days']");
			break;
			case 'room':
				formToggles.enableField($settingsFrom, "input[data-name='d_columns'][data-value='periods']");
				formToggles.enableField($settingsFrom, "input[data-name='d_columns'][data-value='days']");
				formToggles.disableField($settingsFrom, "input[data-name='d_columns'][data-value='rooms']");
			break;
		}
	}

	return pub;

})(window.jQuery);

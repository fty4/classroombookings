function CheckboxRevealer(container) {
	this.hiddenClass = 'd-none';
	this.checkboxes = container.find('[type="checkbox"]');
	this.checkboxes.on('click', $.proxy(this, 'onCheckboxClick'));
	this.setupHtml();
};

CheckboxRevealer.prototype.setupHtml = function() {
	this.checkboxes.each($.proxy(function(i, el) {
		var panelId = $(el).attr('data-aria-controls')
		if(panelId) {
			if(!el.checked) {
				$('#'+panelId).addClass(this.hiddenClass);
			} else {
				$('#'+panelId).removeClass(this.hiddenClass);
			}
		}
	}, this));
};

CheckboxRevealer.prototype.onCheckboxClick = function(e) {
	var checkbox = $(e.target);
	var panelId = checkbox.attr('data-aria-controls');
	if(panelId) {
		if(!checkbox[0].checked) {
			$('#'+panelId).addClass(this.hiddenClass);
		} else {
			$('#'+panelId).removeClass(this.hiddenClass);
		}
	}
};

/**
 * Bookings console functionality
 *
 */
function BookingsConsole(el) {

	this.SELECT_SINGLE = 'single';
	this.SELECT_MULTI = 'multi';

	this.KEY_CTRL = 17;

	// Set up elements
	this.console = el;
	this.grid = el.find('[data-ui="bookings_grid"]');
	this.cardsContainer = el.find('[data-ui="cards_container"]');
	this.loadingCard = this.cardsContainer.find('[data-ui="loading"]');

	// Single/Multi select mode
	this.selectMode = this.SELECT_SINGLE;
	this.ctrlPressed = false;

	// Preload bookings that are in the current view
	this.loadBookings();

	// Set up events
	this.grid.on('click', '[data-ui="booking"]', $.proxy(this, 'onBookingClick'));
	this.grid.on('click', '[data-ui="room"]', $.proxy(this, 'onRoomClick'));
	this.grid.on('change', 'input[type="checkbox"][data-ui="free"]', $.proxy(this, 'onFreeChange'));
	this.console.on('click', '[data-ui="make_booking"]', $.proxy(this, 'onMakeBookingClick'));
	this.console.on('change', 'input[type="checkbox"][data-ui="toggle_multi_select"]', $.proxy(this, 'onMultiSelectToggle'));
	$(document).on('click', $.proxy(this, 'onDocumentClick'));
	$(window).on('keydown', $.proxy(this, 'onWinKeyDown'));
	$(window).on('keyup', $.proxy(this, 'onWinKeyUp'));
}


BookingsConsole.prototype.onWinKeyDown = function(evt) {
	if (evt.which == this.KEY_CTRL) {
		this.ctrlPressed = true;
	}
}

BookingsConsole.prototype.onWinKeyUp = function(evt) {
	if (evt.which == this.KEY_CTRL) {
		this.ctrlPressed = false;
	}
}


BookingsConsole.prototype.onMultiSelectToggle = function(evt) {
	console.log($(evt.target));
	if ($(evt.target).is(":checked")) {
		this.selectMode = this.SELECT_MULTI;
	} else {
		this.selectMode = this.SELECT_SINGLE;
	}
}


/**
 * Event listener for clicks outside of the console.
 *
 */
BookingsConsole.prototype.onDocumentClick = function(evt) {

	// if active?
	// $active = this.cardsContainer.find('.active');
	// if ($active.length > 0) {
	// 	if ( ! $.contains($active[0], evt.target)) {
	// 		console.log("click was NOT in active card, hide...");
	// 		this.hideCards();
	// 		return;
	// 	}
	// }

	if ( ! $.contains(this.cardsContainer[0], evt.target)) {
		// Click target was NOT in the cards container - so wasn't in a card.
		// console.log("cards container does not contain click target...");
		this.hideCards();
		return;
	}

	// Element clicked with a data-action to close_card?
	if ($(evt.target).data("action") == 'close_card') {
		console.log("target action is close card");
		this.hideCards();
		return;
	}

}


/**
 * Handle clicking on a booking to show the info card.
 *
 */
BookingsConsole.prototype.onBookingClick = function(evt) {

	var $target = $(evt.currentTarget),
		data = $target.data();

	// prevent document click handler doing anything
	evt.stopImmediatePropagation();

	this.showCard('booking', data.bookingid, $target);
}


/**
 * Handle clicking on a Room to show the info card.
 *
 */
BookingsConsole.prototype.onRoomClick = function(evt) {

	var $target = $(evt.currentTarget),
		data = $target.data();

	// stop the <a> href being navigated to
	evt.preventDefault();
	// prevent document click handler doing anything
	evt.stopImmediatePropagation();

	this.showCard('room', data.roomid, $target);
}


/**
 * Handler for when the underlying checkbox of a free period is clicked.
 * This handles the single/multi select mode and shows the booking UI if appropriate.
 *
 */
BookingsConsole.prototype.onFreeChange = function(evt) {

	var targetId = $(evt.target).attr('id'),
		isMultiSelect = (this.selectMode == this.SELECT_MULTI || this.ctrlPressed);

	if ( ! isMultiSelect) {
		// Not multi - need to uncheck all OTHER items to make sure just this one is selected
		$inputs = this.grid.find('input[type="checkbox"][name="booking[]"][id!="' + targetId + '"]');
		$inputs.removeAttr('checked').prop('checked', false);
	}

	// Toggle the make booking UI in response to selected items
	this.toggleMakeBookingUI();
}


/**
 * Show the booking UI when requested, if appropriate.
 *
 */
BookingsConsole.prototype.toggleMakeBookingUI = function() {

	var $inputs = this.grid.find('input[type="checkbox"][data-ui="free"]:checked'),
		$button = this.console.find('[data-ui="make_booking"]'),
		isMultiSelect = (this.selectMode == this.SELECT_MULTI || this.ctrlPressed);

	// if More than one item selected, just show the "make booking" button
	if ($inputs.length > 1) {
		var buttonText = $button.data('value').replace(/{count}/, $inputs.length);
		$button.text(buttonText);
		$button.removeClass('d-hide');
		// console.log("Book multiple...");
		return;
	}

	// If one item selected and NOT multi-selecting, show card to make booking (with trigger)
	if ($inputs.length == 1 && ! isMultiSelect) {
		$button.addClass('d-hide');
		this.showBookingCard($inputs.first().parent('.grid-item'));
		// @TODO check if the user can make recurring bookings
		/*if (this.canMakeRecurring) {
			$button.removeClass('d-hide');
		} else {
			this.showBookingCard();
		}*/

		return;
	}

	// Nothing selected? Hide cards.
	if ($inputs.length == 0) {
		$button.addClass('d-hide');
		this.hideCards();
	}
}


/**
 * Event handler for the multi-select "Make booking".
 *
 */
BookingsConsole.prototype.onMakeBookingClick = function(evt) {

	evt.preventDefault();
	evt.stopImmediatePropagation();

	$inputs = this.grid.find('input[type="checkbox"][data-ui="free"]:checked');
	if ($inputs.length < 1) {
		return;
	}

	this.showBookingCard();

}


/**
 * Actually show the booking UI card.
 *
 */
BookingsConsole.prototype.showBookingCard = function($trigger) {

	var self = this,
		items = [],
		$inputs = this.grid.find('input[type="checkbox"][data-ui="free"]:checked'),
		$el = this.cardsContainer.find("[data-type='booking_add']"),
		url = SITE_URL + '/bookings/add';

	if ($el || $el.length != 0) {
		$el.remove();
	}

	this.showLoadingCard($trigger);

	$inputs.each(function(idx, item) {

		var data = $(item).data();

		items.push({
			'date': data.date,
			'period_id': data.periodid,
			'room_id': data.roomid,
		});

	});

	if (items.length === 0) {
		return;
	}

	$.post(url, { 'items': items }, function(res) {
		self.hideCards();
		// Add response to the cards container and make it active.
		$el = $(res);
		$el.addClass('active');
		$el.appendTo(self.cardsContainer);
		self.positionCard($el, $trigger);
		new Autocomplete($el.find('[data-ui="users"]'));
		new Autocomplete($el.find('[data-ui="departments"]'));
		new CheckboxRevealer($el.find('[data-field="recurring"]'));
	});
}


BookingsConsole.prototype.showLoadingCard = function($trigger) {
	// console.log("Loading ...");
	this.loadingCard.addClass('active');
	this.positionCard(this.loadingCard, $trigger);
	// this.loadingCard.removeClass('d-hide');
}

BookingsConsole.prototype.hideLoadingCard = function() {
	// console.log("Not loading");
	this.loadingCard.removeClass('active');
}


/**
 * Hide all open cards by removing the `active` class.
 *
 */
BookingsConsole.prototype.hideCards = function() {
	console.log("hiding active cards ");
	$els = this.cardsContainer.find('.active');
	$els.removeClass('active');
}


/**
 * Show the requested card.
 *
 * @param type  Type of card to display - booking or room
 * @param id  ID of entity to show
 * @param $trigger  Element reference that triggered it (for positioning)
 *
 */
BookingsConsole.prototype.showCard = function(type, id, $trigger) {

	var self = this,
		url = false,
		$el = this.cardsContainer.find("[data-type='" + type + "'][data-id='" + id + "']");

	switch (type) {
		case 'booking':
			url = SITE_URL + '/bookings/view/' + id;
		break;
		case 'room':
			url = SITE_URL + '/bookings/room/' + id;
		break;
	}

	if ( ! url) {
		return;
	}

	if ( ! $el || ! $el.length) {

		// No element exists already; load it & show it.

		$.get(url, function(res) {
			// Hide other cards if they're visible
			self.hideCards();
			// Add response to the cards container and make it active.
			$el = $(res);
			$el.addClass('active');
			$el.appendTo(self.cardsContainer);
			// Finally position the card near the trigger element.
			self.positionCard($el, $trigger);
		});

	} else {

		// Element already exists (pre-loaded, or already clicked)

		if ($el.hasClass('active')) {
			// Card is already visible, hide it.
			// console.log('is active!');
			$el.removeClass('active');
			return;
		}

		// Hide other cards, show this one and position it near the trigger element.
		this.hideCards();
		$el.addClass('active');
		self.positionCard($el, $trigger);

	}

}


/**
 * Given a card element $cardEl, position it near the grid element that triggered it ($trigger)
 *
 */
BookingsConsole.prototype.positionCard = function($cardEl, $trigger) {

	// Current positioning is just along horizontal axis.
	// Vertical position is always centred.

	var bodyRect = document.body.getBoundingClientRect(),
		centreX = Math.floor(bodyRect.width / 2),
		centreY = Math.floor(bodyRect.height / 2),
		triggerRect = false;

	if ($trigger) {
		triggerRect = $trigger[0].getBoundingClientRect();

		if (triggerRect.x > centreX) {
			$cardEl.css('right', bodyRect.width - triggerRect.left);
			$cardEl.css('left', 'auto');
		} else {
			$cardEl.css('left', triggerRect.left + triggerRect.width);
			$cardEl.css('right', 'auto');
		}
		$cardEl.css('transform', 'translate(0, -50%)');

	} else {
		$cardEl.css('left', '50%');
		$cardEl.css('right', 'auto');
		$cardEl.css('transform', 'translate(-50%, -50%)');
	}

	// $cardEl.css('top', triggerRect.top + (triggerRect.height/2));
}


/**
 * Pre-load all info cards for static & recurring bookings.
 *
 */
BookingsConsole.prototype.loadBookings = function() {

	var self = this,
		items = this.grid.find('[data-ui="booking"]'),
		ids = [],
		id,
		url = SITE_URL + '/bookings/view_multi',
		$el;

	// Find IDs of all bookings currenty displayed
	ids = $.map(items, function(item) {
		return $(item).data('bookingid');
	});

	if (ids.length === 0) {
		return;
	}

	ids.sort(function(a, b) {
  		return a - b;
  	});

	$.post(url, { booking_ids: ids }, function(res) {
		self.cardsContainer.append(res);
	});

}

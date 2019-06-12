(function AcademicYear(window, document, $) {


	var $document = $(document),
		$yearCalendar,
		weeks = [],
		weekIds,
		weekClassNames = [],
		dates = {};


	function init(event) {
		findElements();
		run();
	}


	$document.ready(init);


	//


	function findElements() {

		$yearCalendar = $("[data-ui='year_calendar']");

		// $yearCalendar.find("[data-ui='calendar_date']").each(function(a, b) {
		// 	var data = $(this).data();
		// 	dates[data.date] = data;
		// });
		// console.log(dates);

	}


	function run() {

		if ( ! $yearCalendar) {
			return;
		}

		weeks = $yearCalendar.data("weeks");
		processWeeks();

		setupHandlers();
	}


	function processWeeks() {
		weekIds = $.map(weeks, function(week) {
			return parseInt(week.week_id, 10);
		});
		weekIds.push(null);

		weekClassNames = $.map(weeks, function(week) {
			return "week-" + week.week_id;
		});
	}


	function setupHandlers() {
		$yearCalendar.on("click", "button[data-ui='calendar_date_btn']", handleDateClick);
	}


	function handleDateClick(e) {

		var data = $(this).parent().data();
		var weekstarts = data.weekstarts,
			date = data.date;

		toggleWeek(data);

		// console.log(data);
		// // All items in this week
		// $items = $("[data-ui='calendar_date'][data-weekstarts='" + weekstarts + "']");
		// $items.addClass("calendar-range");
		// $items.addClass("week-4");
	}


	function toggleWeek(data) {

		// var weekstarts = dates[date].weekstarts;
		var weekstarts = data.weekstarts,
			curWeekId = data.weekid,
			newWeekId,
			classes;

		if (! curWeekId || curWeekId && curWeekId.length == 0) {
			curWeekId = null;
		}

		var newWeekId = weekIds[ ($.inArray(curWeekId, weekIds) + 1 ) % weekIds.length];
		// console.log(curWeekId + " => " + newWeekId);

		$items = $("[data-ui='calendar_date'][data-weekstarts='" + weekstarts + "']");

		for (var i = 0; i < weekClassNames.length; i++) {
			$items.removeClass(weekClassNames[i]);
		}

		if (newWeekId !== null) {
			$items.addClass("week-" + newWeekId);
			$items.addClass("calendar-range");
		} else {
			$items.removeClass("calendar-range");
		}

		$items.attr("data-weekid", newWeekId);
		$items.data("weekid", newWeekId);

		$items.find("input[type=hidden]").val(newWeekId);
	}


	function refreshUI(date) {

		var classNames = '',
			weekstarts = dates[date].weekstarts,
			weekid = dates[date].weekid;

		if (weekid.length) {
			classNames = "calendar-range week-" + weekid;
		}
	}


})(window, document, jQuery);

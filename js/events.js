$(document).ready(function () {
	var CHECK_INTERVAL = 1000, 
		CHECK_URL = '/ajax/mail/events.php', 
		MAX_COUNT = 50, 
		NOTIFICATION_SOUND = 'icq', 
		EVENTS_COUNTER_CLASS = 'horizMenuLinkEvent', 
		EVENTS_COUNTER_SHOW_CLASS = 'horizMenuLinkEventShow';

	$.ionSound({
		sounds: [
			{
				name: NOTIFICATION_SOUND, 
				preload: true
			}
		], 
		path: '/ajax/sounds/', 
		multiplay: false, 
		volume: 1
	});

	var events_options = {
		messages: {
			array_key: 'messages', 
			selector: '#event_messages', 
			link: function (events) {
				return '/' + (events.messages > 0 ? 'new_mess' : 'konts') + '.php';
			}
		}, 
		journal: {
			array_key: 'journal', 
			selector: '#event_journal'
		}, 
		lenta: {
			array_key: 'lenta', 
			selector: '#event_lenta'
		}
	};

	var old_count_all_events = countCurrentAllEvents();

	setCheckTimeout();

	function sayKnockKnock() {
		if (NOTIFS_PLAY_SOUND)
			$.ionSound.play(NOTIFICATION_SOUND);
	}

	function setCheckTimeout() {
		setTimeout(function () {
			$.ajax({
				url: CHECK_URL, 
				success: function (json) {
					updateEvents(json.events);
				}, 
				complete: function () {
					setCheckTimeout();
				}
			});
		}, CHECK_INTERVAL);
	}

	function updateEvents(events) {
		if (events == undefined)
			return;

		for (var key in events_options) {
			var event_options = events_options[key];
			var $event_element = $(event_options.selector), 
				$event_counter_element = $('.' + EVENTS_COUNTER_CLASS, $event_element);

			var count = events[event_options.array_key];
			if (count > 0)
				$event_counter_element.text(count).addClass(EVENTS_COUNTER_SHOW_CLASS);
			else
				$event_counter_element.removeClass(EVENTS_COUNTER_SHOW_CLASS);
		}

		var count_all_events = countAllEvents(events);
		if (count_all_events > old_count_all_events)
			sayKnockKnock();
		old_count_all_events = count_all_events;
	}

	function countAllEvents(events) {
		var count = 0;
		for (var key in events)
			count += parseInt(events[key]);

		return count;
	}

	function countCurrentAllEvents() {
		var count = 0;
		for (var key in events_options) {
			var event_options = events_options[key];

			var $event_element = $(event_options.selector), 
				$event_counter_element = $('.' + EVENTS_COUNTER_CLASS, $event_element);

			count += $event_counter_element.length == 0 ? 0 : parseInt($event_counter_element.text());
		}

		return count;
	}

});
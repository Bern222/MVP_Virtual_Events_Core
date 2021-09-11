const sessionUUID = uuidv4();

function getEventDataAttribute(routeId, buttonObj) {
	var eventDataAttribute = 'data-event-category="' + routeId + '" data-event-action="' + buttonObj.action;
	switch(buttonObj.action) {
		case enumButtonActions.OPEN_ROUTE:
			eventDataAttribute += ' -  ' + buttonObj.actionParams + '"';
		break;
		case enumButtonActions.OPEN_FILE:
			eventDataAttribute += ' - ' + buttonObj.actionParams + '"' ;
		break;
		case enumButtonActions.OPEN_EXTERNAL_LINK:
			eventDataAttribute += ' - ' + buttonObj.actionParams.url + '"' ;	
		break;
		case enumButtonActions.OPEN_MODAL_INLINE:
			eventDataAttribute += ' - ' + buttonObj.actionParams + '"' ;
		break;
		case enumButtonActions.OPEN_MODAL_VIDEO:
			eventDataAttribute += ' - ' + buttonObj.actionParams + '"' ;
		break;
		case enumButtonActions.OPEN_MODAL_IFRAME:
			eventDataAttribute += ' - ' + buttonObj.actionParams + '"' ;
		break;
		case enumButtonActions.LOGIN:
			// TODO: No action params
		break;
		case enumButtonActions.LOGOUT:
			// No action params
		break;
		default:
			eventDataAttribute += '"';
			break;
	}
	return eventDataAttribute;
}

function initEventListeners() {
	$('.event-tracked').on('click', function(event) {
		// Get the element event and send to logEvent
		if (enableEventLogging && event.currentTarget && event.currentTarget.dataset) {
			if (event.currentTarget.dataset.eventCategory && event.currentTarget.dataset.eventAction) {
				logEvent(event.currentTarget.dataset.eventCategory, event.currentTarget.dataset.eventAction);
			}
			// event.target.dataset
		}
	});
}

function logEvent(category, action, value = currentUser.id) {
	
	var userId = value;
	if (!value) {
		userId = 1;
		value = remoteIP + ' - ' + sessionUUID;
	}

	// console.log('LOG EVENT: ', category, action, value);

	gtag('event', category, { 'event_category': category, 'event_action': action, 'value': value});

	// Run MVP Event Logger
	request = $.post("../core/eventLogger.php", {
		method: "eventLogger", 
		event_category: category,
		currentUserId:  userId, 
		event_action: action,
		event_label: value
	});
	request.done(function (response, textStatus, jqXHR) {
	});
}

function determineCategory(optionalRoute) {
    var eventRoute = currentRoute;
    if (optionalRoute != '') {
        eventRoute = optionalRoute;
    }
    return eventRoute;
}

function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}
function buildMainMenu() {
	try {
		var clickAction;
		var eventAttribute;
		if (configMainMenu.logo) {
			clickAction = getClickAction(configMainMenu.logo);
			eventAttribute = getEventDataAttribute(enumRoutes.MAIN_MENU, configMainMenu.logo);
			
			$('.main-menu-routes').append(
				'<div onclick="' + clickAction + '"><img class="menu-logo" ' + eventAttribute + ' src="' + configMainMenu.logo.imagePath + '"/></div>'
			)
		}

		for(var i=0; i<configMainMenu.menuItems.length; i++) {
			clickAction = getClickAction(configMainMenu.menuItems[i]);
			eventAttribute = getEventDataAttribute(enumRoutes.MAIN_MENU, configMainMenu.menuItems[i]);

			$('.main-menu-routes').append('<div class="menu-item"  ' + eventAttribute + ' onclick="' + clickAction + '">' + configMainMenu.menuItems[i].displayText + '</div>');
		}
	} catch (error) {
		console.error('Build Main Menu Error - ', error);
	}
}

function buildRoute(routeObj) {
	try {
		
		console.log('RouteOBJ:', routeObj.id);

		$('#routeContainer').append('<div id="' + routeObj.id + '" class="page-fullscreen">');
		$('#' + routeObj.id).append('<div id="" class="relative-container">');


		// TODO check if video or image background
		// TODO enable portrait for isMobile portrait
		$('#' + routeObj.id + ' .relative-container').append('<img class="full-background" src="' + routeObj.backgroundLandscape + '"/>')

		// Loop though buttons, TODO need to add portrait support
		if (routeObj.buttons && routeObj.buttons.landscape && routeObj.buttons.landscape.length > 0) {
			
			// Create the absolute button container
			$('#' + routeObj.id + ' .relative-container').append('<div id="' + routeObj.id + 'AbsoluteContainer" class="absolute-button-container">');

			// Loop though the button objects
			for (var i=0; i < routeObj.buttons.landscape.length; i++) {

				const buttonId = routeObj.id + 'Button' + i;
				const buttonObj = routeObj.buttons.landscape[i];
				const clickAction = getClickAction(buttonObj);
				const eventAttribute = getEventDataAttribute(routeObj.id, buttonObj);
				
				// Add the button div
				$('#' + routeObj.id + 'AbsoluteContainer').append('<div id="' + buttonId + '" ' + eventAttribute + ' class="absolute-button" onclick="' + clickAction + '"></div>');
				
				// Check config classes
				if (buttonObj.classes && buttonObj.classes.length > 0) {
					for (var k=0; k < buttonObj.classes.length; k++) {
						$('#' + buttonId).addClass(buttonObj.classes[k]);
					}
				}

				// Add override CSS
				if (buttonObj.overrideCss) {
					$('#' + buttonId).css(buttonObj.overrideCss);
				}
			}

			// Close the button container
			$('#' + routeObj.id + ' .relative-container').append('</div>');
		}
	} catch (error) {
		console.error('Build Route Error - ', routeObj.id, ' - ', error);
	}


	// Check for page buttons (TODO: seems redundant with above, look at combining these)
	if (routeObj.buttons && routeObj.buttons.pageButtons && routeObj.buttons.pageButtons.length > 0) {
		$('#' + routeObj.id).append('<div class="page-button-container"></div>');


		// Loop though page button objects
		for (var i=0; i < routeObj.buttons.pageButtons.length; i++) {
			
			const buttonId = routeObj.id + 'PageButton' + i;
			const buttonObj = routeObj.buttons.pageButtons[i];
			const clickAction = getClickAction(buttonObj);
			const eventDataAttribute = getEventDataAttribute(routeObj.id, buttonObj);
			
			// Add the button div
			$('#' + routeObj.id + ' .page-button-container').append('<div id="' + buttonId + '" ' + eventDataAttribute + ' class="page-black-button" onclick="' + clickAction + '">' + buttonObj.buttonText + '</div>');
			
			// Check config classes
			if (buttonObj.classes && buttonObj.classes.length > 0) {
				for (var k=0; k < buttonObj.classes.length; k++) {
					$('#' + buttonId).addClass(buttonObj.classes[k]);
				}
			}

			// Add override CSS
			if (buttonObj.overrideCss) {
				$('#' + buttonId).css(buttonObj.overrideCss);
			}
		}
	}


	
	// Close the Route / Relative divs
	$('#routeContainer').append('</div></div>')

}

function buildModal(modalObj) {

}

function getClickAction(buttonObj) {
	var clickAction = '';
	switch(buttonObj.action) {
		case enumButtonActions.OPEN_ROUTE:
			clickAction = 'changeRoute(\'' + buttonObj.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_PDF:
			clickAction = 'window.open(\'' + buttonObj.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_EXTERNAL_LINK:
			var linkName = '_blank';
			if (buttonObj.actionParams && buttonObj.actionParams.name) {
				if (buttonObj.actionParams.name) {
					linkName = buttonObj.actionParams.name;
				}
				clickAction = 'window.open(\'' + buttonObj.actionParams.url + '\', \'' + linkName + '\')';
			}
		break;
		case enumButtonActions.OPEN_MODAL_INLINE:
			clickAction = 'openModalInline(\'' + buttonObj.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_MODAL_VIDEO:
			clickAction = 'openModalVideo(\'' + buttonObj.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_MODAL_IFRAME:
			clickAction = 'openModalIframe(\'' + buttonObj.actionParams + '\')';
		break;
	}
	return clickAction;
}

function getEventDataAttribute(routeId, buttonObj) {
	
	var eventDataAttribute = 'data-event-category="' + routeId + '" data-event-action="' + buttonObj.action;
	switch(buttonObj.action) {
		case enumButtonActions.OPEN_ROUTE:
			eventDataAttribute += '"';
		break;
		case enumButtonActions.OPEN_PDF:
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
	}
	return eventDataAttribute;
}

function logEvent(category, action) {
	console.log('LOG EVENT: ', category, action, 'TODO');
	// gtag('event', category, { 'event_category': category, 'event_action': action, 'value': currentUser.id});

	//Run MVP Event Logger
	// request = $.post("eventLogger.php", {
	// 	method: "eventLogger", 
	// 	event_category: category,
	// 	currentUserId:  currentUser.id, 
	// 	event_action: action,
	// 	event_label: parseInt(currentUser.id)
	// });
	// request.done(function (response, textStatus, jqXHR) {
	// });
}


function openProfile() {
	if(currentUser && currentUser.show_notifications) {
		$("#showNotificationCheckbox").prop('checked', true);
	} else {
		$("#showNotificationCheckbox").prop('checked', false);
	}

	setTimeout(function() {
		$.fancybox.open({
			src  : '#profileModal',
			type : 'inline',
			animationEffect: "zoom",
			animationDuration: modalFadeTime,
			opts : {
				beforeClose : function( instance, current ) {
					// toggleUpdatePassword();
					$('#pwem').val("");
					$('#pwemrepeat').val("")
				},
				touch: false
			}
		});
	}, 100);
}

function openShare() {
	setTimeout(function() {
		$.fancybox.open({
			src  : '#shareModal',
			type : 'inline',
			animationEffect: "zoom",
			animationDuration: modalFadeTime,
			opts : {
				touch: false
			}
		});
	}, 100);
}

function logout() {
	request = $.post("modules/loginproc.php", { method: "processLogout" });

	request.done(function (response, textStatus, jqXHR) {
		window.location.replace('index.php');
	});
}

function flyIntoLobby() {
	$('.exterior-fly-in-video-player-container').fadeTo(1, 1);

	$('#exteriorFlyInVideoPlayer').get(0).load();
	$('#exteriorFlyInVideoPlayer').get(0).play();
	setTimeout(function() { 
		fadeIn('lobby');
		$('.exterior-fly-in-video-player-container').fadeTo(1, 0);
	},4000);
}
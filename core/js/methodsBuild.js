function buildMainMenu() {
	try {
		var clickAction;
		if (configMainMenu.logo) {
			clickAction = getClickAction(configMainMenu.logo);
			
			$('.main-menu-routes').append(
				'<div onclick="' + clickAction + '"><img class="menu-logo" src="' + configMainMenu.logo.imagePath + '"/></div>'
			)
		}

		for(var i=0; i<configMainMenu.menuItems.length; i++) {
			clickAction = getClickAction(configMainMenu.menuItems[i]);

			$('.main-menu-routes').append('<div class="menu-item" onclick="' + clickAction + '">' + configMainMenu.menuItems[i].displayText + '</div>');
		}
	} catch (error) {
		console.error('Build Main Menu Error - ', error);
	}
}

function buildRoute(routeObj) {
	try {
		
		console.log('RouteOBJ:', routeObj.id);

		// TODO check if video or image background
		// TODO enable portrait for isMobile portrait
		$('#' + routeObj.id + ' .relative-container').append('<img class="full-background" src="' + routeObj.backgroundLandscape + '"/>')

		// Loop though buttons, TODO need to add portrait support
		if (routeObj.buttons && routeObj.buttons.landscape && routeObj.buttons.landscape.length > 0){
			
			// Create the absolute button container
			$('#' + routeObj.id + ' .relative-container').append('<div id="' + routeObj.id + 'AbsoluteContainer" class="absolute-button-container">');

			// Loop thought the button objects
			for (var i=0; i < routeObj.buttons.landscape.length; i++) {

				const clickAction = getClickAction(configMainMenu.menuItems[i]);
				const buttonId = routeObj.id + 'Button' + i;
				const buttonObj = routeObj.buttons.landscape[i];
				
				// Add the button div
				$('#' + routeObj.id + 'AbsoluteContainer').append('<div id="' + buttonId + '" class="absolute-button" onclick="' + clickAction + '"></div>');
				
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
}

function getClickAction(menuItem) {
	var clickAction = '';
	switch(menuItem.action) {
		case enumButtonActions.OPEN_ROUTE:
			clickAction = 'changeRoute(\'' + menuItem.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_PDF:
			clickAction = 'window.open(\'' + menuItem.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_EXTERNAL_LINK:
			if (!menuItem.actionParams.name) {
				menuItem.actionParams.name = '_blank';
			}

			clickAction = 'window.open(\'' + menuItem.actionParams.url + '\', \'' + menuItem.actionParams.name + '\')';
		break;
		case enumButtonActions.OPEN_MODAL_INLINE:
			clickAction = 'openModalInline(\'' + menuItem.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_MODAL_VIDEO:
			clickAction = 'openModalVideo(\'' + menuItem.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_MODAL_IFRAME:
			clickAction = 'openModalIframe(\'' + menuItem.actionParams + '\')';
		break;
	}
	return clickAction;
}

function logEvent(category, action) {
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
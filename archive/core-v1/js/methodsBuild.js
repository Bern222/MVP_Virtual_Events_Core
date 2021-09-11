function buildMainMenu() {
	try {
		// console.log('MM Config', configMainMenu);

		if (configMainMenu) {
			
			addStyles(configMainMenu, 'header');

			// Add override CSS
			if (configMainMenu.overrideCss) {
				$('#header').css(configMainMenu.overrideCss);
			}

			var clickAction;
			var eventAttribute;
			var currentItem;

			// RIGHT MENU
			if (configMainMenu.rightMenu && configMainMenu.rightMenu.menuItems && configMainMenu.rightMenu.menuItems.length > 0) {
				$('#header').append('<div id="rightMenuContainer" class="right-menu-container"></div>');

				if (configMainMenu.divider) {
					$('#rightMenuContainer').append('<div style="color: ' + configMainMenu.divider.color + '"> | </div>');
				}

				for (var i=0; i < configMainMenu.rightMenu.menuItems.length; i++) {
					currentItem = configMainMenu.rightMenu.menuItems[i];
					itemId = 'rightMenuButton' + i;
					clickAction = getClickAction(currentItem);
					eventAttribute = getEventDataAttribute(enumRoutes.MAIN_MENU, currentItem);
					
					$('#rightMenuContainer').append('<div id="' + itemId + '" class="right-menu-item event-tracked" ' + eventAttribute + ' onclick="' + clickAction + '">' + currentItem.displayText + '</div>');

					addStyles(currentItem, itemId);
				}
			}

			// Mobile Dropdown Menu Button
			if (configMainMenu.enableMobileMenu) {
				$('#header').append('<input class="menu-btn" type="checkbox" id="menu-btn" /><label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>');
			}

			// Main Menu Logo
			if (configMainMenu.logo) {
				clickAction = getClickAction(configMainMenu.logo);
				eventAttribute = getEventDataAttribute(enumRoutes.MAIN_MENU, configMainMenu.logo);
				
				$('#header').append('<div onclick="' + clickAction + '"><img class="header-menu-logo event-tracked" ' + eventAttribute + ' src="' + configMainMenu.logo.imagePath + '"/></div>');
			}

			$('#header').append('<ul id="headerMenu" class="header-menu"></ul>');

			for (var i=0; i<configMainMenu.menuItems.length; i++) {
				currentItem = configMainMenu.menuItems[i];
				clickAction = getClickAction(currentItem);
				eventAttribute = getEventDataAttribute(enumRoutes.MAIN_MENU, currentItem);

				var menuItemId = '';
				if (currentItem.action == enumButtonActions.OPEN_ROUTE) {
					menuItemId = 'id="headerMenuButton' + currentItem.actionParams + '"';
				}


				$('#headerMenu').append('<li><div ' + menuItemId + '" class="header-menu-item event-tracked"  ' + eventAttribute + ' onclick="' + clickAction + '" tabindex="1" role="menuitem">' + currentItem.displayText + '</div></li>');
			}

			// TODO - NEED TO FIX AND REWORK WITH RIGHT MENU ABOVE
			if (configMainMenu.rightMenu && configMainMenu.rightMenu.profileMenu && configMainMenu.rightMenu.profileMenu.length > 0 && currentUser && currentUser.chat_username) {
				$('#header').append('<div class="user-profile-container">');
				$('#header').append('	<div class="user-profile">');
				$('#header').append('		<div class="header-menu-item">' + currentUser.chat_username + '</div>');
				$('#header').append('		<div class="profile-container">');
				$('#header').append('			<div class="subnav" style="display: block;">');
				$('#header').append('			<div class="arrow" style="display: block;"></div>');
				$('#header').append('				<ul>');

				for(var i=0; i<configMainMenu.rightMenu.profileMenu.menuItems.length; i++) {
					currentItem = configMainMenu.rightMenu.profileMenu.menuItems[i];
					clickAction = getClickAction(currentItem);
					eventAttribute = getEventDataAttribute(enumRoutes.PROFILE, currentItem);
					$('#header').append('<li><div class="subnav-button event-tracked" ' + eventAttribute + ' onclick="' + clickAction + '">' + currentItem.displayText + '</div></li>');

					addStyles(currentItem, itemId);
				}

				$('#header').append('</ul></div"></div></div>');
			}
		} 
	} catch (error) {
		console.error('Build Main Menu Error - ', error);
	}
}

function buildRoute(routeObj) {
	try {	
		// console.log('RouteOBJ:', routeObj);

		$('#routeContainer').append('<div id="' + routeObj.id + '" class="route-fullscreen">');
		$('#' + routeObj.id).append('<div id="" class="route-relative-container">');

		// TODO Add portrait support / check
		if (routeObj.backgroundVideoLandscape && routeObj.backgroundVideoLandscape != '') {
			// Add Video background 

			var videoAttributes = '';
			if(!routeObj.disableBackgroundVideoAutoplay) {
				videoAttributes = 'autoplay muted';
			}

			if (!routeObj.disableBackgroundVideoLoop) {
				videoAttributes += ' loop';
			}

			$('#' + routeObj.id + ' .route-relative-container').append('<div class="full-background-landscape">')
			$('#' + routeObj.id + ' .route-relative-container').append('	<video id="' + routeObj.id + 'BackgroundVideo" ' + videoAttributes + ' playsinline onended="backgroundVideoOnEndedCallback(\'' + routeObj.id + '\')"><source src="' + routeObj.backgroundVideoLandscape + '" type="video/mp4" />Your browser does not support the video tag.</video>')

			$('#' + routeObj.id + ' .route-relative-container').append('</div>')

		} else {
			// Add Image Background
			$('#' + routeObj.id + ' .route-relative-container').append('<img class="full-background-landscape" src="' + routeObj.backgroundLandscape + '"/>')
		}

		// Loop though buttons, TODO Add portrait support / check
		if (routeObj.buttons && routeObj.buttons.landscape && routeObj.buttons.landscape.length > 0) {
			
			// Create the absolute button container
			$('#' + routeObj.id + ' .route-relative-container').append('<div id="' + routeObj.id + 'AbsoluteContainer" class="absolute-button-container">');

			// Loop though the button objects
			for (var i=0; i < routeObj.buttons.landscape.length; i++) {

				const buttonId = routeObj.id + 'Button' + i;
				const buttonObj = routeObj.buttons.landscape[i];
				const clickAction = getClickAction(buttonObj);
				const eventAttribute = getEventDataAttribute(routeObj.id, buttonObj);
				const iconHtml = getButtonIcon(buttonObj);
				// Add the button div
				$('#' + routeObj.id + 'AbsoluteContainer').append('<div id="' + buttonId + '" class="absolute-button event-tracked" ' + eventAttribute + ' onclick="' + clickAction + '">' + iconHtml + '</div>');
				
				addStyles(buttonObj, buttonId);
			}

			// Close the button container
			$('#' + routeObj.id + ' .route-relative-container').append('</div>');
		}
	
		// Check for page buttons (TODO: seems redundant with above, look at combining these)
		if (routeObj.buttons && routeObj.buttons.pageButtons && routeObj.buttons.pageButtons.length > 0) {
			$('#' + routeObj.id).append('<div class="route-button-container"></div>');

			// Loop though page button objects
			for (var i=0; i < routeObj.buttons.pageButtons.length; i++) {
				
				const buttonId = routeObj.id + 'PageButton' + i;
				const buttonObj = routeObj.buttons.pageButtons[i];
				const clickAction = getClickAction(buttonObj);
				const eventDataAttribute = getEventDataAttribute(routeObj.id, buttonObj);
				
				// Add the button div
				$('#' + routeObj.id + ' .route-button-container').append('<div id="' + buttonId + '" ' + eventDataAttribute + ' class="route-side-button" onclick="' + clickAction + '">' + buttonObj.buttonText + '</div>');
				
				addStyles(buttonObj, buttonId);
			}
		}
		
		// Close the Route / Relative divs
		$('#routeContainer').append('</div></div>');

	} catch (error) {
		console.error('Build Route Error - ', routeObj.id, ' - ', error);
	}

}

function buildModal(modalObj) {
	try {
		$('#modalContainer').append('<div id="' + modalObj.id + '" class="default-modal">');
		
		if (modalObj.type != enumModalTypes.HTML) {
			$('#' + modalObj.id).append('<div class="modal-list-buttons"></div>');

			for (var i=0; i < modalObj.buttons.length; i++) {
				const buttonId = modalObj.id + 'Button' + i;
				const buttonObj = modalObj.buttons[i];
				const clickAction = getClickAction(buttonObj);
				const eventAttribute = getEventDataAttribute(modalObj.id, buttonObj);

				switch(modalObj.type) {
					case enumModalTypes.THUMB_LIST:					
						$('#' + modalObj.id + ' .modal-list-buttons').append('<div id="' + buttonId + '" class="modal-thumb-list-item event-tracked" ' + eventAttribute + ' onclick="' + clickAction + '"></div>');
						$('#' + buttonId).append('<div class="modal-thumb-list-image">');
						$('#' + buttonId).append('<div><div class="modal-header-text"><div class="modal-description-text"></div></div>');					
						break;
					case enumModalTypes.SIMPLE_LIST:
						$('#' + modalObj.id + ' .modal-list-buttons').append('<div id="' + buttonId + '" class="modal-simple-list-item event-tracked" ' + eventAttribute + ' onclick="' + clickAction + '">' + buttonObj.buttonText + '</div>');
						break;
				}
			}
		} else {
			// HTML Modal
			$('#' + modalObj.id).append('<div class="modal-html-content"></div>');
		}
	} catch (error) {
		console.error('Build Modal Error - ', modalObj.id, ' - ', error);
	}
}

function addStyles(elementObj, elementId) {
	// Check config classes
	if (elementObj.classes && elementObj.classes.length > 0) {
		for (var k=0; k < elementObj.classes.length; k++) {
			$('#' + elementId).addClass(elementObj.classes[k]);
		}
	}

	// Add override CSS
	if (elementObj.overrideCss) {
		$('#' + elementId).css(elementObj.overrideCss);
	}
}

function getButtonIcon(buttonObj) {
	var iconHtml = '';
	if (buttonObj && buttonObj.icon) {
		switch(buttonObj.icon){
			case 'icon-arrow':
				iconHtml = '<div class="blob-container"><div class="blob blue"><i class="fa fa-mouse-pointer"></i></div></div>';
			break;
		}
	}

	return iconHtml;
}

$(document).ready(function () {
	console.log('CONFIGS: ', configRoutes, configModals, configMainMenu, configSiteSettings, dataContent);
	if (configSiteSettings.enableLandscapeLock) {
    	setupDeviceOrientation();
	}

  	// UPDATE SESSION: Update session call based on user interaction
	var doUpdateSession = false;
	window.addEventListener('click', function(event) {
		doUpdateSession = true;
	}, true);

	setInterval( function() {
		if (doUpdateSession) {
			doUpdateSession = false;
			updateSession();
		}
	}, configSiteSettings.updateSessionInterval);



	// CLOSE WINDOW WARNING: add listener for closing window
    if (configSiteSettings.enableCloseWindowWarning) {
	    window.addEventListener('beforeunload', beforeUnloadHandler, true);
    }

    // Force Refresh
    if (configSiteSettings.enableForceRefresh) {
        checkVersion();

        setInterval( function() {		
            checkVersion();
        }, configSiteSettings.forceRefreshInterval);
    }

	// Build Main Menu from local config
	buildMainMenu();


	// Build Routes from core-config
	for (var i=0;i<configRoutes.length;i++) {
		buildRoute(configRoutes[i]);
	}

	// Build Modals from core-config
	for (var i=0;i<configModals.length;i++) {
		buildModal(configModals[i]);
	}

	// Add event listener to .event-tracked class
	// initEventListeners();

	currentRoute = configSiteSettings.startingRoute;
	currentRouteIndex = configSiteSettings.startingRouteIndex;
	if (currentRoute) {
		changeRoute(currentRoute);
	} else {
		console.log('Starting Route not defined.')
	}
});


// TODO move all below to Utility Method JS
function refreshPage() {
	$.fancybox.close();
	window.removeEventListener('beforeunload', beforeUnloadHandler, true);
	var url = '//' + location.host + location.pathname + '?room=' + currentRoute; 
	window.location.href = url;
}	

function beforeUnloadHandler(event) {
	event.preventDefault();
	event.returnValue = '';
	alert(configSiteSettings.closeWindowMessage);
}

function checkVersion() {
	request = $.post("commonRest.php", {method: 'checkVersion'});
	request.done(function (response, textStatus, jqXHR) {
		// TODO: Revisit
		// if (response) {
		// 	var serverVersion = JSON.parse(response);
		// 	if(serverVersion && serverVersion.length > 0 && serverVersion[0].value && clientVersion < serverVersion[0].value) {
		// 		$.fancybox.open({
		// 			src  : '#refreshModal',
		// 			type : 'inline',
		// 			animationDuration: modalFadeTime,
		// 			opts : {
		// 				touch: false
		// 			}
		// 		});
		// 	}
		// }
	});
}

function updateSession() {
	request = $.post("commonRest.php", {method: 'updateSession'});
	request.done(function (response, textStatus, jqXHR) {
		if (response) {

		}
	});
}
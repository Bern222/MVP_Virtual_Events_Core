$(document).ready(function () {
	console.log('CONFIGS: ', configRoutes, configModals, configMainMenu, configSiteSettings, dataContent);

	// CORE METHODS - Setup -------------------------------------------------------
	// Core site setting and build methods 

	// TODO: fix, Enables portrait lockout screen and messaging
	$('#portraitBlock').hide();

	if (configSiteSettings.enableLandscapeLock) {
    	// setupDeviceOrientation();
	}

	// Set Site Title
	if (configSiteSettings.title && configSiteSettings.title != '') {
		document.title = configSiteSettings.title;
	} else {
		document.title = '';
	}

	// Google Analytics
	if (configSiteSettings.keyGoogleAnalytics && configSiteSettings.keyGoogleAnalytics != '') {
		setupGoogleAnalytics();
	}

	// Update session call based on user interaction
	setupUpdateSession();

	// Add listener for before closing window
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

	// Handles the hover states of route elements
	var sourceSwap = function () {
		var $this = $(this);
		var newSource = $this.data('alt-src');
		$this.data('alt-src', $this.attr('src'));
		$this.attr('src', newSource);
	}
	
	$(function() {
		$('img[data-alt-src]').each(function() { 
			new Image().src = $(this).data('alt-src'); 
		}).hover(sourceSwap, sourceSwap); 
	});



	// Adding Custom Code
	createCustomAgenda();

	var day = new Date().getDate() 
	
	if (day == 8) {
		toggleAgenda('8');
	} else {
		toggleAgenda('9');
	}

	// TODO: revisit why this doesn't always work
	// Add event listener to .event-tracked class
	// initEventListeners();

	// Setup current route from config and navigate to the first page
	console.log('HASH NAVIGATION', configSiteSettings.enableHashNavigation);
    if (configSiteSettings.enableHashNavigation && window.location.hash && window.location.hash.length > 1) {
		
        var route = window.location.hash.substring(1);   
        console.log('HASH NAV: ', window.location.hash), route;
		
        currentRoute = route;
        currentRouteIndex = configRoutes.findIndex(x => x.id === route);
		window.onhashchange = locationHashChanged;

        changeRoute(route);
    } else {
		currentRoute = configSiteSettings.startingRoute;
		currentRouteIndex = configSiteSettings.startingRouteIndex;

		if (configSiteSettings.enableHashNavigation) {
			window.location.hash = currentRoute;
			window.onhashchange = locationHashChanged;
		}

		if (currentRoute) {
			changeRoute(currentRoute);
		} else {
			console.log('Starting Route not defined.')
		}
	}


	function locationHashChanged() {  
		var route = window.location.hash.substring(1);
		console.log('Location:', route);

		if (route != currentRoute) {
			changeRoute(route);
		}
		// if (location.hash === "#somecoolfeature") {  
		//   somecoolfeature();  
		// }  
	  }  

	


	// Local Methods -------------------------------------------
	// Local and Override methods



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

// Setups
function setupUpdateSession() {
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
}

function setupGoogleAnalytics() {
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', configSiteSettings.keyGoogleAnalytics);
}
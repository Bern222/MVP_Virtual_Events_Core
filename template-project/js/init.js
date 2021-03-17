$(document).ready(function () {
	
    // TODO revisit these
	// closeChatWindow();
	//document.onkeydown = KeyPress;  //  for bypass
	
    setupLandscapeDetection();
    setupChatWindowDrag();


	// TODO Move to component
    $('.user-profile').click(function() {
		$('.arrow').slideToggle();
		$('.subnav').slideToggle();

	});


  	// Update session call based on user interaction
	window.addEventListener('click', function() {
		doUpdateSession = true;
	}, true);

	setInterval( function() {
		if (doUpdateSession) {
			doUpdateSession = false;
			updateSession();
		}
	}, updateSessionInterval);

	// Close window message
    if (enableCloseWindowWarning) {
	    window.addEventListener('beforeunload', beforeUnloadHandler, true);
    }

    // Force Refresh
    if (enableForceRefresh) {
        checkVersion();

        setInterval( function() {		
            checkVersion();
        }, forceRefreshInterval);
    }

	// Build Main Menu from local config
	buildMainMenu();


	// Build Routes from local config
	for (var i=0;i<configRoutes.length;i++) {
		console.log('ROUTE:', configRoutes[i].id, configRoutes);
		buildRoute(configRoutes[i]);
	}

    // currentRoute definied in global vars
    changeRoute(currentRoute);
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
	alert('Are you sure you want to close the Virtual Conference');
}

function checkVersion() {
	request = $.post("commonRest.php", {method: 'checkVersion'});
	request.done(function (response, textStatus, jqXHR) {
		if (response) {
			var serverVersion = JSON.parse(response);
			if(serverVersion && serverVersion.length > 0 && serverVersion[0].value && clientVersion < serverVersion[0].value) {
				$.fancybox.open({
					src  : '#refreshModal',
					type : 'inline',
					animationEffect: "zoom",
					animationDuration: modalFadeTime,
					opts : {
						touch: false
					}
				});
			}
		}
	});
}

function updateSession() {
	request = $.post("commonRest.php", {method: 'updateSession'});
	request.done(function (response, textStatus, jqXHR) {
		if (response) {

		}
	});
}
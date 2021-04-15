$(document).ready(function () {
	$('.navigation-container').hide();

    setupDeviceOrientation();

	// TODO Move to component
    $('.user-profile').click(function() {
		$('.arrow').slideToggle();
		$('.subnav').slideToggle();

	});

  	// Update session call based on user interaction
	window.addEventListener('click', function(event) {
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
		buildRoute(configRoutes[i]);
	}

	// Build Modals from local config
	for (var i=0;i<configModals.length;i++) {
		console.log('MODAL:', configModals[i].id, configModals[i]);
		buildModal(configModals[i]);
	}

	// Add event listener to .event-tracked class
	initEventListeners();

	$('.audio-play').click(function() {
		var flyInVideo = document.getElementById(enumRoutes.TRANSITION_FLY_IN + 'BackgroundVideo');
		flyInVideo.play();
		// var audioElement = document.getElementById('audioPlayer');
        // audioElement.play();
    });

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
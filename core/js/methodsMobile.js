
// TODO Revisit
function setupDeviceOrientation() {
    
    $('#portraitBlock').hide();

    if( $('body').css('display')=='none') {
        isMobile = true;       
    }

    isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

    if (isMobile) {
        window.addEventListener('orientationchange', doOnOrientationChange);
        if (enableMobileLandscapeLock) {
            $('#portraitBlock').text(configSiteSettings.landscapeLockMessage);
        }
        doOnOrientationChange();
    }
}

function doOnOrientationChange() {
	switch(window.orientation) {  
        case -90: case 90:
            // Landscape
            $('#portraitBlock').hide();
            break; 
        default:
            // Portrait
            // $('#portraitBlock').hide();
            $('#portraitBlock').show();
            
            //alert('Best viewed horizontally.  Please rotate your device.');
            break; 
    }
}
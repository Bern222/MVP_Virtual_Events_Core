

function setupLandscapeDetection() {
    if( $('body').css('display')=='none') {
        isMobile = true;       
    }

    isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

    if (isMobile == true) {
        window.addEventListener('orientationchange', doOnOrientationChange);
        doOnOrientationChange();
    }
}

function doOnOrientationChange() {
	switch(window.orientation) {  
	case -90: case 90:
		//alert('landscape');
		break; 
	default:
		//alert('portrait');
		//alert('Best viewed horizontally');
		alert('Best viewed horizontally.  Please rotate your device.');
		break; 
    }
}
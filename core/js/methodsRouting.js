$(document).ready(function () {
    if(enableHashNavigation && window.location.hash && window.location.hash.length > 1) {
        // changeRoute(window.location.hash);
    }
});


function changeRoute(route) {
    if (enableHashNavigation) {
        window.location.hash = route;
    }    
    const routeIndex = configRoutes.findIndex(x => x.id === route);

    // if (routeIndex != currentRouteIndex) {
        currentRouteIndex = routeIndex;

        prepareRoute(route);
        localRouteMethods(route);

        // May need to be in a callback depending on local route methods
        // Created transition to support other methods than fade
        $('#' + route).show();
        switch(configRoutes[currentRouteIndex].transition) {
            case 'none':
                $('#' + route).fadeTo(0, 1);
                break;
            default:
                $('#' + route).fadeTo(routeFadeTime, 1);
                break;
        }    
    // }
}

function nextRoute() {
    if (currentRouteIndex >= getMinMaxNavigation()[1]) {
        if (loopRoutes) {
            currentRouteIndex = getMinMaxNavigation()[0];
        }
    } else {
        currentRouteIndex++;
    }
    logEvent('nextRoute', enumButtonActions.OPEN_ROUTE + ' - ' + configRoutes[currentRouteIndex].id);
    changeRoute(configRoutes[currentRouteIndex].id);
}

function previousRoute() {
    if (currentRouteIndex <= getMinMaxNavigation()[0]) {
        if (loopRoutes) {
            currentRouteIndex = getMinMaxNavigation()[1];
        }
    } else {
        currentRouteIndex--;
    }
    logEvent('previousRoute', enumButtonActions.OPEN_ROUTE + ' - ' + configRoutes[currentRouteIndex].id);
    changeRoute(configRoutes[currentRouteIndex].id);
}

// Checks Global
function getMinMaxNavigation() {
    var minIndex = 0;
    var maxIndex = configRoutes.length - 1;

    if (minNavigationIndex) {
        minIndex = minNavigationIndex;
    }
    
    if (maxNavigationIndex) {
        maxIndex = maxNavigationIndex;
    }

    return [minIndex, maxIndex];
}

// TODO Revisit this
function prepareRoute(route) {
    // Video resets
    $('.live-video').attr('src', '');
    $.fancybox.close();

    // closeChatWidnow();
    $('#' + currentRoute).fadeTo(0, 0);
	$('#' + currentRoute).hide();
    currentRoute = route;
    console.log('PREPARE SHOW: ', route);
    $('#' + route).show();

    // TODO: Determine if this is CORE
    $('.header li div').removeClass('header-active');
    $('#headerMenuButton' + route).addClass('header-active');

}
$(document).ready(function () {
    if(configSiteSettings.enableHashNavigation && window.location.hash && window.location.hash.length > 1) {
        // changeRoute(window.location.hash);
    }
});


function changeRoute(route) {
    console.log('ROUTE:', route, currentRoute);
    if (configSiteSettings.enableHashNavigation) {
        window.location.hash = route;
    }    
    logEvent(route, enumButtonActions.OPEN_ROUTE);
    const routeIndex = configRoutes.findIndex(x => x.id === route);

    // if (routeIndex != currentRouteIndex) {
        currentRouteIndex = routeIndex;

        prepareRoute(route);
        localRouteMethods(route);
        

        // May need to be in a callback depending on local route methods
        // Created transition to support other methods than fade
        $('#' + route).show();
        if (configRoutes[currentRouteIndex]) {
            switch(configRoutes[currentRouteIndex].transition) {
                case 'none':
                    $('#' + route).fadeTo(0, 1);
                    break;
                default:
                    $('#' + route).fadeTo(configSiteSettings.routeFadeTime, 1);
                    break;
            }    
        } else {
            $('#' + route).fadeTo(configSiteSettings.routeFadeTime, 1);
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
    if (route != currentRoute) {
        if (configRoutes[currentRouteIndex] && configRoutes[currentRouteIndex].transition == 'none') {
            $('#' + currentRoute).fadeTo(0, 1);
            $('#' + route).show();
            var timeoutRoute = currentRoute;
            setTimeout(function() {
                $('#' + timeoutRoute).fadeTo(0, 0);
                $('#' + timeoutRoute).hide();
            }, 3000);
        
            currentRoute = route;
        } else {
            $('#' + currentRoute).fadeTo(0, 0);
            $('#' + currentRoute).hide();
            $('#' + route).show();
            currentRoute = route;
        }
    }


    

    // TODO: Determine if this is CORE
    $('.header li div').removeClass('header-active');
    $('#headerMenuButton' + route).addClass('header-active');

}
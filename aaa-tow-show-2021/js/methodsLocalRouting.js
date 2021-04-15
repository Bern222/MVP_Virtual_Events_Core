// Location for custom route code
function localRouteMethods(route) {
    switch(route) {
        case enumRoutes.EXTERIOR:
            $('.navigation-container').hide();
            openModalInline('welcomeModal');
        break;
        case enumRoutes.TRANSITION_FLY_IN:
            $('.navigation-container').hide();
        break;
        case enumRoutes.LOBBY:
            openModalVideo(dataContent.FILES.LOBBY_WELCOME_VIDEO, 1000);
        break;
        case enumRoutes.BOOTH_1:
            $('.navigation-container').show();
            openModalVideo(dataContent.FILES.BOOTH_1_WELCOME_VIDEO, 1500);
        break;
        case enumRoutes.BOOTH_2:
            $('.navigation-container').show();
            openModalVideo(dataContent.FILES.BOOTH_2_WELCOME_VIDEO, 1500);
        break;
        case enumRoutes.BOOTH_3:
            $('.navigation-container').show();
            openModalVideo(dataContent.FILES.BOOTH_3_WELCOME_VIDEO, 1500);
        break;
        case enumRoutes.BOOTH_4:
            $('.navigation-container').show();
            openModalVideo(dataContent.FILES.BOOTH_4_WELCOME_VIDEO, 1500);
        break;
        case enumRoutes.BOOTH_5:
            $('.navigation-container').show();
            openModalVideo(dataContent.FILES.BOOTH_5_WELCOME_VIDEO, 1500);
        break;
        case enumRoutes.BOOTH_6:
            $('.navigation-container').show();
            openModalVideo(dataContent.FILES.BOOTH_6_WELCOME_VIDEO, 1500);
        break;
    }
}

function backgroundVideoOnEndedCallback(routeId) {
    switch(routeId) {
        case enumRoutes.TRANSITION_FLY_IN:
            changeRoute(enumRoutes.LOBBY);   
        break;
    }
}

function videoOnEndedCallback(videoId) {
    logEvent(configRoutes[currentRouteIndex].id, enumButtonActions.VIDEO_COMPLETE + ' - ' + videoId);
    switch(videoId) {
        case dataContent.FILES.LOBBY_WELCOME_VIDEO:
            $.fancybox.close();
        break;
        case dataContent.FILES.BOOTH_1_WELCOME_VIDEO:
            $.fancybox.close();
        break;
        case dataContent.FILES.BOOTH_2_WELCOME_VIDEO:
            $.fancybox.close();
        break;
        case dataContent.FILES.BOOTH_3_WELCOME_VIDEO:
            $.fancybox.close();
        break;
        case dataContent.FILES.BOOTH_4_WELCOME_VIDEO:
            $.fancybox.close();
        break;
        case dataContent.FILES.BOOTH_5_WELCOME_VIDEO:
            $.fancybox.close();
        break;
        case dataContent.FILES.BOOTH_6_WELCOME_VIDEO:
            $.fancybox.close();
        break;
    }
}

function videoBeforeCloseCallback(videoId) {
    logEvent(configRoutes[currentRouteIndex].id, enumButtonActions.VIDEO_CLOSED + ' - ' + videoId);
    switch(videoId) {
        case dataContent.FILES.LOBBY_WELCOME_VIDEO:
            setTimeout(function() {
                changeRoute(enumRoutes.BOOTH_1)
            }, 1000);
        break;
    }
}
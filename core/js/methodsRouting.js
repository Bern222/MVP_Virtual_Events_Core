function changeRoute(route) {    
    prepareRoute(route);
    localRouteMethods(route);

    // May need to be in a callback depending on local route methods
    $('#' + route).fadeTo(routeFadeTime, 1);
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
    $('#' + route).show();

}
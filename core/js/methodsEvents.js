function determineCategory(optionalRoute) {
    var eventRoute = currentRoute;
    if (optionalRoute != '') {
        eventRoute = optionalRoute;
    }
    return eventRoute;
}
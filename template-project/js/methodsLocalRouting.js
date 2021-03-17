// Location for custom route code
function localRouteMethods(route) {
    switch(route) {
        case enumRoutes.EXTERIOR:
            
        break;
        case enumRoutes.LOBBY:
            openModalVideo(dataExternalLinks.LOBBY, 1000);
        break;
        case enumRoutes.AUDITORIUM:
            openModalIframe(dataIframes.AUDITORIUM, 1000);
        break;
        case enumRoutes.SEMINAR_ROOMS:
            openModalInline(enumModals.SEMINAR_ROOMS, 1000);
        break;
        case enumRoutes.NETWORKING_LOUNGE:
            openModalInline(enumModals.NETWORKING_LOUNGE, 1000);
        break;
        case enumRoutes.INFORMATION_DESK:
            openModalInline(enumModals.INFORMATION_DESK, 1000);
        break;
    }
}
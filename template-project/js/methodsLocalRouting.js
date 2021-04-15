// Location for custom route code
function localRouteMethods(route) {
    switch(route) {
        case enumRoutes.EXTERIOR:
            
        break;
        case enumRoutes.LOBBY:
            openModalVideo(dataContent.FILES.SAMPLE_VIDEO, 1000);
        break;
        case enumRoutes.AUDITORIUM:
            openModalInline(enumModals.AUDITORIUM, 1000);
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

function videoOnEndedCallback() {

}

function videoBeforeCloseCallback() {

}
const configMainMenu = {
    logo: {
        imagePath: '../core/images/logos/logo.png',
        action: enumButtonActions.OPEN_EXTERNAL_LINK,
        actionParams: {
            url: dataExternalLinks.HOMEPAGE,
            name: '_blank'
        }               
    },
    menuItems: [
        {
            displayText: 'Lobby',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.LOBBY
        },
        {
            displayText: 'Auditorium',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.AUDITORIUM
        },
        {
            displayText: 'Exhibit Hall',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.EXHIBIT_HALL
        },
        {
            displayText: 'Seminar Rooms',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.SEMINAR_ROOMS
        },
        {
            displayText: 'Networking Lounge',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.NETWORKING_LOUNGE
        },
        {
            displayText: 'Agenda',
            action: enumButtonActions.OPEN_MODAL_INLINE,
            actionParams: enumRoutes.AGENDA
        },
        {
            displayText: 'Information Desk',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.INFORMATION_DESK
        }
    ]   
}
const configMainMenu = {
    enableMobileMenu: true,
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
            actionParams: enumModals.AGENDA
        },
        {
            displayText: 'Information Desk',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.INFORMATION_DESK
        }
    ],
    rightMenu: {
        menuItems: [
            {
                displayText: 'Your Profile',
                action: enumButtonActions.OPEN_MODAL_INLINE,
                actionParams: enumModals.PROFILE
            },
            {
                displayText: 'Logout',
                action: enumButtonActions.LOGOUT
            }
        ]
    }
}
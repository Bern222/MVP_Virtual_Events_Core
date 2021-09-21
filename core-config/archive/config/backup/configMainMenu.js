const configMainMenu = {
    enableMobileMenu: true,
    classes: [
        'main-menu-gradient'
    ],
    logo: {
        imagePath: 'images/logo.png',
        action: enumButtonActions.OPEN_EXTERNAL_LINK,
        actionParams: {
            url: ''
        }               
    },
    menuItems: [
        {
            displayText: 'WELCOME',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.WELCOME
        },
        {
            displayText: 'LOBBY',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.LOBBY
        },
        {
            displayText: 'GARAGE',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.GARAGE
        },
        {
            displayText: 'AAA TOOLBOX',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.TOOLBOX
        },
        {
            displayText: 'EVENT SURVEY',
            // action: enumButtonActions.OPEN_MODAL_INLINE,
            // actionParams: enumModals.EVENT_SURVEY
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.EVENT_SURVEY
        }
       
    ]
    // rightMenu: {
    //     divider: {
    //         color: 'red',
    //     },
    //     menuItems: [
    //         {
    //             displayText: '0 of 10 Booths Visited',
    //             classes: [
    //                 'right-menu-item-red'
    //             ]
    //         }
    //     ],
    // }
}
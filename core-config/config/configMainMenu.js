const configMainMenu = {
    enableMobileMenu: true,
    classes: [
        'main-menu-gradient'
    ],
    logo: {
        imagePath: '../core-config/content/images/logos/logo.png',
        action: '',
        actionParams: ''      
    },
    menuItems: [
        {
            displayText: 'EXTERIOR',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.EXTERIOR
        },
        {
            displayText: 'LOBBY',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.LOBBY
        },
        {
            displayText: 'LOGIN',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.LOGIN
        },
        {
            displayText: 'INFORMATION DESK',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.INFORMATION_DESK
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
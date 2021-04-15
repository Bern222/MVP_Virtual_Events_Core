const configMainMenu = {
    enableMobileMenu: true,
    classes: [
        'main-menu-gradient'
    ],
    logo: {
        imagePath: 'images/logo.png',
        action: enumButtonActions.OPEN_EXTERNAL_LINK,
        actionParams: {
            url: dataContent.EXTERNAL_LINKS.HOMEPAGE,
            name: '_blank'
        }               
    },
    menuItems: [
        {
            displayText: 'Who We Are',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.BOOTH_1
        },
        {
            displayText: 'Service Provider Benefits / Expectations',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.BOOTH_2
        },
        {
            displayText: 'Preferred Service Provider',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.BOOTH_3
        },
        {
            displayText: 'Mobile Battery Program',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.BOOTH_4
        },
        {
            displayText: 'Approved Auto Repair',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.BOOTH_5
        },
        {
            displayText: 'Training',
            action: enumButtonActions.OPEN_ROUTE,
            actionParams: enumRoutes.BOOTH_6
        }
    ],
    rightMenu: {
        divider: {
            color: 'red',
        },
        menuItems: [
            {
                displayText: 'Submit an Application',
                classes: [
                    'right-menu-item-red'
                ],
                action: enumButtonActions.OPEN_FILE,
                actionParams: dataContent.FILES.APPLICATION
            },
            {
                displayText: 'Schedule an Appointment',
                action: enumButtonActions.OPEN_EXTERNAL_LINK,
                actionParams: {
                    url: dataContent.EXTERNAL_LINKS.BOOKING_LINK
                }
            }
        ],
        // profileMenu: {
        //     menuItems: [
        //         // {
        //         //     displayText: 'Your Profile',
        //         //     action: enumButtonActions.OPEN_MODAL_INLINE,
        //         //     actionParams: enumModals.PROFILE
        //         // },
        //         {
        //             displayText: 'Logout',
        //             action: enumButtonActions.LOGOUT
        //         }
        //     ]
        // }
    }
}
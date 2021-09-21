var configRoutes = [
    {
        id: enumRoutes.LOGIN,
        title: 'Login',
        backgroundLandscape: dataContent.EXTERIOR_BACKGROUND,
        backgroundPortrait: '',
        elements: []
    },
    {
        id: enumRoutes.EXTERIOR,
        title: 'Exterior',
        backgroundLandscape: dataContent.EXTERIOR_BACKGROUND,
        backgroundPortrait: '',
        disableBackgroundVideoAutoplay: true,
        disableBackgroundVideoMuted: true,
        disableBackgroundVideoLoop: true,
        transition: 'none',
        elements: [
            {
                id: enumRoutes.EXTERIOR + 'Element1',
                title: 'Element 1',
                classes: [
                    'absolute-button'
                ],
                overrideCss: {
                    "top": '0%',
                    "left": '0%',
                    "height": '20%',
                    "width": '20%',
                    "transform": "rotate(50deg)"
                },
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.EXTERIOR
            },
            {
                id: enumRoutes.EXTERIOR + 'Element2',
                title: 'Element 2',
                classes: [
                    'absolute-button'
                ],
                overrideCss: {
                    "top": '0%',
                    "left": '0%',
                    "height": '40%',
                    "width": '40%'
                },
                action: enumButtonActions.OPEN_EXTERNAL_LINK,
                actionParams: dataContent.TEST_ASSET_5
            }
        ]
    },
    {
        id: enumRoutes.LOBBY,
        title: 'Lobby',
        backgroundLandscape: dataContent.LOBBY_BACKGROUND,
        backgroundPortrait: '',
       
        disableBackgroundVideoAutoplay: false,
        disableBackgroundVideoLoop: false,
        elements: [
            {
                id: enumRoutes.LOBBY + 'Element1',
                title: 'Element 1',
                classes: [
                    'absolute-button'
                ],
                overrideCss: {
                    "top": '10%',
                    "left": '80%',
                    "height": '20%',
                    "width": '20%',
                    "transform": "rotate(50deg)"
                },
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.EXTERIOR
            },
            {
                id: enumRoutes.LOBBY + 'Element2',
                title: 'Element 2',
                classes: [
                    'absolute-button'
                ],
                overrideCss: {
                    "top": '70%',
                    "left": '20%',
                    "height": '10%',
                    "width": '40%',
                    "transform": "rotate(270deg)"
                },
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.INFORMATION_DESK
            }
        ]
    },
    
    {
        id: enumRoutes.INFORMATION_DESK,
        title: 'Information Desk',
        backgroundLandscape: dataContent.INFORMATION_DESK_BACkGROUND,
        backgroundPortrait: '',
        elements: []
    },
    
]
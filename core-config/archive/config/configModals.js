var configModals = [
    {
        id: enumModals.VIDEO_EXAMPLE,
        title: 'Video Example',
        type: enumModalTypes.VIDEO,
    },
    {
        id: enumModals.IFRAME_EXAMPLE,
        title: 'IFrame Example',
        type: enumModalTypes.IFRAME,
    },
    {
        id: enumModals.SIMPLE_LIST_EXAMPLE,
        title: 'Simple List Example',
        type: enumModalTypes.SIMPLE_LIST,
        buttons: [
            {
                type: 'header',
                title: 'Human Resources'
            },
            {
                buttonText: 'Voice of the Employee',
                action: enumButtonActions.OPEN_MODAL_VIDEO,
                actionParams: dataContent.TEST_ASSET_8
            },
            {
                buttonText: 'Refreshed Recognition Process!',
                action: enumButtonActions.OPEN_EXTERNAL_LINK,
                actionParams: dataContent.TEST_ASSET_4
            }
        ]
    },
    {
        id: enumModals.THUMB_LIST_EXAMPLE,
        title: 'Thumb List Example',
        type: enumModalTypes.THUMB_LIST,
        buttons: [
            {
                type: 'header',
                title: 'Human Resources'
            },
            {
                thumbImage: 'images/thumbs/booth2-1.jpg',
                buttonText: 'Voice of the Employee',
                action: enumButtonActions.OPEN_MODAL_VIDEO,
                actionParams: dataContent.TEST_ASSET_8
            },
            {
                thumbImage: 'images/thumbs/booth2-2.jpg',
                buttonText: 'Refreshed Recognition Process!',
                action: enumButtonActions.OPEN_EXTERNAL_LINK,
                actionParams: dataContent.TEST_ASSET_4
            }
        ]
    }
];
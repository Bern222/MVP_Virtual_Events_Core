var configModals = [
    {
        id: enumModals.AUDITORIUM,
        type: enumModalTypes.THUMB_LIST,
        overrideCss: {},
        buttons: [
            {
                buttonText: 'Auditorium Link 1 (Modal Video)',
                action: enumButtonActions.OPEN_MODAL_VIDEO,
                actionParams: dataContent.FILES.SAMPLE_VIDEO
            },
            {
                buttonText: 'Agenda Link 1',
                action: enumButtonActions.OPEN_EXTERNAL_LINK,
                actionParams: {
                    url: dataContent.EXTERNAL_LINKS.SAMPLE_HOME,
                    name: '_blank'
                }
            }
        ]
    },
    {
        id: enumModals.SEMINAR_ROOMS,
        type: enumModalTypes.THUMB_LIST,
        overrideCss: {},
        buttons: [
            {
                buttonText: 'Seminar Room 1',
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.SEMINAR_ROOM_1
            },
            {
                buttonText: 'Seminar Room 2',
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.SEMINAR_ROOM_2
            },
            {
                buttonText: 'Seminar Room 3',
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.SEMINAR_ROOM_3
            }
        ]
    },
    {
        id: enumModals.NETWORKING_LOUNGE,
        type: enumModalTypes.THUMB_LIST,
        buttons: [
            {
                buttonText: 'Networking Lounge 1',
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.NETWORKING_LOUNGE_1
            },
            {
                buttonText: 'Networking Lounge 2',
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.NETWORKING_LOUNGE_2
            },
            {
                buttonText: 'Networking Lounge 3',
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.NETWORKING_LOUNGE_3
            }
        ]
    },
    {
        id: enumModals.INFORMATION_DESK,
        type: enumModalTypes.SIMPLE_LIST,
        buttons: [
            {
                buttonText: 'Information Desk 1',
                action: enumButtonActions.OPEN_EXTERNAL_LINK,
                actionParams: {
                    url: dataContent.EXTERNAL_LINKS.SAMPLE_HOME,
                    name: '_blank'
                }
            },
            {
                buttonText: 'Information Desk 2',
                action: enumButtonActions.OPEN_FILE,
                actionParams: dataContent.FILES.SAMPLE_PDF
            },
            {
                buttonText: 'Information Desk 3',
                action: enumButtonActions.OPEN_FILE,
                actionParams: dataContent.FILES.SAMPLE_DOC
            },
            {
                buttonText: 'Information Desk 4',
                action: enumButtonActions.OPEN_MODAL_VIDEO,
                actionParams: dataContent.FILES.SAMPLE_VIDEO
            },
        ]
    },
    {
        id: enumModals.AGENDA,
        type: enumModalTypes.SIMPLE_LIST,
        buttons: [
            {
                buttonText: 'Agenda Link 1 (EXTERNAL LINK)',
                action: enumButtonActions.OPEN_EXTERNAL_LINK,
                actionParams: {
                    url: 'https://mvpcollaborative.com',
                    name: '_blank'
                }
            },
            {
                buttonText: 'Agenda Link 2 (MODAL VIDEO)',
                action: enumButtonActions.OPEN_MODAL_VIDEO,
                actionParams: dataSampleContent.SAMPLE_VIDEO
            },
            {
                buttonText: 'Agenda Link 3 (ROUTE CHANGE - Information Desk)',
                action: enumButtonActions.OPEN_ROUTE,
                actionParams: enumRoutes.INFORMATION_DESK
            }
        ]
    },
];
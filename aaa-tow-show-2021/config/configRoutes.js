const configRoutes = [
    {
        id: enumRoutes.EXTERIOR,
        backgroundLandscape: 'images/backgrounds/exterior.jpg',
        backgroundPortrait: '',        
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        backgroundVideoAutoplay: true,
        buttons: {
            landscape: [
                {
                    icon: '',
                    classes: [
                        'absolute-button',
                        'audio-play'
                    ],
                    overrideCss: {
                        "top": '0%',
                        "left": '0%',
                        "height": '100%',
                        "width": '100%'
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.TRANSITION_FLY_IN
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.TRANSITION_FLY_IN,
        backgroundLandscape: '',
        backgroundPortrait: '',
        backgroundVideoLandscape: 'https://d1vxcp6kmz704x.cloudfront.net/aaa_tow_show_2021/flyin-audio.mp4',
        backgroundVideoPortrait: '',
        disableBackgroundVideoAutoplay: true,
        disableBackgroundVideoMuted: true,
        disableBackgroundVideoLoop: true,
        transition: 'none'
    },
    {
        id: enumRoutes.LOBBY,
        backgroundLandscape: 'images/backgrounds/lobby.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: ''
    },
    {
        id: enumRoutes.BOOTH_1,
        backgroundLandscape: 'images/backgrounds/booth1.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            landscape: [
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "53%",
                        "left": "28%",
                        "width": "10%",
                        "height": "14%"
                    },
                    action: enumButtonActions.OPEN_FILE,
                    actionParams: dataContent.FILES.BOOTH_1_1_PDF
                },
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "45%",
                        "left": "84%",
                        "width": "6%",
                        "height": "22%",
                    },
                    action: enumButtonActions.OPEN_FILE,
                    actionParams: dataContent.FILES.BOOTH_1_2_PDF
                },
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "45%",
                        "left": "75%",
                        "width": "9%",
                        "height": "23%"
                    },
                    
                    action: enumButtonActions.OPEN_MODAL_HTML,
                    actionParams: {
                        modalId: enumModals.TESTIMONIAL,
                        html: createTesimonialHtml(1),
                    }
                }
            ],
            portrait: [

            ],
            // pageButtons: [
            //     {
            //         icon: '',
            //         buttonText: 'Back',
            //         classes: [
            //             'route-side-button'
            //         ],
            //         action: enumButtonActions.OPEN_ROUTE,
            //         actionParams: enumRoutes.LOBBY
            //     }
            // ]
        }
    },
    {
        id: enumRoutes.BOOTH_2,
        backgroundLandscape: 'images/backgrounds/booth2.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            landscape: [
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "49%",
                        "left": "33%",
                        "width": "10%",
                        "height": "15%",
                    },
                    action: enumButtonActions.OPEN_FILE,
                    actionParams: dataContent.FILES.BOOTH_2_PDF
                },
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "50%",
                        "left": "58%",
                        "width": "8%",
                        "height": "24%",
                    },
                    action: enumButtonActions.OPEN_MODAL_HTML,
                    actionParams: {
                        modalId: enumModals.TESTIMONIAL,
                        html: createTesimonialHtml(2),
                    }
                }
            ],
            portrait: [

            ],
            // pageButtons: [
            //     {
            //         icon: '',
            //         buttonText: 'Back',
            //         classes: [
            //             'route-side-button'
            //         ],
            //         action: enumButtonActions.OPEN_ROUTE,
            //         actionParams: enumRoutes.LOBBY
            //     }
            // ]
        }
    },
    {
        id: enumRoutes.BOOTH_3,
        backgroundLandscape: 'images/backgrounds/booth3.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            landscape: [
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "52%",
                        "left": "41%",
                        "width": "8%",
                        "height": "20%",
                    },
                    action: enumButtonActions.OPEN_FILE,
                    actionParams: dataContent.FILES.BOOTH_3_PDF
                },
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "50%",
                        "left": "60%",
                        "width": "8%",
                        "height": "20%",
                    },
                    action: enumButtonActions.OPEN_MODAL_HTML,
                    actionParams: {
                        modalId: enumModals.TESTIMONIAL,
                        html: createTesimonialHtml(3),
                    }
                }
            ],
            portrait: [

            ],
            // pageButtons: [
            //     {
            //         icon: '',
            //         buttonText: 'Back',
            //         classes: [
            //             'route-side-button'
            //         ],
            //         action: enumButtonActions.OPEN_ROUTE,
            //         actionParams: enumRoutes.LOBBY
            //     }
            // ]
        }
    },
    {
        id: enumRoutes.BOOTH_4,
        backgroundLandscape: 'images/backgrounds/booth4.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            landscape: [
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "50%",
                        "left": "7%",
                        "width": "7%",
                        "height": "26%",
                    },
                    action: enumButtonActions.OPEN_FILE,
                    actionParams: dataContent.FILES.BOOTH_4_PDF
                },
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "50%",
                        "left": "20%",
                        "width": "10%",
                        "height": "19%",
                    },
                    action: enumButtonActions.OPEN_MODAL_HTML,
                    actionParams: {
                        modalId: enumModals.TESTIMONIAL,
                        html: createTesimonialHtml(4),
                    }
                }
            ],
            portrait: [

            ],
            // pageButtons: [
            //     {
            //         icon: '',
            //         buttonText: 'Back',
            //         classes: [
            //             'route-side-button'
            //         ],
            //         action: enumButtonActions.OPEN_ROUTE,
            //         actionParams: enumRoutes.LOBBY
            //     }
            // ]
        }
    },
    {
        id: enumRoutes.BOOTH_5,
        backgroundLandscape: 'images/backgrounds/booth5.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            landscape: [
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "50%",
                        "left": "7%",
                        "width": "7%",
                        "height": "26%",
                    },
                    action: enumButtonActions.OPEN_FILE,
                    actionParams: dataContent.FILES.BOOTH_5_PDF
                },
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "45%",
                        "left": "68%",
                        "width": "7%",
                        "height": "26%",
                    },
                    action: enumButtonActions.OPEN_MODAL_HTML,
                    actionParams: {
                        modalId: enumModals.TESTIMONIAL,
                        html: createTesimonialHtml(5),
                    }
                }
            ],
            portrait: [

            ],
            // pageButtons: [
            //     {
            //         icon: '',
            //         buttonText: 'Back',
            //         classes: [
            //             'route-side-button'
            //         ],
            //         action: enumButtonActions.OPEN_ROUTE,
            //         actionParams: enumRoutes.LOBBY
            //     }
            // ]
        }
    },
    {
        id: enumRoutes.BOOTH_6,
        backgroundLandscape: 'images/backgrounds/booth6.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            landscape: [
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "45%",
                        "left": "21%",
                        "width": "8%",
                        "height": "23%",
                    },
                    action: enumButtonActions.OPEN_FILE,
                    actionParams: dataContent.FILES.BOOTH_6_1_PDF
                },
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "50%",
                        "left": "63.6%",
                        "width": "8%",
                        "height": "23%",
                    },
                    action: enumButtonActions.OPEN_FILE,
                    actionParams: dataContent.FILES.BOOTH_6_2_PDF
                },
                {
                    icon: 'icon-arrow',
                    overrideCss: {
                        "top": "45%",
                        "left": "80%",
                        "width": "8%",
                        "height": "23%",
                    },
                    action: enumButtonActions.OPEN_MODAL_HTML,
                    actionParams: {
                        modalId: enumModals.TESTIMONIAL,
                        html: createTesimonialHtml(6),
                    }
                }
            ],
            portrait: [

            ],
            // pageButtons: [
            //     {
            //         icon: '',
            //         buttonText: 'Back',
            //         classes: [
            //             'route-side-button'
            //         ],
            //         action: enumButtonActions.OPEN_ROUTE,
            //         actionParams: enumRoutes.LOBBY
            //     }
            // ]
        }
    }
]
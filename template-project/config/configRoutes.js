const configRoutes = [
    {
        id: enumRoutes.EXTERIOR,
        backgroundLandscape: '../core/images/backgrounds/exterior.jpg',
        backgroundPortrait: '',        
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            landscape: [
                {
                    icon: '',
                    classes: [
                        'absolute-button'
                    ],
                    overrideCss: {
                        "top": '0%',
                        "left": '0%',
                        "height": '100%',
                        "width": '100%'
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.LOBBY
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.LOBBY,
        backgroundLandscape: '../core/images/backgrounds/lobby.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "53%",
                        "left": "78%",
                        "width": "15%",
                        "height": "3%",
                        "transform": "rotate(-1deg)"
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.AUDITORIUM
                },
                {
                    icon: '',
                    overrideCss: {
                        "top": "56.9%",
                        "left": "78%",
                        "width": "15%",
                        "height": "4%",
                        "transform": "rotate(0deg)"
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.SEMINAR_ROOMS
                },
                {
                    icon: '',
                    overrideCss: {
                        "top": "62%",
                        "left": "78%",
                        "width": "15%",
                        "height": "4%",
                        "transform": "rotate(1deg)"
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.NETWORKING_LOUNGE
                },
                {
                    icon: '',
                    overrideCss: {
                        "top": "63%",
                        "left": "10%",
                        "width": "30%",
                        "height": "20%"
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.INFORMATION_DESK
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.AUDITORIUM,
        backgroundLandscape: '../core/images/backgrounds/auditorium.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.LOBBY
                }
            ]
        }
    },
    {
        id: enumRoutes.SEMINAR_ROOMS,
        backgroundLandscape: '../core/images/backgrounds/seminar-rooms.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.LOBBY
                },
                {
                    icon: '',
                    buttonText: 'Event Menu',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_MODAL_INLINE,
                    actionParams: enumModals.SEMINAR_ROOMS_MODAL
                }
            ]
        }
    },
    {
        id: enumRoutes.SEMINAR_ROOM_1,
        backgroundLandscape: '../core/images/backgrounds/seminar-1.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.SEMINAR_ROOMS
                }
            ],
            landscape: [  
                {
                    icon: '',
                    overrideCss: {
                        "top": "0%",
                        "left": "0%",
                        "height": "0%",
                        "width": "0%"
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.SEMINAR_ROOM_2,
        backgroundLandscape: '../core/images/backgrounds/seminar-2.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.SEMINAR_ROOMS
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "0%",
                        "left": "0%",
                        "height": "0%",
                        "width": "0%"
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.SEMINAR_ROOM_3,
        backgroundLandscape: '../core/images/backgrounds/seminar-3.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.SEMINAR_ROOMS
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "0%",
                        "left": "0%",
                        "height": "0%",
                        "width": "0%"
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.EXHIBIT_HALL,
        backgroundLandscape: '../core/images/backgrounds/exhibit-hall.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.LOBBY
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "53%",
                        "left": "91%",
                        "width": "15%",
                        "height": "3%",
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.EXHIBIT_1
                },
                {
                    icon: '',
                    overrideCss: {
                        "top": "56.9%",
                        "left": "91%",
                        "width": "15%",
                        "height": "4%",
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.EXHIBIT_2
                },
                {
                    icon: '',
                    overrideCss: {
                        "top": "62%",
                        "left": "91%",
                        "width": "15%",
                        "height": "4%",
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.EXHIBIT_3
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.EXHIBIT_1,
        backgroundLandscape: '../core/images/backgrounds/exhibit-1.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.EXHIBIT_HALL
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "0%",
                        "left": "0%",
                        "height": "0%",
                        "width": "0%"
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.EXHIBIT_2,
        backgroundLandscape: '../core/images/backgrounds/exhibit-2.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.EXHIBIT_HALL
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "0%",
                        "left": "0%",
                        "height": "0%",
                        "width": "0%"
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.EXHIBIT_3,
        backgroundLandscape: '../core/images/backgrounds/exhibit-3.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.EXHIBIT_HALL
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "0%",
                        "left": "0%",
                        "height": "0%",
                        "width": "0%"
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.NETWORKING_LOUNGE,
        backgroundLandscape: '../core/images/backgrounds/networking-lounge.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.EXHIBIT_HALL
                },
                {
                    icon: '',
                    buttonText: 'Lounges',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_MODAL_INLINE,
                    actionParams: enumModals.NETWORKING_LOUNGE_MODAL
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "53%",
                        "left": "91%",
                        "width": "15%",
                        "height": "3%",
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.NETWORKING_LOUNGE_1
                },
                {
                    icon: '',
                    overrideCss: {
                        "top": "56.9%",
                        "left": "91%",
                        "width": "15%",
                        "height": "4%",
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.NETWORKING_LOUNGE_2
                },
                {
                    icon: '',
                    overrideCss: {
                        "top": "62%",
                        "left": "91%",
                        "width": "15%",
                        "height": "4%",
                    },
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.NETWORKING_LOUNGE_3
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.NETWORKING_LOUNGE_1,
        backgroundLandscape: '../core/images/backgrounds/networking-1.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.NETWORKING_LOUNGE
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "0%",
                        "left": "0%",
                        "height": "0%",
                        "width": "0%"
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                }
            ],
            portrait: [

            ]
        }
    },

    {
        id: enumRoutes.NETWORKING_LOUNGE_2,
        backgroundLandscape: '../core/images/backgrounds/networking-2.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.NETWORKING_LOUNGE
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "0%",
                        "left": "0%",
                        "height": "0%",
                        "width": "0%"
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                }
            ],
            portrait: [

            ]
        }
    },

    {
        id: enumRoutes.NETWORKING_LOUNGE_3,
        backgroundLandscape: '../core/images/backgrounds/networking-3.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.NETWORKING_LOUNGE
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "0%",
                        "left": "0%",
                        "height": "0%",
                        "width": "0%"
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                }
            ],
            portrait: [

            ]
        }
    },
    {
        id: enumRoutes.INFORMATION_DESK,
        backgroundLandscape: '../core/images/backgrounds/information-desk.jpg',
        backgroundPortrait: '',
        backgroundVideoLandscape: '',
        backgroundVideoPortrait: '',
        buttons: {
            pageButtons: [
                {
                    icon: '',
                    buttonText: 'Back',
                    classes: [
                        'page-black-button'
                    ],
                    action: enumButtonActions.OPEN_ROUTE,
                    actionParams: enumRoutes.LOBBY
                }
            ],
            landscape: [
                {
                    icon: '',
                    overrideCss: {
                        "top": "53%",
                        "left": "91%",
                        "width": "15%",
                        "height": "3%",
                    },
                    action: enumButtonActions.OPEN_PDF,
                    actionParams: dataExternalLinks.INFORMATION_DESK_PDF
                },
                {
                    icon: '',
                    overrideCss: {
                        "top": "56.9%",
                        "left": "91%",
                        "width": "15%",
                        "height": "4%",
                    },
                    action: enumButtonActions.OPEN_EXTERNAL_LINK,
                    actionParams: {
                        url: '',
                        name: '_blank' // name is defaulted to _blank if left empty
                    }
                },
                {
                    icon: '',
                    overrideCss: {
                        "top": "62%",
                        "left": "91%",
                        "width": "15%",
                        "height": "4%",
                    },
                    action: enumButtonActions.OPEN_MODAL_IFRAME,
                    actionParams: dataIframes.INFORMATION_DESK
                }
            ],
            portrait: [

            ]
        }
    }
]
var currentRoute = enumsConfigurator.ROUTE_LANDING;
var currentConfigurationRouteId = '';
var currentConfigurationModalId = '';
var currentKey = '';
var configuratorPath = '../';
var elementGraphicType = 'elementGraphicImage';

// Status to track what needs to be published
var updateStatus = {
    enumEvents: false,
    enumModals: false,
    enumRoutes: false,

    configSiteSettings: false,
    configGlobalVariables: false,
    configMainMenu: false,
    configModals: false,
    configRoutes: false,
};

var iconFail = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2"><circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/><line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/><line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/></svg>';
var iconSuccess = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2"><circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/><polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/></svg>';
var defaultBackgroundPath = 'content/images/backgrounds/default.jpg';

var filteredContentArray = [];

function changeConfiguratorRoute(route) {
    resetCurrentVars();

    $('#' + currentRoute).fadeTo(1,0);
    $('#' + currentRoute).hide();
    
    switch(route) {
        case enumsConfigurator.ROUTE_LANDING:
            break;
        case enumsConfigurator.ROUTE_CONTENT_LIBRARY:
            filteredContentArray = dataContent;
            updateContentTable('library');
            break;
        case enumsConfigurator.ROUTE_AUTHENTICATION:
            // TODO: temp until config is attached.
            updateAuthentication(enumAuthenticationTypes.NONE);
            break;
        case enumsConfigurator.ROUTE_MAIN_MENU:
            loadMainMenuConfiguration();
            updateListMenu(route);
            break;
        case enumsConfigurator.ROUTE_ROUTES:
            updateListMenu(route);
            break;
        case enumsConfigurator.ROUTE_MODALS:
            updateListMenu(route);
            break;
        case enumsConfigurator.ROUTE_ROUTE_CONFIGURATOR:
            loadRouteConfiguration();
            break;
        case enumsConfigurator.ROUTE_MODAL_CONFIGURATOR:
            loadModalConfiguration();
            break;
    }

    currentRoute = route;
    $('#' + currentRoute).show();
    $('#' + currentRoute).fadeTo(700,1);
}



function updateListMenu(type) {
    switch(type){
        case enumsConfigurator.ROUTE_MAIN_MENU:
            $('.configurator-list-main-menu-container').empty();
            if(configMainMenu && configMainMenu.menuItems && configMainMenu.menuItems.length > 0) {
                for(var i=0;i<configMainMenu.menuItems.length;i++) {
                    $('.configurator-list-main-menu-container').append('<div class="configurator-list-row"><h2 class="configurator-list-header" onclick="openConfigureMainMenu(\'' + i +'\')">' + configMainMenu.menuItems[i].displayText + '</h2><div class="list-delete-button" onclick="deleteRow(\'mainMenu\',\'' + i + '\')">X</div></div>');
                }
            }
            break;
        case enumsConfigurator.ROUTE_ROUTES:
            $('.configurator-list-route-container').empty();
            for(var i=0;i<configRoutes.length;i++) {
                $('.configurator-list-route-container').append('<div class="configurator-list-row"><h2 class="configurator-list-header" onclick="openConfigureRoute(\'' + configRoutes[i].id +'\')">' + configRoutes[i].title + '</h2><div class="list-delete-button" onclick="deleteRow(\'routes\',\'' + configRoutes[i].id + '\')">X</div></div>');
            }
            break;
        case enumsConfigurator.ROUTE_MODALS:
            $('.configurator-list-modal-container').empty();
            for(var i=0;i<configModals.length;i++) {
                $('.configurator-list-modal-container').append('<div class="configurator-list-row"><h2 class="configurator-list-header" onclick="openConfigureModal(\'' + configModals[i].id +'\')">' + configModals[i].title + '</h2><div class="list-delete-button" onclick="deleteRow(\'modals\',\'' + configModals[i].id + '\')">X</div></div>');
            }
            break;
    }
}

function deleteRow(type, id) {
    
    switch(type) {
        case 'mainMenu':
            // for(var i=0;i<configMainMenu.menuItems.length; i++) {
            //     console.log('IDS:', configMainMenu.menuItems[i], id);
            //     if(enumRoutes[i] == id) {
            //         enumRoutes.splice(i, 1);
            //     }
            // }
            // for(var i=0;i<configRoutes.length; i++) {
            //     console.log('IDS:', configRoutes[i].id, id);
            //     if(configRoutes[i].id == id) {
            //         configRoutes.splice(i, 1);
            //     }
            // }

            if (configMainMenu && configMainMenu.menuItems && configMainMenu.menuItems.length > 0) {
                configMainMenu.menuItems.splice(id, 1);


                console.log('CONFIG MAIN MENU:', configMainMenu);
                updateListMenu(enumsConfigurator.ROUTE_MAIN_MENU);
                updateStatus.configMainMenu = true;
                $('#publishButton').removeClass('disabled-button');
                $('#publishButton').addClass('green-button');
            }
        break;
        case 'routes':
            for(var i=0;i<enumRoutes.length; i++) {
                console.log('IDS:', enumRoutes[i], id);
                if(enumRoutes[i] == id) {
                    enumRoutes.splice(i, 1);
                }
            }
            for(var i=0;i<configRoutes.length; i++) {
                console.log('IDS:', configRoutes[i].id, id);
                if(configRoutes[i].id == id) {
                    configRoutes.splice(i, 1);
                }
            }
            updateListMenu(enumsConfigurator.ROUTE_ROUTES);
            updateStatus.enumRoutes = true;
            updateStatus.configRoutes = true;
            $('#publishButton').removeClass('disabled-button');
            $('#publishButton').addClass('green-button');
        break;
        case 'modals':
            for(var i=0;i<enumModals.length; i++) {
                console.log('IDS:', enumModals[i], id);
                if(enumModals[i] == id) {
                    enumModals.splice(i, 1);
                }
            }
            for(var i=0;i<configModals.length; i++) {
                if(configModals[i].id == id) {
                    configModals.splice(i, 1);
                }
            }
            updateListMenu(enumsConfigurator.ROUTE_MODALS);
            updateStatus.enumModals = true;
            updateStatus.configModals = true;
            $('#publishButton').removeClass('disabled-button');
            $('#publishButton').addClass('green-button');
        break;
    }
}

function openModal(type, data = '') {
    switch(type) {
        case enumsConfigurator.ADD_MAIN_MENU_ITEM:
            currentMainMenuItemIndex = -1;
            currentMainMenuItem = {};
            $('#mainMenuItemModalHeader').text('Add Menu Item');
            $('#inputMainMenuDisplayText').val('');
            resetActionParams();

            // $('#selectMenuItemAction').val();
            $.fancybox.open({
                src  : '#mainMenuItemModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            break;
        case enumsConfigurator.EDIT_MAIN_MENU_ITEM:
            $('#mainMenuItemModalHeader').text('Edit Menu Item');
            $('#inputMainMenuDisplayText').val(currentMainMenuItem.displayText);

            // ACTION
            console.log('EDIT MAIN MENU ACTION:', currentMainMenuItem);
            if(currentMainMenuItem.action && currentMainMenuItem.action != '') {
                $('.select-element-action').val(currentMainMenuItem.action);
                updateActionParamInput(currentMainMenuItem, currentMainMenuItem.action, 'mainMenu');
            } else {
                resetActionParams();
            }

            $.fancybox.open({
                src  : '#mainMenuItemModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            break;
        case enumsConfigurator.SELECT_CONTENT:
            filteredContentArray = getFilteredContent(data);
            updateContentTable(data);
            $.fancybox.open({
                src  : '#selectContentModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            break;
        case enumsConfigurator.ADD_CONTENT:
            $('#inputAddContentTitle').val('');
            $('#inputAddContentPath').val('');
            $("#inputAddContentType")[0].selectedIndex = 0;
            // TODO: Temp until upload is fixed
            updateContentInputContainer(enumContentTypes.EXTERNAL_LINK);

            $.fancybox.open({
                src  : '#addContentModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            break;
        case enumsConfigurator.EDIT_CONTENT:
            $('#inputContentKey').val(data);
            $('#inputEditContentTitle').val(dataContent[data].title);
            $('#inputEditContentType').val(dataContent[data].type);

            console.log('PATH:', dataContent[data], dataContent[data].path);
            // TODO: this will change with upload fixes
            $('#inputEditContentPath').val(dataContent[data].path);

            var inputVal = $("#inputEditContentType option:checked").val();
            if (inputVal == enumContentTypes.EXTERNAL_LINK || inputVal == enumContentTypes.IFRAME) {
                $('#inputContentPathContianer').show();
            } else {
                // TODO: Temp until upload is fixed
                // $('#inputContentPathContianer').hide();
                $('#inputContentPathContianer').show();
            }

            $("#inputEditContentType").change(function () {
                var inputVal = $("#inputEditContentType option:checked").val();

                if (inputVal == enumContentTypes.EXTERNAL_LINK || inputVal == enumContentTypes.IFRAME) {
                    $('#inputContentPathContianer').show();
                } else {
                    // TODO: Temp until upload is fixed
                    // $('#inputContentPathContianer').hide();
                    $('#inputContentPathContianer').show();
                }
            });

            $.fancybox.open({
                src  : '#editContentModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            break;
        case enumsConfigurator.DELETE_CONTENT:
            currentKey = data;
            $.fancybox.open({
                src  : '#deleteContentModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            break;
        case enumsConfigurator.ADD_ROUTE:
            $.fancybox.open({
                src  : '#addRouteModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            break;
        case enumsConfigurator.ADD_MODAL:
            $.fancybox.open({
                src  : '#addModalModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            
            break;
        case enumsConfigurator.EDIT_MODAL:
            $('#inputMainMenuDisplayText').val(currentMainMenuItem.displayText);

            // ACTION
            console.log('EDIT MAIN MENU ACTION:', currentMainMenuItem);
            if(currentMainMenuItem.action && currentMainMenuItem.action != '') {
                $('.select-element-action').val(currentMainMenuItem.action);
                updateActionParamInput(currentMainMenuItem, currentMainMenuItem.action, 'mainMenu');
            } else {
                resetActionParams();
            }

            $.fancybox.open({
                src  : '#editModalModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            break;
        case enumsConfigurator.SELECT_MODAL:
            $.fancybox.open({
                src  : '#addModalModal',
                type : 'inline',
                animationEffect: 'zoom',
                animationDuration: configSiteSettings.modalFadeTime,
                opts : {
                    touch: false
                },
                afterClose: function() {
                }
            });
            break;
        case enumsConfigurator.DELETE_CUSTOM_FIELD:
            
        
            break;
    }
}

function closeMenu() {
    $('#menuToggle input').prop("checked", false);
}

function showAlert(html, closeWindow = true) {
    
    if(closeWindow) {
        $.fancybox.close();
    }

    $('#alertModalText').html(html);

    $.fancybox.open({
        src  : '#alertModal',
        type : 'inline',
        animationEffect: 'zoom',
        animationDuration: configSiteSettings.modalFadeTime,
        opts : {
            touch: false
        }
    });
}

function publishContent() {
    var stringDataContent = 'var dataContent = ';
    stringDataContent += JSON.stringify(dataContent);
    request = $.post("libs/publish.php", {method: 'publishContent', dataContent: stringDataContent});
    request.done(function (response, textStatus, jqXHR) {
        console.log('PUBLISH CONTENT RESPONSE: ', response);
        showAlert('Content Published');
        $('#publishButton').addClass('disabled-button');
        $('#publishButton').removeClass('green-button');
    });
}

function publishConfigs() {
    var stringEnumEvents = '';
    var stringEnumModals = '';
    var stringEnumRoutes = '';

    var stringConfigSiteSettings = '';
    var stringConfigGlobalVariables = '';
    var stringConfigMainMenu = '';
    var stringConfigModals = '';
    var stringConfigRoutes = '';

    var doPublish = false;
    // Enums
    // enumEvents
    if (updateStatus.enumEvents) {
        stringEnumEvents = 'const enumEvents = ';
        stringEnumEvents += JSON.stringify(enumEvents);
        doPublish = true;
    }
    // enumModals
    if (updateStatus.enumModals) {
        stringEnumModals = 'const enumModals = ';
        stringEnumModals += JSON.stringify(enumModals);
        doPublish = true;
    }
    // enumRoutes
    if (updateStatus.enumRoutes) {
        stringEnumRoutes = 'const enumRoutes = ';
        stringEnumRoutes += JSON.stringify(enumRoutes);
        doPublish = true;
    }
    console.log('UPDATE STATUS:', updateStatus);

    // Configs
    // Site Settings
    if (updateStatus.configSiteSettings) {
        stringConfigSiteSettings = 'var configSiteSettings = ';
        stringConfigSiteSettings += JSON.stringify(configSiteSettings);
        doPublish = true;
    }
    // configGlobalVariables
    if (updateStatus.configGlobalVariables) {
        // stringConfigGlobalVariables = JSON.stringify(configGlobalVariables);
        // stringEnumEvents.prepend('const enumEvents = ');
        // doPublish = true;
    }
    // configMainMenu
    if (updateStatus.configMainMenu) {
        stringConfigMainMenu = 'var configMainMenu = ';
        stringConfigMainMenu += JSON.stringify(configMainMenu);
        doPublish = true;
    }
    // configModals
    if (updateStatus.configModals) {
        stringConfigModals = 'var configModals = ';
        stringConfigModals += JSON.stringify(configModals);
        doPublish = true;
    }
    // configRoutes
    if (updateStatus.configRoutes) {
        stringConfigRoutes = 'var configRoutes = ';
        stringConfigRoutes += JSON.stringify(configRoutes);
        doPublish = true;
    }


    console.log('PUBLISH ENUM EVENTS:', stringEnumEvents);
    console.log('PUBLISH ENUM MODALS:', stringEnumModals);
    console.log('PUBLISH ENUM ROUTES:', stringEnumRoutes);

    console.log('PUBLISH CONFIG SITE SETTINGS:', stringConfigSiteSettings);
    console.log('PUBLISH CONFIG GLOBAL:', stringConfigGlobalVariables);
    console.log('PUBLISH CONFIG MAIN MENU:', stringConfigMainMenu);
    console.log('PUBLISH CONFIG MODALS:', stringConfigModals);
    console.log('PUBLISH CONFIG ROUTES:', stringConfigRoutes);

    if (doPublish) {
        request = $.post("libs/publish.php", {method: 'publishConfigs', enumEvents: stringEnumEvents, enumModals: stringEnumModals, enumRoutes: stringEnumRoutes, configSiteSettings: stringConfigSiteSettings, configGlobalVariables: stringConfigGlobalVariables, configMainMenu: stringConfigMainMenu, configModals: stringConfigModals, configRoutes: stringConfigRoutes});
        request.done(function (response, textStatus, jqXHR) {
            console.log('PUBLISH CONFIG RESPONSE: ', response);
            updateStatus = {
                enumEvents: false,
                enumModals: false,
                enumModals: false,
            
                configGlobalVariables: false,
                configMainMenu: false,
                configModals: false,
                configRoutes: false,
            };
            showAlert('Site Published');
            $('#publishButton').addClass('disabled-button');
            $('#publishButton').removeClass('green-button');
            // if (response) {
            // 	showAlert('Site Published ', response);
            // } else {
            //     showAlert('Publish Error', response);
            // }
        });
    }   
}
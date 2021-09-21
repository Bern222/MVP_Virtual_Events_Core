var currentMainMenuConfig;
var currentMainMenuItem = {};
var currentMainMenuItemIndex = -1;

function loadMainMenuConfiguration() {
    currentMainMenuConfig = configMainMenu;
}

function openConfigureMainMenu(index) {
    currentMainMenuItemIndex = index;
    currentMainMenuItem = configMainMenu.menuItems[index];
    openModal(enumsConfigurator.EDIT_MAIN_MENU_ITEM, index);
}

function saveMainMenuItem() {
    if(currentMainMenuItem != {}) {
        if ($("#inputMainMenuDisplayText").val() != '') {
            currentMainMenuItem.displayText = $("#inputMainMenuDisplayText").val();
            if (currentMainMenuItemIndex >= 0) {
                configMainMenu.menuItems[currentMainMenuItemIndex] = currentMainMenuItem;
            } else {
                configMainMenu.menuItems.push(currentMainMenuItem);
            }
            updateStatus.configMainMenu = true;
            $('#publishButton').removeClass('disabled-button');
            $('#publishButton').addClass('green-button');
            showAlert('Main Menu Item Saved!');
            updateListMenu(enumsConfigurator.ROUTE_MAIN_MENU);
        } else {
            showAlert('Please enter a display name.', false);
        }
    }
}

function cancelMainMenuItemChanges() {
    currentMainMenuItem = {};
    currentMainMenuItemIndex = -1;
    $.fancybox.close();
}

function updateCurrentMainMenuConfiguration(type, configData) {
    
    console.log('UPDATE MAIN MENU CONFIG:', type, configData);
    // if (currentRouteConfig && currentElement) {
        switch(type) {
            case enumsConfigurator.ADD_MAIN_MENU_ITEM:
                
            break;
            case enumsConfigurator.MAIN_MENU_ACTION:
                console.log('UPDATE MAIN MENU CONFIG - Action', $('#selectMainMenuAction').val(), currentMainMenuConfig, configMainMenu[currentMainMenuItemIndex]);
                currentMainMenuItem.action = $('#selectMainMenuAction').val();
                currentMainMenuConfig.menuItems[currentMainMenuItemIndex] = currentMainMenuItem;
            break;
            case enumsConfigurator.MAIN_MENU_ACTION_DATA:
                if (configData) {
                    console.log('UPDATE MAIN MENU CONFIG - Action data', configData, currentMainMenuItem, currentMainMenuConfig, configMainMenu[currentMainMenuItemIndex]);
                    currentMainMenuItem.actionParams = configData;
                    currentMainMenuConfig.menuItems[currentMainMenuItemIndex] = currentMainMenuItem;
                }
            break;
        }
    // }
}
function fillInputs() {
    for (var key in enumButtonActions) {
        if (enumButtonActions.hasOwnProperty(key)) {

            var optionText = '';
            var optionValue = enumButtonActions[key];
            switch(enumButtonActions[key]) {
                case enumButtonActions.OPEN_ROUTE:
                    optionText = 'Open Page';
                break;
                case enumButtonActions.OPEN_EXTERNAL_LINK:
                    optionText = 'Open External Link';
                break;
                case enumButtonActions.OPEN_MODAL_INLINE:
                    optionText = 'Open Inline Modal';
                break;
                case enumButtonActions.OPEN_MODAL_HTML:
                    optionText = 'Open HTML Modal';
                break;
                case enumButtonActions.OPEN_MODAL_VIDEO:
                    optionText = 'Open Video Modal';
                break;
                case enumButtonActions.OPEN_MODAL_IFRAME:
                    optionText = 'Open Iframe Modal';
                break;
            }
    
            if(optionText != '') {
                $('.select-element-action').append('<option value=' + optionValue + '>' + optionText +'</option>');
            }
        }
    }

    // updateActionParamInput(enumButtonActions.OPEN_ROUTE, 'routes');
}

function determineUpdateConfiguration() {

}


// TODO: this is clunky and confusing.  Needs to be rethought
function updateActionParamInput(actionData, newAction, type, update = true) {
    console.log('UPDATE ACTION PARAMS INPUT:', actionData, newAction);
    switch(newAction) {
        case enumButtonActions.OPEN_ROUTE:
            refreshRouteSelect();
            console.log('CURRENT ELEMENT ACTION PARAMS:', actionData);
            if(actionData.actionParams) {
                $('.select-route').val(actionData.actionParams);
            } else {
                $(".select-route")[0].selectedIndex = 0;
            }

            // if (update) {
            //     determineUpdateConfiguration();
            // }

            if (update && configRoutes && configRoutes.length > 0) {
                if (type == 'routes') {
                    updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, configRoutes[0].id);
                } else {
                    updateCurrentMainMenuConfiguration(enumsConfigurator.MAIN_MENU_ACTION_DATA, configRoutes[0].id);
                }
            }
        break;
        case enumButtonActions.OPEN_EXTERNAL_LINK:
        case enumButtonActions.OPEN_MODAL_VIDEO:
        case enumButtonActions.OPEN_MODAL_IFRAME:
            refreshContentSelectDisplay(actionData, newAction);

            if (update) {
                if (type == 'routes') {
                    updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, actionData.actionParams);
                } else {
                    updateCurrentMainMenuConfiguration(enumsConfigurator.MAIN_MENU_ACTION_DATA, actionData.actionParams);
                }
            }
        break;
        case enumButtonActions.OPEN_MODAL_INLINE:
            refreshModalInlineSelect(type);
            if(actionData.actionParams) {
                $('.select-modal').val(actionData.actionParams);
            } else {
                $(".select-modal")[0].selectedIndex = 0;
            }
            if (update && configRoutes && configRoutes.length > 0) {
                if (type == 'routes') {
                    updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, configRoutes[0].id);
                } else {
                    updateCurrentMainMenuConfiguration(enumsConfigurator.MAIN_MENU_ACTION_DATA, configRoutes[0].id);
                }
            }
        break;
        case enumButtonActions.OPEN_MODAL_HTML:
            // TODO
        break;
    }

    if (type == 'routes') {
        updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION);
    } else {
        updateCurrentMainMenuConfiguration(enumsConfigurator.MAIN_MENU_ACTION);
    }
}

function resetActionParams() {
    $('.select-element-action').eq(1).prop('selected', true);;
    $('.action-params-container').empty();
    $('.action-params-container').append('<div class="">Select Action Above</div>');
}


function refreshRouteSelect(type = 'actionParams') {
    if(type == 'actionParams') {
        $('.action-params-container').empty();
        $('.action-params-container').append('<select class="select-default select-route"></select>');

        // $('#selectRoute').on('change', updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, this.value));
        $('.select-route').on('change', function() {
            console.log('ROUTE CONFIG CHECK:', currentRouteConfig);
            if (currentRouteConfig && currentRouteConfig.elements) {
                updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, this.value);
            } else if (currentMainMenuConfig){
                updateCurrentMainMenuConfiguration(enumsConfigurator.MAIN_MENU_ACTION_DATA, this.value);
            }
        });
    } else {
       
    }

    $('.select-route').empty();
    for(var i=0;i<configRoutes.length;i++) {
       $('.select-route').append('<option value="' + configRoutes[i].id + '">' + configRoutes[i].title + '</option>');
    }
}

function refreshModalInlineSelect(type) {
    $('.action-params-container').empty();
    $('.action-params-container').append('<select class="select-default select-modal"></select>');

    // $('#selectRoute').on('change', updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, this.value));
    $('.select-modal').on('change', function() {
        console.log('MODAL CONFIG CHECK:', type);
        if (type == 'routes') {
            updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, this.value);
        } else {
            updateCurrentMainMenuConfiguration(enumsConfigurator.MAIN_MENU_ACTION_DATA, this.value);
        }
    });

    for(var i=0;i<configModals.length;i++) {
       $('.select-modal').append('<option value="' + configModals[i].id + '">' + configModals[i].title + '</option>');
    }
}

function refreshContentSelectDisplay(actionData, action) {
    console.log('Refresh Select Display:', actionData, action);
    $('.action-params-container').empty();

    var title = '<b>No Content Selected</b>';
    var path = '';

    $('.action-params-container').append('<div class="align-center"><div class="small-header right-space">Content</div><div class="plus-button" onclick="openModal(\'' + enumsConfigurator.SELECT_CONTENT +'\' ,\'' + action + '\')"></div></div>');

    switch(action) { 
        case enumButtonActions.OPEN_EXTERNAL_LINK:
            if(actionData.actionParams && dataContent[actionData.actionParams]) {
                title = '<b>Link Name: </b> ' + dataContent[actionData.actionParams].title;
                path = '<b>Path: </b> ' + dataContent[actionData.actionParams].path;
            }
        break;
        case enumButtonActions.OPEN_MODAL_VIDEO:
            if(actionData.actionParams && dataContent[actionData.actionParams]) {
                title = '<b>Video Name: </b> ' + dataContent[actionData.actionParams].title;
                path = '<b>Path: </b> ' + dataContent[actionData.actionParams].path;
            }
        break;
        case enumButtonActions.OPEN_MODAL_IFRAME:
            if(actionData.actionParams && dataContent[actionData.actionParams]) {
                title = '<b>Content Name: </b> ' + dataContent[actionData.actionParams].title;
                path = '<b>Path: </b> ' + dataContent[actionData.actionParams].path;
            }
        break;
    }

    $('.action-params-container').append('<div class="indent">' + title + '</div><br/>');
    $('.action-params-container').append('<div class="indent">' + path + '</div>');
}
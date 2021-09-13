var currentRouteConfig;
var currentRouteIndex;
var curSelectedContent;


function openConfigureRoute(routeId) {
    currentConfigurationRouteId = routeId;
    changeRoute(enumsConfigurator.ROUTE_ROUTE_CONFIGURATOR);
}

function addRoute() {
    var routeTitle = $('#inputAddRouteName').val();
    var routeId = formatIdFromTitle(routeTitle);

    console.log('CHECK:', formatEnumKey(routeTitle), enumRoutes[formatEnumKey(routeTitle)], enumRoutes)
    if (!enumRoutes[formatEnumKey(routeTitle)]) {
        enumRoutes[formatEnumKey(routeTitle)] = routeId;

        configRoutes.push({
            id: routeId,
            title: routeTitle,
            elements: []
        });

        updateStatus.enumRoutes = true;
        updateStatus.configRoutes = true;
        $('#publishButton').removeClass('disabled-button');
        $('#publishButton').addClass('green-button');

        console.log('ADD ROUTE:', configRoutes, enumRoutes);

        updateListMenu(enumsConfigurator.ROUTE_ROUTES);
        showAlert(iconSuccess + '"' + routeTitle + '" Page Created');
    } else {
        showAlert(iconFail + '"' + routeTitle + '" Already Exists');
    }
}

function loadRouteConfiguration() {
    for(var i=0;i<configRoutes.length;i++) {
        if (configRoutes[i].id == currentConfigurationRouteId) {
            currentRouteIndex = i;
            currentRouteConfig = JSON.parse(JSON.stringify(configRoutes[i]));
        }
    }

    currentElement = {};
    currentElementIndex = -1;

    $('#configruatorNoElementText').show();
    $('#configruatorElementConfigContainer').hide();
    $('#backgroundImage').attr('src', configuratorPath + defaultBackgroundPath);

    refreshRouteVariables();
    refreshBackground();
    refreshElements();

}

function refreshRouteVariables() {
    $('#inputRouteName').val(currentRouteConfig.title);

    if (currentRouteConfig.backgroundLandscape) {
        $('#configuratorBackgroundTitle').html('<b>Title:</b> ' + currentRouteConfig.backgroundLandscape.title);
        $('#configuratorBackgroundPath').html('<b>File Name:</b> ' + getFilename(currentRouteConfig.backgroundLandscape.path));
    } else {
        $('#configuratorBackgroundTitle').html('<b>No Background Selected</b>');
        $('#configuratorBackgroundPath').html('');
    }
}


function refreshBackground() {
    var background = configuratorPath + defaultBackgroundPath;

    // TODO ADD SUPPORT FOR PORTRAIT
    if (currentRouteConfig.backgroundLandscape && currentRouteConfig.backgroundLandscape.path) {
        background = configuratorPath + currentRouteConfig.backgroundLandscape.path;
        
    }
    
    $('#backgroundImage').attr('src', background);
}

function refreshElements() {
    $('#elementContainer').empty();
    if (currentRouteConfig.elements && currentRouteConfig.elements.length > 0) {
        for(var i=0;i<currentRouteConfig.elements.length;i++) {
            addElement(currentRouteConfig.elements[i]);
        }
    }
}

function saveRoute() {
    $('#buttonSaveRoute').addClass('disabled-button');
    $('#buttonSaveRoute').removeClass('green-button');
    $('#buttonDiscardRoute').hide();

    console.log('SAVE ROUTE:', currentRouteConfig);
    // Add new config to main config
    configRoutes[currentRouteIndex] = JSON.parse(JSON.stringify(currentRouteConfig));
    updateStatus.configRoutes = true;
    $('#publishButton').removeClass('disabled-button');
    $('#publishButton').addClass('green-button');
}

function discardRouteChanges() {
    currentRouteConfig = JSON.parse(JSON.stringify(configRoutes[currentRouteIndex]));
    updateStatus.configRoutes = true;
    $('#publishButton').removeClass('disabled-button');
    $('#publishButton').addClass('green-button');

    console.log('HERE;', currentRouteConfig);

    loadRouteConfiguration();
}

function updateCurrentRouteConfiguration(type, configData) {
    $('#buttonSaveRoute').removeClass('disabled-button');
    $('#buttonSaveRoute').addClass('green-button');
    $('#buttonDiscardRoute').show();

    
    console.log('UPDATE CONFIG:', type, configData);
    // if (currentRouteConfig && currentElement) {
        switch(type) {
            case enumsConfigurator.ELEMENT_CREATE:
                if (configData) {
                    console.log('UPDATE CONFIG - Element Create', currentRouteConfig, configRoutes[currentRouteIndex]);

                    var newElement = {
                        id: configData,
                        title: 'Element ' + currentRouteConfig.elements.length,
                        overrideCss: {}
                    }

                    if (currentRouteConfig.elements) {
                        currentRouteConfig.elements.push(newElement);
                    } else {
                        currentRouteConfig.elements = [newElement];
                    }
                }
            break;
            case enumsConfigurator.ELEMENT_DELETE:
                console.log('UPDATE CONFIG - Element Delete', currentRouteConfig, configRoutes[currentRouteIndex]);
                currentRouteConfig.elements.splice(currentElementIndex, 1);
            break;
            case enumsConfigurator.ELEMENT_DRAG:
                console.log('UPDATE CONFIG - Element Drag', currentRouteConfig, configRoutes[currentRouteIndex]);
                currentElement.overrideCss = getCurrentElementCss();
                currentRouteConfig.elements[currentElementIndex] = currentElement;
            break;
            case enumsConfigurator.ELEMENT_RESIZE:
                console.log('UPDATE CONFIG - Element Resize', currentRouteConfig, configRoutes[currentRouteIndex]);
                currentElement.overrideCss = getCurrentElementCss();
                currentRouteConfig.elements[currentElementIndex] = currentElement;
            break;
            case enumsConfigurator.ELEMENT_ROTATE:
                if (configData) {
                    console.log('UPDATE CONFIG - Element Rotate', configData, currentRouteConfig, configRoutes[currentRouteIndex]);
                    currentElement.overrideCss.transform = configData;
                    currentRouteConfig.elements[currentElementIndex] = currentElement;
                }
            break;
            case enumsConfigurator.ELEMENT_ICON:
                currentElement.icon = $('#inputElementIcon').val();
                console.log('UPDATE CONFIG - Element Icon', $('#inputElementIcon').val(), currentRouteConfig, configRoutes[currentRouteIndex]);
                currentRouteConfig.elements[currentElementIndex] = currentElement;
            break;
            case enumsConfigurator.ELEMENT_NAME:
                console.log('UPDATE CONFIG - Element Name', $('#inputElementName').val(), currentRouteConfig, configRoutes[currentRouteIndex]);
                currentElement.title = $('#inputElementName').val();
                currentRouteConfig.elements[currentElementIndex] = currentElement;
            break;
            case enumsConfigurator.ELEMENT_BACKGROUND:
                if (configData) {
                    console.log('UPDATE CONFIG - Background', currentRouteConfig, configRoutes[currentRouteIndex]);
                    currentRouteConfig.backgroundLandscape = dataContent[configData];
                    refreshBackground();
                    refreshRouteVariables();
                }
            break;
            case enumsConfigurator.ELEMENT_ACTION:
                console.log('UPDATE CONFIG - Action', $('#selectElementAction').val(), currentRouteConfig, configRoutes[currentRouteIndex]);
                currentElement.action = $('#selectElementAction').val();
                // currentElement.actionParams = '';
                currentRouteConfig.elements[currentElementIndex] = currentElement;
            break;
            case enumsConfigurator.ELEMENT_ACTION_DATA:
                if (configData) {
                    console.log('UPDATE CONFIG - Action data', configData, currentElement, currentRouteConfig, configRoutes[currentRouteIndex]);
                    currentElement.actionParams = configData;
                    currentRouteConfig.elements[currentElementIndex] = currentElement;
                }
            break;
        }

    // }    
}





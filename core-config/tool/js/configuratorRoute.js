var currentRouteConfig;
var currentRouteIndex;
var curSelectedContent;


function openConfigureRoute(routeId) {
    hideElementGraphicInputs();
    currentConfigurationRouteId = routeId;
    changeConfiguratorRoute(enumsConfigurator.ROUTE_ROUTE_CONFIGURATOR);
}

function inputElementGraphicChange(value) {
    hideElementGraphicInputs();
    switch(value) {
        case 'graphic-custom':
            $('.input-element-graphic-custom').show();
        break;
        case 'graphic-arrow':
            updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_GRAPHIC, value);
        break;
        case 'graphic-dot':
            updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_GRAPHIC, value);
        break;
    }
}

function addRoute() {
    var routeTitle = $('#inputAddRouteName').val();
    var routeId = uuidv4();

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

    refreshDisplayConfiguratorRoute('all');
    refreshElements();
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

    console.log('UPDATE ROUTE CONFIGDATA:', type, configData)

    // if (currentRouteConfig && currentElement) {
        switch(type) {
            case enumsConfigurator.ROUTE_BACKGROUND: {
                if (configData) {
                    console.log('UPDATE CONFIG - Route Background', currentRouteConfig, configRoutes[currentRouteIndex]);
                    currentRouteConfig.backgroundLandscape = dataContent[configData];
                    // updateBackground();
                    refreshDisplayConfiguratorRoute('background');
                }
            }
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
            case enumsConfigurator.ELEMENT_NAME:
                console.log('UPDATE CONFIG - Element Name', $('#inputElementName').val(), currentRouteConfig, configRoutes[currentRouteIndex]);
                currentElement.title = $('#inputElementName').val();
                currentRouteConfig.elements[currentElementIndex] = currentElement;
            break;
            case enumsConfigurator.ELEMENT_GRAPHIC:
                console.log('UPDATE CONFIG - Graphic', $('#inputElementGraphic').val());
                currentElement.graphic = $('#inputElementGraphic').val();
                console.log('UPDATE CONFIG - Element Graphic', $('#inputElementGraphic').val(), currentRouteConfig, configRoutes[currentRouteIndex]);
                currentRouteConfig.elements[currentElementIndex] = currentElement;
            break;
            case enumsConfigurator.ELEMENT_GRAPHIC_IMAGE:
                console.log('UPDATE CONFIG - Graphic Image', configData, currentElement, currentRouteConfig, configRoutes[currentRouteIndex]);
                currentRouteConfig.elements[currentElementIndex] = currentElement;
                refreshDisplayConfiguratorRoute('graphic');
            break;
            case enumsConfigurator.ELEMENT_GRAPHIC_HOVER:
                console.log('UPDATE CONFIG - Graphic Hover', configData, currentElement, currentRouteConfig, configRoutes[currentRouteIndex]);
                currentRouteConfig.elements[currentElementIndex] = currentElement;
                refreshDisplayConfiguratorRoute('graphic');
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

function refreshDisplayConfiguratorRoute(type) {
    console.log('Refresh Graphic Display Start', currentElement, type, currentRouteConfig);
    switch (type) {
        case 'all':
            refreshDisplayConfiguratorRoute('route');
            refreshDisplayConfiguratorRoute('background');
            break;
        case 'route':
            $('#inputRouteName').val(currentRouteConfig.title);
        break;
        case 'background':
            var background = configuratorPath + defaultBackgroundPath;

            // TODO ADD SUPPORT FOR PORTRAIT
            if (currentRouteConfig.backgroundLandscape && currentRouteConfig.backgroundLandscape.path) {
                background = configuratorPath + currentRouteConfig.backgroundLandscape.path;
                
            }

            if (currentRouteConfig.backgroundLandscape) {
                console.log('BG LAND: ', currentRouteConfig.backgroundLandscape, currentRouteConfig);
                $('#configuratorBackgroundTitle').html('<b>Title:</b> ' + currentRouteConfig.backgroundLandscape.title);
                $('#configuratorBackgroundPath').html('<b>File Name:</b> ' + getFilename(currentRouteConfig.backgroundLandscape.path));
            } else {
                console.log('BG LAND 2: ');

                $('#configuratorBackgroundTitle').html('<b>No Background Selected - Using Default</b>');
                $('#configuratorBackgroundPath').html('');
                $('#backgroundImage').attr('src', '');
            }

            console.log('BACKGROUND', background);
            
            $('#backgroundImage').attr('src', background);
        break;
        case 'elements':
            
        break;
        case 'graphic': 

            console.log('Refresh Graphic Display', currentElement.graphic);

            if (currentElement.graphic && currentElement.graphic.image) {
                $('#configuratorGraphicImageTitle').html('<b>Title:</b> ' + dataContent[currentElement.graphic.image].title);
                $('#configuratorGraphicImagePath').html('<b>File Name:</b> ' + getFilename(dataContent[currentElement.graphic.image].path));
                $('#' + currentElement.id + ' .resize-element-container').html('<img class="full-width" src="' + configuratorPath + dataContent[currentElement.graphic.image].path + '"/>');
            } else {
                $('#configuratorGraphicImageTitle').html('No Content Selected');
                $('#configuratorGraphicImagePath').html('');
            }

            if (currentElement.graphic && currentElement.graphic.hover) {
                $('#configuratorGraphicHoverTitle').html('<b>Title:</b> ' + dataContent[currentElement.graphic.hover].title);
                $('#configuratorGraphicHoverPath').html('<b>File Name:</b> ' + getFilename(dataContent[currentElement.graphic.hover].path));
                
                // TODO: look at adding hover to preview
                // $('#' + currentElement.id + ' .resize-element-container').mouseenter(function() {
                //         $(this).css("background", "#F00").css("border-radius", "3px");
                //     }).mouseleave(function() {
                //         $(this).css("background", "00F").css("border-radius", "0px");
                //     });.html('<img class="full-width" src="' + configuratorPath + dataContent[currentElement.graphic.hover].path + '"/>');
            } else {
                $('#configuratorGraphicHoverTitle').html('No Content Selected');
                $('#configuratorGraphicHoverPath').html('');
            }
        break;
    }
}

function refreshElements() {
    $('#elementContainer').empty();
    if (currentRouteConfig.elements && currentRouteConfig.elements.length > 0) {
        for(var i=0;i<currentRouteConfig.elements.length;i++) {
            addElement(currentRouteConfig.elements[i]);
        }
    }
}

function hideElementGraphicInputs() {
    // May have more down the road
    $('.input-element-graphic-custom').hide();
}

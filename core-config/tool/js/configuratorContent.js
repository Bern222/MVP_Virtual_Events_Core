function saveContent(type) {
    var key = $('#inputContentKey').val();

    if(type == 'edit') {
        dataContent[key].title = $('#inputEditContentTitle').val();
        dataContent[key].type = $('#inputEditContentType').val();

        //TODO: Refactor once upload is fixed
        dataContent[key].path = $('#inputEditContentPath').val();
    } else {
        var formattedContentKey = uuidv4();

        //TODO: Refactor once upload is fixed
        var content = {
            title: $('#inputAddContentTitle').val(),
            type: $('#inputAddContentType').val(),
            path: $('#inputAddContentPath').val()
        }
        dataContent[formattedContentKey] = content;
    }

    filteredContentArray = dataContent;
    updateContentTable('library');
    // TODO: Determine publish flow
    publishContent();
    // showAlert(iconSuccess + ' Content Saved');
}

function deleteContent() {
    // NEED TO RECURSIVLY REMOVE LINKS FROM SITE
    delete dataContent[currentKey];
    delete filteredContentArray[currentKey];
    updateContentTable('library');
    // TODO: Determine publish flow with update table above
    publishContent();
    currentKey = '';
    showAlert(iconSuccess + 'Content Deleted');
}


function updateContentTable(type) {
    $('.content-library-table-body').empty();
    for (const key in filteredContentArray) {
        if (filteredContentArray.hasOwnProperty(key)) {
            // var inputVal = JSON.stringify(dataContent[key]);

            if (type && type == 'library') {
                $('.content-library-table-body').append('<tr class="clickable-table-row" onclick="openModal(\'editContent\', \'' + key + '\')"><td>' + filteredContentArray[key].title + '</td><td>' + filteredContentArray[key].type + '</td><td>' + dataContent[key].path + '</td><td><div class="remove-button" onclick="event.stopPropagation(); openModal(\'deleteContent\', \'' + key + '\')">X</div></td></tr>');
            } else {
                $('.content-library-table-body').append('<tr class="clickable-table-row" onclick="addSelectedContent(\'' + type + '\', \'' + key + '\');"><td>' + filteredContentArray[key].title + '</td><td>' + filteredContentArray[key].type + '</td><td>' + dataContent[key].path + '</td></tr>');
            }

        }
    }
    $(".content-library-table").tablesorter();
}


function getFilteredContent(type) {
    var returnContent = {};
    for (var key in dataContent) {
        if (dataContent.hasOwnProperty(key)) {

            var curContent = dataContent[key];
        // for(var i=0; i<dataContent.length; i++) {
            switch(type) {
                // TODO: Review the discrepency here
                case enumsConfigurator.ELEMENT_GRAPHIC_IMAGE:
                case enumsConfigurator.ELEMENT_GRAPHIC_HOVER:
                case enumContentTypes.IMAGE:
                    if (curContent.type == enumContentTypes.IMAGE ||
                        curContent.type == enumContentTypes.BACKGROUND ||
                        curContent.type == enumContentTypes.WORD_DOC ||
                        curContent.type == enumContentTypes.ICON ||
                        curContent.type == enumContentTypes.THUMBNAIL) {
                        returnContent[key] = curContent;
                    }
                    break;
                case enumContentTypes.BACKGROUND:
                    if (curContent.type == type) {
                        returnContent[key] = curContent;
                    }
                    break;
                case enumButtonActions.OPEN_EXTERNAL_LINK:
                    if (curContent.type == enumContentTypes.PDF ||
                        curContent.type == enumContentTypes.POWERPOINT ||
                        curContent.type == enumContentTypes.WORD_DOC ||
                        curContent.type == enumContentTypes.VIDEO ||
                        curContent.type == enumContentTypes.EXTERNAL_LINK) {
                        returnContent[key] = curContent;
                    }
                    break;
                case enumButtonActions.OPEN_MODAL_VIDEO:
                    if (curContent.type == enumContentTypes.VIDEO) {
                        returnContent[key] = curContent;
                    }
                    break;
                case enumButtonActions.OPEN_MODAL_IFRAME:
                    if (curContent.type == enumContentTypes.IFRAME ||
                        curContent.type == enumContentTypes.PDF ||
                        curContent.type == enumContentTypes.VIDEO ||
                        curContent.type == enumContentTypes.EXTERNAL_LINK) {
                        returnContent[key] = curContent;
                    }
                    break;
            }
        }
        
    }
    return returnContent;
}



// SELECT CONTENT -------------------
function selectContent(key) {
    curSelectedContent = key;
}

function addSelectedContent(type, data) {
    $.fancybox.close();
    console.log('Add selected content: ', type, data, currentRouteConfig, currentRouteConfig.elements);

    var updateMethod;
    var updateEnum;
    
    switch(currentRoute) {
        case enumsConfigurator.ROUTE_LANDING:
            break;
        case enumsConfigurator.ROUTE_CONTENT_LIBRARY:
            
            break;
        case enumsConfigurator.ROUTE_AUTHENTICATION:
            
            break;
        case enumsConfigurator.ROUTE_MAIN_MENU:
            updateMethod = updateCurrentMainMenuConfiguration;
            updateEnum = enumsConfigurator.MAIN_MENU_ACTION_DATA;
            break;
        case enumsConfigurator.ROUTE_ROUTES:
            break;
        case enumsConfigurator.ROUTE_MODALS:
            break;
        case enumsConfigurator.ROUTE_ROUTE_CONFIGURATOR:
            updateMethod = updateCurrentRouteConfiguration;
            updateEnum = enumsConfigurator.ELEMENT_ACTION_DATA;
            break;
        case enumsConfigurator.ROUTE_MODAL_CONFIGURATOR:
            break;
    }


    // TODO: figure out where graphic image / hover belong in emums, messy code with image and hover, should be seperated
    switch(type) {
        case enumsConfigurator.ELEMENT_GRAPHIC_IMAGE:
            if (currentElement.graphic) {
                if (currentElement.graphic.image) {
                    currentElement.graphic.image = data;
                } else {
                    if (typeof currentElement.graphic === 'string' || currentElement.graphic instanceof String) {
                        currentElement.graphic = {image: data}
                    } else {
                        currentElement.graphic['image'] = data;
                    }
                }
            } else {
                currentElement.graphic = {image: data};
            }
            updateMethod(enumsConfigurator.ELEMENT_GRAPHIC_IMAGE, data);
            break;
        case enumsConfigurator.ELEMENT_GRAPHIC_HOVER:
            if (currentElement.graphic) {
                if (currentElement.graphic.hover) {
                    currentElement.graphic.hover = data;
                } else {
                    if (typeof currentElement.graphic === 'string' || currentElement.graphic instanceof String) {
                        currentElement.graphic = {hover: data}
                    } else {
                        currentElement.graphic['hover'] = data;
                    }
                    
                }
            } else {
                currentElement.graphic = {hover: data};
            }
            updateMethod(enumsConfigurator.ELEMENT_GRAPHIC_HOVER, data);
            break;
        
        case enumContentTypes.BACKGROUND:
            updateMethod(enumsConfigurator.ROUTE_BACKGROUND, data);
            break;
        case enumButtonActions.OPEN_EXTERNAL_LINK:
        case enumButtonActions.OPEN_MODAL_VIDEO:
        case enumButtonActions.OPEN_MODAL_IFRAME:
            updateMethod(updateEnum, data);
          
            var actionObject = {
                actionParams: data
            }

            refreshContentSelectDisplay(actionObject, type);
            break;
        case enumsConfigurator.ELEMENT_ACTION_DATA:
            updateMethod(updateEnum, data);
            break;
    }
}

function updateContentInputContainer(value) {
    console.log('VALUE:', value);
    switch(value) {
        case enumContentTypes.EXTERNAL_LINK:
        case enumContentTypes.IFRAME:
        case enumContentTypes.VIDEO:
            $('#uploadContainer').hide();
            $('#pathInputContainer').show();
            break;
        default:
            //TODO: Temp before upload is fixed
            $('#uploadContainer').hide();
            $('#pathInputContainer').show();
            // $('#uploadContainer').show();
            // $('#pathInputContainer').hide();
            break;
    }
}

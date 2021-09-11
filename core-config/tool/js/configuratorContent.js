function addContent() {
    

}

function saveContent(type) {
    var key = $('#inputContentKey').val();

    if(type == 'edit') {
        dataContent[key].title = $('#inputEditContentTitle').val();
        dataContent[key].type = $('#inputEditContentType').val();

        //TODO: Refactor once upload is fixed
        dataContent[key].path = $('#inputEditContentPath').val();
    } else {
        dataContent[key].title = $('#inputAddContentTitle').val();
        dataContent[key].type = $('#inputAddContentType').val();

        //TODO: Refactor once upload is fixed
        dataContent[key].path = $('#inputAddContentPath').val();
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
                $('.content-library-table-body').append('<tr class="clickable-table-row" onclick="addSelectedContent(\'' + key + '\', \'' + type + '\');"><td>' + filteredContentArray[key].title + '</td><td>' + filteredContentArray[key].type + '</td><td>' + dataContent[key].path + '</td></tr>');
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

function addSelectedContent(data, type) {
    $.fancybox.close();
    console.log('SELECT CONTENT TYPE: ', data, type, currentRouteConfig);

    var updateMethod = updateCurrentMainMenuConfiguration;
    var updateEnum = enumsConfigurator.MAIN_MENU_ACTION_DATA;
    
    if (currentRouteConfig && currentRouteConfig.elements) {
        updateMethod = updateCurrentRouteConfiguration;
        updateEnum = enumsConfigurator.ELEMENT_ACTION_DATA;
    }

    switch(type) {
        case enumContentTypes.BACKGROUND:
            updateMethod(enumsConfigurator.ELEMENT_BACKGROUND, data);
            // updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_BACKGROUND, key);
            break;
        case enumButtonActions.OPEN_EXTERNAL_LINK:
        case enumButtonActions.OPEN_MODAL_VIDEO:
        case enumButtonActions.OPEN_MODAL_IFRAME:
            updateMethod(updateEnum, data);
            // if (currentRouteConfig && currentRouteConfig != {}) {
            //     updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, key);
            // } else {
            //     updateCurrentMainMenuConfiguration(enumsConfigurator.MAIN_MENU_ACTION_DATA, key);
            // }
            var actionObject = {
                actionParams: data
            }


            refreshContentSelectDisplay(actionObject, type);
            break;
        // case enumButtonActions.OPEN_MODAL_VIDEO:
        //     updateMethod(enumsConfigurator.ELEMENT_ACTION_DATA, key);
        //     // updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, key);
        //     refreshContentSelectDisplay(type);
        //     break;
        // case enumButtonActions.OPEN_MODAL_IFRAME:
        //     updateMethod(enumsConfigurator.ELEMENT_ACTION_DATA, key);
        //     // updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, key);
        //     refreshContentSelectDisplay(type);
        //     break;
        case enumsConfigurator.ELEMENT_ACTION_DATA:
            updateMethod(updateEnum, data);
            // updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, key);
            break;
    }
}

function refreshSelectContentModal(type) {
    updateContentTable(type);
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
            $('#uploadContainer').show();
            $('#pathInputContainer').hide();
            break;
    }
}
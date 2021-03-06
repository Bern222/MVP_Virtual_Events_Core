var currentElement = {};
var currentElementIndex = -1;

$(document).ready(function() {
    // $('.select-element-action').on('change', updateActionParamInput(this.value));
    // $('.select-route').on('change', updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ACTION_DATA, this.value));
});

function addElement(element) {
    console.log('ADD ELEMENT');
    $("#rotateSlider").slider('value', 0);

    var elementId = currentRouteConfig.id + 'Element' + uuidv4();;
    var elementTitle = 'Element ' + currentRouteConfig.elements.length;

    if (element) {
        elementId = element.id;
        elementTitle = element.title;
    }

    // console.log('ADD ELEMENT: ', elementId, element);

    $('#elementContainer').append('<div id="' + elementId +'" class="resize-element" style="background: ' + randomColor() + '" onclick="selectElement(\'' + elementId + '\')"><div>');
    $('#' + elementId).append('<div class="resize-element-container noselect">' + elementTitle + '</div>');
    $('#' + elementId).append('<div class="resize-element-remove" onclick="removeElement(\'' + elementId + '\')">X</div>');
    
    if (element) {
        console.log('ELEMENT: ', element);

        if (element.graphic && element.graphic.image) {
            $('#' + elementId + ' .resize-element-container').html('<img class="full-width" src="' + configuratorPath + dataContent[element.graphic.image].path + '"/>');
        }

        if (element.classes || element.overrideCss) {
            addStyles(element, elementId);
        }
    }
    
    $("#" + elementId).draggable({
        containment: "#backgroundImage",
        start: function( event, ui ) {
            // var elementSubstring = event.target.id.substr(event.target.id.lastIndexOf('t')+1)
            selectElement(event.target.id)
        },
        stop: function( event, ui ) {
            console.log('Stop Drag Event:', event);
            updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_DRAG, ui);
        }
    }).resizable({
        start: function( event, ui ) {
            // var elementSubstring = event.target.id.substr(event.target.id.lastIndexOf('t')+1)
            selectElement(event.target.id)
        },
        stop: function( event, ui ) {
            console.log('Stop Resize Event:', event.target.id);
            updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_RESIZE, ui);
        }
    });

    
    if (!element) {
        // var elementLength = currentRouteConfig.elements.length - 1;
        // currentElement = currentRouteConfig.elements[elementLength];
        // currentElementIndex = elementLength;
        updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_CREATE, elementId);
        selectElement(elementId);
    }

}

function selectElement(elementId) {
    console.log('SELECT ELEMENT LISTENER -  ID:', elementId)
    if (elementId && currentElement.id != elementId && elementId.includes(currentRouteConfig.id + "Element")) {
        for(var i=0;i<currentRouteConfig.elements.length;i++) {
            if (currentRouteConfig.elements[i].id == elementId) {
                currentElement = currentRouteConfig.elements[i];
                currentElementIndex = i;
            }
        }

        $('#configruatorNoElementText').hide();
        $('#configruatorElementConfigContainer').show();

        $('.configurator-element-config').show();
        $('.resize-element-remove').hide();

        
        if (currentElement.graphic && currentElement.graphic.image && currentElement.graphic.image != '') {
            $('#inputElementGraphic').val('graphic-custom');
            $('.input-element-graphic-custom').show();
            refreshDisplayConfiguratorRoute('graphic');
        } else {
            if (typeof currentElement.graphic === 'string' || currentElement.graphic instanceof String) {
                $('#inputElementGraphic').val(currentElement.graphic);
            } else {
                $('#inputElementGraphic').val('none');

            }
        }
        $('#inputElementName').val(currentElement.title);


        var rotateVal = 0;
        if (currentElement.overrideCss && currentElement.overrideCss.transform) {
            rotateVal = currentElement.overrideCss.transform.substring(7,currentElement.overrideCss.transform.length - 4);
        }

        $("#rotateSlider").slider('value', rotateVal);
        
        $('.resize-element').removeClass('element-active');
        
        $('#' + currentElement.id).addClass('element-active');
        $('#' + currentElement.id + ' .resize-element-remove').show();


        // ACTION
        if(currentElement.action && currentElement.action != '') {
            $('.select-element-action').val(currentElement.action);
            if (currentElement.actionParams && currentElement.actionParams != '') {
                // refreshContentSelectDisplay(currentElement, currentElement.action);
                updateActionParamInput(currentElement, currentElement.action, 'routes', false);
            }
        } else {
            resetActionParams();
        }
    }
}

function removeElement(id) {   
    $('#configruatorNoElementText').show();
    $('#configruatorElementConfigContainer').hide();
    currentElement.id = undefined;
    $('.configurator-element-config').hide();
    $("#" + id).remove();
    updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_DELETE, id);
}

function updateRotation() {
    if (currentElement.id) {
        var rotateString = 'rotate('+ $('#rotateSlider').slider("option", "value") +'deg)';
        $('#' + currentElement.id).css({'transform' : rotateString});

        updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ROTATE, rotateString);
    }
}



function addStyles(elementObj, elementId) {
	// Check config classes
	if (elementObj.classes && elementObj.classes.length > 0) {
		for (var k=0; k < elementObj.classes.length; k++) {
			$('#' + elementId).addClass(elementObj.classes[k]);
		}
	}

	// Add override CSS
	if (elementObj.overrideCss) {
		$('#' + elementId).css(elementObj.overrideCss);
	}
}

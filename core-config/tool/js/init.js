$(document).ready(function() {
    loadSiteSettings();
    refreshRouteSelect('initalLoad');

    $('#buttonDiscardRoute').hide();

    // Populate inputs with core enums
    // for (const key in enumModalTypes) {
    //     if (enumModalTypes.hasOwnProperty(key)) {
    //         $('.input-asset-types').append('<tr class="clickable-table-row" onclick="openModal(\'editContent\')"><td>' + dataContent[key].title + '</td><td>' + dataContent[key].type + '</td><td>' + dataContent[key].path + '</td></tr>');
    //     }
    // }

    for (const key in enumContentTypes) {
        if (enumContentTypes.hasOwnProperty(key)) {
            $('.input-content-types').append('<option value="' + enumContentTypes[key] + '">' + enumContentTypes[key] + '</option>)');
        }
    }

    for (const key in enumModalTypes) {
        if (enumModalTypes.hasOwnProperty(key)) {
            $('.input-modal-types').append('<option value="' + enumModalTypes[key] + '">' + enumModalTypes[key] + '</option>)');
        }
    }

    $('#menu a').click(function() {
        closeMenu();
    });
    
    // $('#backgroundImage').prop('src', '');

    $("#rotateSlider").slider({
        min: 0,
        max: 359,
        slide: updateRotation
    });

    $(window).mousedown(function(event){
        selectElement(event.target.id);
    });
    $(window).mouseup(function(event){
        // updateRotation();
        // getCss();
    });

    $(window).resize(function() {
        // TODO: possible handler for configurator refresh
    });

    // setInterval(function (){
    //     if (currentElement.id) {

    //     }
    // },100)

    fillInputs();


    changeRoute(enumsConfigurator.ROUTE_LANDING);
});
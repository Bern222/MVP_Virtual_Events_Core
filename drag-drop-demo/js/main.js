var buttonCount = 1;
var currentElementId;
var currentElementCss;
var mouseDown = false;

$(document).ready(function() {
    $('.button-config-container').hide();

    $("#rotateSlider").slider({
        min: 0,
        max: 359,
        slide: updateRotation
    });

    $(window).mousedown(function(event){
        console.log('ID:', event.target);
        selectElement(event.target.id);
    });
    $(window).mouseup(function(event){
        mouseDown = false;
        updateRotation();
        getCss();
    });

    $(window).resize(function() {
        
    });

    setInterval(function (){
        if (currentElementId) {

        }
    },100)
});

function addButton() {
    const buttonId = 'button' + buttonCount;
    $('#mainContainer').append('<div id="' + buttonId +'" class="resize-button" style="background: ' + randomColor() + '"><div>');
    $('#' + buttonId).append('<div class="resize-button-text">' + buttonId + '</div>');
    $('#' + buttonId).append('<div class="resize-button-remove" onclick="removeButton(\'' + buttonId + '\')">X</div>');

    $("#" + buttonId).draggable({
        containment: "#backgroundImage"
    }).resizable();

    selectElement(buttonId);
    buttonCount++;
}

function randomColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
}

function selectElement(buttonId) {
    if(buttonId && buttonId.includes("button")) {
        $('.button-config-container').show();
        $('.resize-button-remove').hide();
        $('#elementText').text(buttonId);
        mouseDown = true;
        $('.resize-button').removeClass('button-active');
        currentElementId = buttonId;
        $('#' + currentElementId).addClass('button-active');
        $('#' + currentElementId + ' .resize-button-remove').show();
        getCss();
    }
}

function updateRotation() {
    if (currentElementId) {
        console.log('SLIDER VALUE', $('#rotateSlider').slider("option", "value"));
        if (currentElementId) {
            $('#' + currentElementId).css({'transform' : 'rotate('+ $('#rotateSlider').slider("option", "value") +'deg)'});
            getCss();
        }
    }
}

function removeButton(id) {   
    currentElementId = undefined;
    $('.button-config-container').hide();
    $("#" + id).remove();
}

function getCss() {
    if (currentElementId) {
        var position = $('#' + currentElementId).position();
        var percentLeft = roundPercent(position.left /  $('#backgroundImage').width() * 100);
        var percentTop = roundPercent(position.top /  $('#backgroundImage').height() * 100);
        var percentWidth = roundPercent($('#' + currentElementId).width() / $('#backgroundImage').width() * 100);
        var percentHeight = roundPercent($('#' + currentElementId).height() / $('#backgroundImage').height() * 100);

        var cssHtml = 'top: ' + percentTop + '%,<br>left: ' + percentLeft + '%,<br>width: ' + percentWidth + '%,<br>height: ' + percentHeight + '%,<br>transform: rotate('+ $('#rotateSlider').slider("option", "value") +'deg)';

        $('#cssText').html(cssHtml);
    }
}

function roundPercent(value) {
    return(Math.round(value * 100) / 100);
}
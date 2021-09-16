function randomColorOld() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
}

function randomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

function roundPercent(value) {
    return value;
    // return(Math.round(value * 100) / 100);
}

function formatEnumKey(value) {
    return value.replace(/ /g,"_").toUpperCase();
}

// Replaced with uuidv4() id's
// function formatIdFromTitle(title) {
//     return title.replace(/(?:^\w|[A-Z]|\b\w)/g, function(word, index) {
//         return index === 0 ? word.toLowerCase() : word.toUpperCase();
//       }).replace(/\s+/g, '');
// }

function uploadAsset() {
    $(document).ready(function() {
        $("#but_upload").click(function() {
            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file', files);
   
            $.ajax({
                url: 'upload.php',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    if(response != 0){
                       alert('file uploaded');
                    }
                    else{
                        alert('file not uploaded');
                    }
                },
            });
        });
    });
}

function clsAlphaNoOnly (e) {  // Accept only alpha numerics, no special characters 
    var regex = new RegExp("^[a-zA-Z0-9 ]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }

    e.preventDefault();
    return false;
}

function getFilename(path) {
    return path.replace(/^.*[\\\/]/, '');
}

function getCurrentElementCss() {
    var position = $('#' + currentElement.id).position();
    var percentLeft = roundPercent(position.left /  $('#backgroundImage').width() * 100);
    var percentTop = roundPercent(position.top /  $('#backgroundImage').height() * 100);
    var percentWidth = roundPercent($('#' + currentElement.id).width() / $('#backgroundImage').width() * 100);
    var percentHeight = roundPercent($('#' + currentElement.id).height() / $('#backgroundImage').height() * 100);
    const css = {
        top: percentTop + '%',
        left: percentLeft + '%',
        width: percentWidth + '%',
        height: percentHeight + '%',
        transform: 'rotate('+ $('#rotateSlider').slider("option", "value") +'deg)'
    }

    console.log('CSS:', css);
    return css;
}

function resetCurrentVars() {
    currentMainMenuItemIndex = -1;
    currentElementIndex = -1;
    currentModalIndex = -1;
    currentRouteIndex = -1;
    currentMainMenuItem = {};
    currentElement = {};
    currentModalConfig = {};
    currentRouteConfig = {};
}


function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}
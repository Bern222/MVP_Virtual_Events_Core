var sessionResults = [];
var sessionSearchString = "";


$(document).ready(function(){
    $("#sessionSearchInput").on("input", function(){
        searchSessions();
    });
});

function openSessionSearchModal() {
    gaEvent(enumEvents.category.MAIN_MENU, enumEvents.action.OPEN_SESSION_SEARCH);

	sessionResults = [];
	sessionSearchString = "";
	$('.session-list-container').empty();
    $('.session-list-container').append('<div class="no-sessions-found">No sesssions found</div>');
    
    $.fancybox.open({
        src  : '#sessionSearchModal',
        type : 'inline',
        animationEffect: "zoom",
        animationDuration: modalFadeTime,
        opts : {
            touch: false
        }
    });
}

function searchSessions() {
	sessionSearchString = $('#sessionSearchInput').val();
	// if (sessionSearchString.length > 3) {
    if (sessionSearchString.length != 0) {
		request = $.post("components/session-search/sessionSearchData.php", {method: 'getSearchResults', searchString: sessionSearchString});
		request.done(function (response, textStatus, jqXHR) {
			if (response) {
                sessionResults = JSON.parse(response);
                if(sessionResults.length > 0) {
				    $('.session-list-container').empty();
                    for(var i=0; i<sessionResults.length; i++) {
                        var sessionSearchEntry 	= 	'<div class="session-search-entry" onclick="openSessionFromSearch(\'' + sessionResults[i].session_id + '\', \'' + sessionResults[i].session_name + '\')">'
                        sessionSearchEntry 		+=		'<img class="video-thumb" src="images/video-thumb.jpg">';
                        sessionSearchEntry 		+=		'<div class="session-search-entry-title">' + sessionResults[i].session_name + '</div>';
                        sessionSearchEntry 		+=	'</div>';
                        $('.session-list-container').append(sessionSearchEntry);
                    }
                } else {
                    emptySessionContainer();
                }
			} else {
				emptySessionContainer();
			}
		});
	} else {
        emptySessionContainer();
    }
}

function emptySessionContainer() {
    $('.session-list-container').empty();
    $('.session-list-container').append('<div class="no-sessions-found">No sesssions found</div>');
}

function openSessionFromSearch(uuid, sessionName) {
    gaEvent(enumEvents.category.MAIN_MENU, enumEvents.action.OPEN_SESSION_SEARCH + ': ' + sessionName);
    
    // Located in session component 
    openSession('notification', uuid);    
}
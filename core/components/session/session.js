
var allSessions = [];

$(document).ready(function () {
    // TODO: needs to move to init.js if used
    // getAllSessions();
});

function getAllSessions() {
    request = $.post("components/session/sessionData.php", {method: 'getAllSessions'});
	request.done(function (response, textStatus, jqXHR) {
		if (response) {
            allSessions = JSON.parse(response);
		}
	});
}

function openSession(type, uuid) {
    switch(type) {
        case 'notification':
            hideNotification(uuid);
        break;
        case 'search':

        break;
        case 'agenda':
            $.fancybox.close();
        break;
    }

    request = $.post("components/session/sessionData.php", {method: 'getSession', classKey: uuid});
	request.done(function (response, textStatus, jqXHR) {
		if (response) {
            var session = JSON.parse(response);
            console.log(session[0].STREAM_EMBED);
            

            switch(session[0].ROOM){
                case 'Ballroom':
                    fadeIn("grandBallroom",false);
                    break;
                case 'Seminar Room 1':
                    fadeIn("seminar1",false);
                    break;
                case 'Seminar Room 2':
                    fadeIn("seminar2",false);
                    break;
                case 'Seminar Room 3':
                    fadeIn("seminar3",false);
                    break;
                case 'Seminar Room 4':
                    fadeIn("seminar4",false);
                    break;
                case 'Seminar Room 5':
                    fadeIn("seminar5",false);
                    break;
                case 'Seminar Room 6':
                    fadeIn("seminar6",false);
                    break;
                case 'Seminar Room 7':
                    fadeIn("seminar7",false);
                    break;
                case 'Blacks in the Military Tribute Hall':
                    fadeIn("militaryTributeHall",false);
                    break;
                case 'Session Room 2':
                    fadeIn("seminar2",false);
                    break;
                case 'STEMulating Lounge':
                    fadeIn("stemulatingLounge",false);
                    break;
                case 'AMIE VIP Room':
                    fadeIn("amie-networking-lounge",false);
                    break;
                case 'Networking Lounge':
                    fadeIn("networkingLounge",false);
                    break;
                default:
                    
                break;
            }
            //$.fancybox.close();
            currentSessions = session;
            if(session[0].STREAM_EMBED !=""){
                openDynamicVideoPlayerModal(session[0].TITLE.replace(/'/g, "\\'"),0);
            }
            //openIframeContent(session[0].STREAM_EMBED);
            // TODO: verifiy what opens when these and other sessions are clicked (VOD_URL vs SESSION_LINK)
            // openDynamicVODVideoPlayerModal('Artificial Intelligence: The Benefits and Risks of Machine Learning', session.SESSION_LINK, 0);
		} else {
            alert ('Session not found');
        }
	});
}
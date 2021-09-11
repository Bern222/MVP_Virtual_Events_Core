/* Compoent Notes: 

- notificationSessions is generated from the sessions component sessions.js file

*/

// Number of minutes before session begins to show notification
var notificationInterval = 5;



// Number of seconds before hiding notification
var hideInterval = 10;

var shiftCount = 0;
var notificationSessions = [];
var currentServerTimestamp;

$(document).ready(function () {
    
    // TODO: Needs to move to init.js if used
    // getnotificationSessions();
    // setInterval( function() {
	// 	checkNotificationsTime();
	// }, 60000);
});

function getnotificationSessions() {
    request = $.post("components/session/sessionData.php", {method: 'getAllSessions'});
	request.done(function (response, textStatus, jqXHR) {
		if (response) {
            notificationSessions = JSON.parse(response);
            checkNotificationsTime();
		}
	});
}

function checkNotificationsTime() {
	$.ajax({
	 	url:"components/notification/notificationData.php",
	 	method:"POST",
	 	data:{method:'getServerTimestamp'},
	 	success: function(response){	
            currentServerTimestamp = parseInt(response);	
            startNextShiftLoop(); 		
	 	},
	 	error: function(XMLHttpRequest, textStatus, errorThrown) { 
	 		console.log("Status: " + textStatus); 
	 		console.log("Error: " + errorThrown); 
		} 
	});
}

function startNextShiftLoop() {
    if(notificationSessions && notificationSessions.length > 0 && notificationSessions.length >= shiftCount) {
        // Check for interval min before
        var sessionTime = parseInt(notificationSessions[shiftCount].CLASS_START_TIMESTAMP);
                
        console.log('ST:', currentServerTimestamp, sessionTime);

        if (currentServerTimestamp < sessionTime) {
            var intervalServerTimestamp = currentServerTimestamp + notificationInterval * 60;

            if (intervalServerTimestamp > parseInt(sessionTime)) {
                // Show the notification bar
                showNotification(notificationSessions[shiftCount]);
                hideNotification(notificationSessions[shiftCount].CLASS_KEY);
    
                // Check for more at that time
                notificationSessions.shift();
                shiftCount++;
                startNextShiftLoop();
            }
        } else { 
            // Remove that session from the array so we don't get duplicates and loop
            notificationSessions.shift();
            shiftCount++;
            startNextShiftLoop();
        }
    }
}

function showNotification(session) {
    
    // TODO: VOD or SESSION LINK 
    var notificationHTML     =  '<div id="notificationWindow' + session.CLASS_KEY + '" class="notification-container">';
    notificationHTML        +=      '<div id="' + session.CLASS_KEY + '" class="notification-window">';
    notificationHTML        +=          '<div class="notification-window-close-button" onclick="hideNotificationClick(\'' + session.CLASS_KEY + '\')">X</div>';
    notificationHTML        +=          '<div class="notificaton-information-container" onclick="openSessionFromNotification(\'' + session.CLASS_KEY + '\', \'' + session.TITLE + '\')">';
    notificationHTML        +=              '<div class="notificaton-title">' + session.TITLE +'</div>';
    notificationHTML        +=              '<div class="notification-time">Session begins in less than ' + notificationInterval + ' minutes</div>';
    notificationHTML        +=          '</div>';
    notificationHTML        +=      '</div>';
    notificationHTML        +=  '</div>';

    $('.notifications-container').append(notificationHTML);

    $('#' + session.CLASS_KEY).animate({
        opacity: 1,
        right: "+=320",
      }, 1000, function() {
        // Animation complete.
    });
}

function hideNotification(uuid) {
    setTimeout( function() {
        $('#' + uuid).animate({
            opacity: 0,
            right: "-=320",
          }, 400, function() {
              // On Complete
              $('#notificationWindow' + uuid).remove();
        });
    }, hideInterval * 1000);
}

function hideNotificationClick(uuid) {
    $('#' + uuid).animate({
        opacity: 0,
        right: "-=320",
        }, 400, function() {
            // On Complete
            $('#notificationWindow' + uuid).remove();
    });
}

function openSessionFromNotification(uuid, sessionName) {
    // TODO: should this pass the path or the sesssion name
    gaEvent(enumEvents.category.GENERAL, enumEvents.action.OPEN_SESSION_NOTIFICATION + ': ' + sessionName);
    // Located in session component 
    openSession('notification', uuid);    
}






// TODO: Test For notification functionality without time
function testShowNotification() {
    // Show the notification bar
    showNotification(notificationSessions[0]);

    startHideTimeout(notificationSessions[0].CLASS_KEY);

    // Remove that session from the array so we don't get duplicates
    notificationSessions.shift();
}

function startHideTimeout(uuid) {
    setTimeout( function() {
        hideNotification(uuid);
    }, 2000);
}

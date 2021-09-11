/* Compoent Notes: 

- allSessions var is generated from the sessions component sessions.js file

*/

var bookmarkedSessions = [];
var agendaHTML = '';
var currentHeader;
var bookmarkHTML = '';

const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

function openAgenda() {

    // Definied in Bookmark Component
    // TODO: figure out some sort of promise system for refreshing this before creating the entries
    //getBookmarkedSessions();

    createAgendaSessionEntries();
    
    $.fancybox.close(); 
    
    $.fancybox.open({
        src  : '#agendaModal',
        type : 'inline',
        animationEffect: 'zoom',
        animationDuration: modalFadeTime,
        opts : {
            touch: false
        },
        afterClose: function() {
        }
    });
}

function agendaTabSelected(type) {
    $('.tab').removeClass('tab-selected');
    $('.' + type).addClass('tab-selected');
    $('.agenda-no-sessions-found').css('display', 'none');
    switch(type) {
        case 'all-sessions-tab':
            $('.not-bookmarked').css('display', 'flex');
            $('.header-has-no-bookmarks').css('display', 'block');
            break;
        case 'bookmarked-sessions-tab':
            $('.not-bookmarked').css('display', 'none');
            $('.header-has-no-bookmarks').css('display', 'none');

            if (bookmarkedSessions && bookmarkedSessions.length == 0) {
                $('.agenda-no-sessions-found').css('display', 'flex');
            }
            break;
    }
}

function createAgendaSessionEntries() {
    agendaHTML = "";
    var currentDate;

    $('.agenda-session-list-container').empty();
    $('.agenda-session-list-container').append('<div class="agenda-no-sessions-found">No Sessions Bookmarked</div>');
    console.log('ALL SESSIONS:', allSessions);
    
    if (allSessions && allSessions.length > 0) {   
        $('.agenda-no-sessions-found').css('display', 'none');
        currentDate = new Date(allSessions[0].CLASS_START);
        agendaHTML += '<div id="' + currentDate.getTime() + '" class="agenda-date-header header-has-no-bookmarks">' + formatDate(currentDate) + '</div>';
        currentHeader = currentDate.getTime();

        for (var i=0;i<allSessions.length;i++) {
            var tempFullDateStart = new Date(allSessions[i].CLASS_START); 
            var tempFullDateEnd = new Date(allSessions[i].CLASS_END); 
            var newDate = new Date(tempFullDateStart.getFullYear(), tempFullDateStart.getMonth(), tempFullDateStart.getDate());
            var currentDateWithoutTime = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
            
            if (newDate > currentDateWithoutTime) {
                currentDate = tempFullDateStart;
                agendaHTML += '<div id="' + currentDate.getTime() + '" class="agenda-date-header header-has-no-bookmarks">' + formatDate(currentDate) + '</div>';
                currentHeader = currentDate.getTime();
            }
           
            agendaHTML +=   '<div class="agenda-session-entry ' + getBookmarkClass(allSessions[i].CLASS_KEY) + '" onclick="openSessionFromAgenda(\'' + allSessions[i].CLASS_KEY + '\', \'' + allSessions[i].TITLE.replace(/'/g, "\\'") + '\')">';
            agendaHTML +=       '<div class="agenda-session-time-container">';
            agendaHTML +=           bookmarkHTML;
            agendaHTML +=           '<img class="agenda-session-clock-image" src="images/clock.png"/>';
            agendaHTML +=           '<div class="agenda-session-time">' + formatTime(tempFullDateStart) + ' - ' + formatTime(tempFullDateEnd) +'</div>';
            agendaHTML +=       '</div>';
            agendaHTML +=       '<div class="agenda-session-title-container">';
            agendaHTML +=           '<div class="agenda-session-title">' + allSessions[i].TITLE + '</div>';
            agendaHTML +=           '<div class="agenda-session-room">(' + allSessions[i].ROOM + ')</div>';
            agendaHTML +=       '</div>';
            agendaHTML +=   '</div>';

            $('.agenda-session-list-container').append(agendaHTML);
            agendaHTML = "";
        }
    } else {
        $('.agenda-no-sessions-found').css('display', 'block');
    }
}

function formatDate(date) {
    var formattedDate = dayNames[date.getDay()].toUpperCase() + ', ' + monthNames[date.getMonth()].toUpperCase() + ' ' + date.getDate() + ', ' + date.getFullYear();
    return formattedDate;
}

function formatTime(date) {
    const options = {  
        hour: "2-digit", minute: "2-digit"  
    };  
    
    return date.toLocaleTimeString("en-us", options); 
}

function getBookmarkClass(uuid) {
    bookmarkHTML = '';
    if (bookmarkedSessions && bookmarkedSessions.length > 0) {
        var found = false;
        for(var i=0;i< bookmarkedSessions.length; i++) {

            if(bookmarkedSessions[i].SESSION_CODE == uuid) {
                found = true;
                break;
            }
        }

        if(found) {
            $('#' + currentHeader).removeClass('header-has-no-bookmarks');
            bookmarkHTML = '<div class="bookmark-circle"></div>';

            return 'bookmarked';
        } else {
            bookmarkHTML = '';
            return 'not-bookmarked';
        }

    } else {
        bookmarkHTML = '';
        return 'not-bookmarked';
    }
}

function openSessionFromAgenda(uuid, sessionName) {
    // TODO: should this pass the path or the sesssion name
    gaEvent(enumEvents.category.AGENDA, enumEvents.action.OPEN_SESSION_AGENDA + ': ' + sessionName);
    
    // Located in session component 
    openSession('notification', uuid);    
}


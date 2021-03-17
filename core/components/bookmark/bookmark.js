var bookmarkedSessions = [];

function getBookmarkedSessions() {
    request = $.post('components/bookmark/bookmarkData.php', {method: 'getUserBookmarks'});
    request.done(function (response, textStatus, jqXHR) {
        console.log('BOOKMARKED SESSIONS', response);
        if (response) {
            bookmarkedSessions = JSON.parse(response);
        }
    });
}
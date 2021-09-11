var bookmarkedSessions = [];

function getBookmarkedSessions() {
    request = $.post('components/bookmark/bookmarkData.php', {method: 'getUserBookmarks'});
    request.done(function (response, textStatus, jqXHR) {
        if (response) {
            bookmarkedSessions = JSON.parse(response);
        }
    });
}
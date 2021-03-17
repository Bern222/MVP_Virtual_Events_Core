// Notificatiions ------------------------------------------------------------

function toggleShowNotifications() {
	var showNotifications = false;

	if ($('#showNotificationCheckbox').is(':checked')) {
		showNotifications = true;
	}

	console.log('CURRENT USER:', currentUser);
	if (currentUser) {
		request = $.post("components/users/userData.php", {method: 'updateUserNotifications', userId: currentUser.id, showNotifications: showNotifications});
		request.done(function (response, textStatus, jqXHR) {
			if (response && response == 'success') {
				currentUser.show_notifications = showNotifications
			} else {
				alert ('Error');
			}
		});
	}
}
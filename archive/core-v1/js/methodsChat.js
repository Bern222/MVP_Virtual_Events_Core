// TODO Revisit All below
function openChat(room) {
	$("#chatDropdown").show();
	$("#userChatButton").hide();
	$("#chatDropdown").empty();
	$('#chatframe').attr('src','about:blank');
	// $('.icon-chat-small').hide();
	if(chatZoomId != '') {
		$('#videoChatButton').show();
	} else {
		$('#videoChatButton').hide();
	}
	getChatRooms(room);
}

function getChatRooms(room) {
	gaEvent(enumEvents.category.CHAT, enumEvents.action.OPEN_CHAT_ROOM + ': Room - ' + room);
	request = $.post("boothData.php", {method: "getChatRoomsByRoomName", roomName: room});
	request.done(function (response, textStatus, jqXHR) {
		currentChatRooms = JSON.parse(response);
		if (currentChatRooms.length > 0) {
			currentChatRoomId = currentChatRooms[0].id;
			
			// if (type == 'edit') {
			// 	refreshEditChatRooms();
			// } else {
				refreshChatRooms();
			// }
		} else {
			// if (type == 'edit') {
			// 	refreshEditChatRooms();
			// } else {
				alert('No chat rooms available');
			// }
		}
	});
}

function refreshChatRooms() {
	for (var i=0; i < currentChatRooms.length; i++){
		var chatRoom = currentChatRooms[i];
	// for(chatRoom of currentChatRooms) {
		$("#chatDropdown").append("<option value='" + chatRoom.id + "'>" + chatRoom.name + "</option>");
	}

	toggleChatRoom();
	$("#chatwindow").css({ opacity: 0 });
	$('#chatwindow').show();
	$('#chatframe').show();
	$('#chatwindow').fadeTo(1000, 1);
	// $('#chatframe').attr('src', 'libs/chatRoom/index.php?userid=' + currentUserId + '&username=' + currentUsername + '&type=room' + '&chatRoomId=' + currentChatRoomId);

	if (sponsorAdminId && sponsorAdminId == currentBoothId) {
		$('#adminChatButton').show();
		$('#userChatButton').hide();
	} else {
		$('#adminChatButton').hide();
		// checkSponsorMessages();
	}
}

function toggleChatRoom() {
	hideAllVideoChat();
	$(".room-dropdown").show();
	$('#chatframe').attr('src', 'libs/chatRoom/index.php?userid=' + currentUserId + '&username=' + currentUsername + '&type=room' + '&chatRoomId=' + currentChatRoomId);
	$('#chatframe').show();
}

function togglePrivateChatRoom(userType) {
	hideAllVideoChat();
	$(".room-dropdown").show();
	$('#chatframe').show();
	$('#chatframe').attr('src', 'libs/chat/index.php?userid=' + currentUserId + '&username=' + currentUsername + '&boothId=' + currentBoothId  + '&userType=' + userType);
}

// TODO: Rework
function toggleVideoChat() {
	// if (currentBoothData.video_meeting_Url && currentBoothData.video_meeting_Url.search('zoom') != -1) {
	// 	toggleZoom();
	// } else if (currentBoothData.video_meeting_Url && currentBoothData.video_meeting_Url.search('teams') != -1) {
	// 	toggleTeams();
	// } else if (currentBoothData.video_meeting_Url && currentBoothData.video_meeting_Url.search('webex') != -1) {
	// 	toggleWebex();
	// } else if (currentBoothData.video_meeting_Url && currentBoothData.video_meeting_Url.search('meet.google') != -1) {
	// 	toggleGoogleMeet();
	// }

	toggleZoom();
}

function hideAllVideoChat() {
	$(".room-dropdown").hide();
	$('#chatframe').hide();
	$('#zoomContainer').hide();
	$('#teamsContainer').hide();
	$('#webexContainer').hide();
	$('#googleMeetContainer').hide();
}

function toggleZoom() {
	hideAllVideoChat();
	$('#zoomContainer').show();
}

function toggleTeams() {
	hideAllVideoChat();
	$('#teamsContainer').show();
}

function toggleWebex() {
	hideAllVideoChat();
	$('#webexContainer').show();
}

function toggleGoogleMeet() {
	hideAllVideoChat();
	$('#googleMeetContainer').show();
}

function togglePrivateMessage(userType) {
	$(".room-dropdown").hide();
	$('#chatframe').show();
	togglePrivateChatRoom(userType)}

function closeChatWindnow() {
	window.clearInterval();
	$('#chatwindow').hide();
	$('#chatframe').attr('src', 'about:blank');
}

function setupChatWindowDrag() {
	dragElement(document.getElementById("chatwindow"));
}

function dragElement(elmnt) {
	if ($('#' + elmnt.id + 'header')) {
		var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
		if (document.getElementById(elmnt.id + "header")) {
		/* if present, the header is where you move the DIV from:*/
		document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
		} else {
		/* otherwise, move the DIV from anywhere inside the DIV:*/
		elmnt.onmousedown = dragMouseDown;
		}
	
		function dragMouseDown(e) {
		e = e || window.event;
		e.preventDefault();
		// get the mouse cursor position at startup:
		pos3 = e.clientX;
		pos4 = e.clientY;
		document.onmouseup = closeDragElement;
		// call a function whenever the cursor moves:
		document.onmousemove = elementDrag;
		}
	
		function elementDrag(e) {
		e = e || window.event;
		e.preventDefault();
		// calculate the new cursor position:
		pos1 = pos3 - e.clientX;
		pos2 = pos4 - e.clientY;
		pos3 = e.clientX;
		pos4 = e.clientY;
		// set the element's new position:
		elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
		elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
		}
	
		function closeDragElement() {
		/* stop moving when mouse button is released:*/
		document.onmouseup = null;
		document.onmousemove = null;
		}
	}
}
  
  function updateChat(chatRoomId) {
	  if(chatRoomId) {
		  currentChatRoomId= chatRoomId;
		  toggleChatRoom();
	  }
  }
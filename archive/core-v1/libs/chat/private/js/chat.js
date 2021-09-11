var onlineUsersOnly = true;
var currentActiveId = 0;
var intialLoad = true;
var previousSelectedUserId = 0;
var newMessageUserCount = 0;

$(document).ready(function(){
	setStyles();
	updateUserList();
	setInterval(function(){
		updateUserList();	
		updateUnreadMessageCount();	
		if (userType == 'user') {
			checkAdminStatus();
		}
	}, 20000);	
	setInterval(function(){
		// showTypingStatus();
		updateUserChat();			
	}, 5000);
	$(".messages").animate({ 
		scrollTop: $('.messages').prop('scrollHeight')
	}, "fast");
	$(document).on("click", '#profile-img', function(event) { 	
		$("#status-options").toggleClass("active");
	});
	$(document).on("click", '.expand-button', function(event) { 	
		$("#profile").toggleClass("expanded");
		$("#contacts").toggleClass("expanded");
	});	
	$(document).on("click", '#status-options ul li', function(event) { 	
		$("#profile-img").removeClass();
		$("#status-online").removeClass("active");
		$("#status-away").removeClass("active");
		$("#status-busy").removeClass("active");
		$("#status-offline").removeClass("active");
		$(this).addClass("active");
		$('.icon-chat-medium').hide();
		if($("#status-online").hasClass("active")) {
			$("#profile-img").addClass("online");
		} else if ($("#status-away").hasClass("active")) {
			$("#profile-img").addClass("away");
		} else if ($("#status-busy").hasClass("active")) {
			$("#profile-img").addClass("busy");
		} else if ($("#status-offline").hasClass("active")) {
			$("#profile-img").addClass("offline");
		} else {
			$("#profile-img").removeClass();
		};
		$("#status-options").removeClass("active");
	});	
	$(document).on('click', '.contact', function(){		
		$('.contact').removeClass('active');
		$(this).addClass('active');
		var to_user_id = $(this).data('touserid');
		checkAllNewMessageStatus();
		$('#' + currentActiveId + 'NewChatMessage').hide();
		currentActiveId = to_user_id;
		$('#' + currentActiveId + 'NewChatMessage').hide();
		showUserChat(to_user_id);
		$(".chatMessage").attr('id', 'chatMessage'+to_user_id);
		$(".chatButton").attr('id', 'chatButton'+to_user_id);
	});	
	$(document).on("click", '.submit', function(event) { 
		var to_user_id = $(this).attr('id');
		to_user_id = to_user_id.replace(/chatButton/g, "");
		sendMessage(to_user_id);
	});
	$(document).on('focus', '.message-input', function(){
		var is_type = 'yes';
		$.ajax({
			url:"chat_action.php",
			method:"POST",
			data:{is_type:is_type, action:'update_typing_status'},
			success:function(){
			}
		});
	}); 
	$(document).on('blur', '.message-input', function(){
		var is_type = 'no';
		$.ajax({
			url:"chat_action.php",
			method:"POST",
			data:{is_type:is_type, action:'update_typing_status'},
			success:function() {
			}
		});
	}); 		
}); 

function setStyles() {
	// if (userType == 'user') {
	// 	$('#sidepanel').hide();
	// 	$('.content').width('100%');
	// } else {
	// 	$('#sidepanel').show();
	// 	$('.content').width('60%');
	// }
}

function initalSelection(toUserId) {
	$('.contact').removeClass('active');
	$('#' + toUserId).addClass('active');
	checkAllNewMessageStatus();
	showUserChat(toUserId);
	$(".chatMessage").attr('id', 'chatMessage'+toUserId);
	$(".chatButton").attr('id', 'chatButton'+toUserId);
}

function checkAllNewMessageStatus() {
	if (!$('#' + currentActiveId + 'NewChatMessage').is(":hidden")) {
		newMessageUserCount--;
	}

	if (newMessageUserCount <= 0) {
		parent.removeNewMessageStatus();
	}
}

function checkAdminStatus() {
	$.ajax({
		url:"chat_action.php",
		method:"POST",
		dataType: "json",
		data:{action:'check_admin_status'},
		success:function(response){		
			if (response && response.length > 0) {
				var user = reponse[0];
				if (user.online_booth_id == boothId) {
					var last_booth_date = new Date(user.online_booth_timestamp);
					var duration = 5 * 60000;
					if (last_booth_date >= new Date() - duration) {
						$('.status-indicator').style('background-color', 'green');
						$('.status-indicator').text('Online');
					} else {
						$('.status-indicator').style('background-color', 'red');
						$('.status-indicator').text('Offline');
					}
				}
			}

		}
	});
}

function updateUserList() {
	$.ajax({
		url:"chat_action.php",
		method:"POST",
		dataType: "json",
		data:{action:'update_user_list', userType: userType},
		success:function(response){		
			$(".chat-contacts").empty();
			// for(var i=0;i<response.length;i++) {
				
			// }

			// echo '<li id="'.$user['id'].'" class="contact '.$activeUser.'" data-touserid="'.$user['id'].'" data-tousername="'.$user['chat_username'].'">';
			// 				echo '<div class="wrap">';
			// 				echo '<span id="status_'.$user['id'].'" class="contact-status '.$status.'"></span>';
			// 				echo '<img src="userpics/'.$user['avatar'].'" alt="" />';
			// 				echo '<div class="meta">';
			// 				echo '<p class="name">'.$user['chat_username'].'<span id="unread_'.$user['id'].'" class="unread">'.$chat->getUnreadMessageCount($user['id'], $_SESSION['userid']).'</span></p>';
			// 				echo '<p class="preview"><span id="isTyping_'.$user['id'].'" class="isTyping"></span></p>';
			// 				echo '</div>';
			// 				echo '</div>';
			// 				echo '</li>'; 


			// 				<li id="92" class="contact " data-touserid="92" data-tousername="Sponsor4Admin"><div class="wrap"><span id="status_92" class="contact-status offline"></span><img src="userpics/user1.jpg" alt=""><div class="meta"><p class="name">Sponsor4Admin<span id="unread_92" class="unread"></span></p><p class="preview"><span id="isTyping_92" class="isTyping"></span></p></div></div></li>

			var obj = response.profileHTML;
			var firstId = true;
			if(obj && obj.length > 0) {	
				//for(var i=0; i<users.length; i++){
				
				Object.keys(obj).forEach(function(key) {
					if(firstId) {
						firstId = false;
						currentActiveId = obj[key].id;
					}

					$.ajax({
						url:"../../boothData.php",
						method:"POST",
						dataType: "json",
						data:{method:'checkNewChatMessagesPerUser', userId: userId, sponsorId: obj[key].id, boothId: boothId},
						success:function(response){	
							var active  = '';
							if (currentActiveId == obj[key].id) {
								active = 'active';
							}

							status = 'offline';
							if (obj[key].online_booth_id == boothId && new Date(obj[key].online_booth_timestamp) >= new Date() - 5 * 60000) {
								status = 'online';
							}

							$(".chat-contacts").append('<li id="' + obj[key].id +'" class="contact ' + active + '" data-touserid="'+ obj[key].id +'" data-tousername="' + obj[key].chat_username +'"><div class="wrap"><span id="status_' + obj[key].id +'" class="contact-status ' + status + '"></span><img src="userpics/' + obj[key].avatar +'" alt="" /><div class="meta"><p class="name user-list-name">' + obj[key].chat_username + '<span id="unread_' + obj[key].id + '" class="unread"></span></p><p class="preview"><span id="isTyping_' + obj[key].id + '" class="isTyping"></span></p></div></div><div class="chat-profile-button"><img id="' + obj[key].id + 'NewChatMessage" class="icon-chat-medium" src="../../images/icon-chat-new.png"/></div></li>');
							
							
							if (response && response.length > 0) {
								newMessageUserCount++;
								$('#' + obj[key].id + "NewChatMessage").show();
							} else {
								$('#' + obj[key].id + "NewChatMessage").hide();
							}
							
							// $(".chat-contacts").append('<div class="wrap">');
							// $(".chat-contacts").append('<span id="status_' + obj[key].id +'" class="contact-statusonline"></span>');
							// $(".chat-contacts").append('<img src="userpics/' + obj[key].avatar +'" alt="" />');
							// $(".chat-contacts").append('<div class="meta">');
							// $(".chat-contacts").append('<p class="name">' + obj[key].chat_username + '<span id="unread_' + obj[key].id + '" class="unread"></span></p>');
							// $(".chat-contacts").append('<p class="preview"><span id="isTyping_' + obj[key].id + '" class="isTyping"></span></p>');
							// $(".chat-contacts").append('</div>');
							// $(".chat-contacts").append('</div>');
							// $(".chat-contacts").append('</li>');

							// // update user online/offline status
							// if($("#"+obj[key].userid).length) {
							// 	if(obj[key].online == 1 && !$("#status_"+obj[key].userid).hasClass('online')) {
							// 		$("#status_"+obj[key].userid).addClass('online');
							// 	} else if(obj[key].online == 0){
							// 		$("#status_"+obj[key].userid).removeClass('online');
							// 	}
							// }		
				
						}
					});	
				});		
				if(intialLoad) {
					initalSelection(currentActiveId);
				}
			}	
		}
	});
}
function sendMessage(to_user_id) {
	message = $(".message-input input").val();
	$('.message-input input').val('');
	if($.trim(message) == '') {
		return false;
	}

	var words = message.split(" ");

	var forLoopStartTime = new Date();

	var found = false;
	for(var i=0;i<words.length;i++) {
		for(var k=0;k<wordFilterArray.length;k++) {
			if(words[i] == wordFilterArray[k]) {
				found = true;
				k = wordFilterArray.length;
				i = words.length; 
			}
		}
	}
	
	if(found) {
		parent.showProfanityModal();
	} else {
		$.ajax({
			url:"chat_action.php",
			method:"POST",
			data:{to_user_id:to_user_id, chat_message:message, action:'insert_chat', booth_id: boothId},
			dataType: "json",
			success:function(response) {
				$('#conversation').html(response.conversation);				
				$(".messages").animate({ scrollTop: $('.messages').prop('scrollHeight') }, "fast");
			}
		});	
	}
}
function showUserChat(to_user_id){
	intialLoad = false;
	$.ajax({
		url:"chat_action.php",
		method:"POST",
		data:{to_user_id:to_user_id, action:'show_chat'},
		dataType: "json",
		success:function(response){
			$('.profile-empty').hide();
			$('#userSection').html(response.userSection);
			$('#conversation').html(response.conversation);	
			$('#unread_'+to_user_id).html('');
			removeNewMessageStatus(to_user_id);
			$(".messages").animate({ 
				scrollTop: $('.messages').prop('scrollHeight')
			}, "fast");
		}
	});
}
function removeNewMessageStatus(sender_userid) {
	$.ajax({
		url:"chat_action.php",
		method:"POST",
		data:{sender_userid: sender_userid, action:'remove_new_message_status'},
		dataType: "text",
		success:function(response){
			// $('.profile-empty').hide();
			// $('#userSection').html(response.userSection);
			// $('#conversation').html(response.conversation);	
			// $('#unread_'+to_user_id).html('');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
    	}  
	});
}


function updateUserChat() {
	$('li.contact.active').each(function(){
		var to_user_id = $(this).attr('data-touserid');
		$.ajax({
			url:"chat_action.php",
			method:"POST",
			data:{to_user_id:to_user_id, action:'update_user_chat'},
			dataType: "json",
			success:function(response){				
				$('#conversation').html(response.conversation);			
			}
		});
	});
}
function updateUnreadMessageCount() {
	$('li.contact').each(function(){
		if(!$(this).hasClass('active')) {
			var to_user_id = $(this).attr('data-touserid');
			$.ajax({
				url:"chat_action.php",
				method:"POST",
				data:{to_user_id:to_user_id, action:'update_unread_message'},
				dataType: "json",
				success:function(response){		
					if(response.count) {
						$('#unread_'+to_user_id).html(response.count);	
					}					
				}
			});
		}
	});
}
function showTypingStatus() {
	$('li.contact.active').each(function(){
		var to_user_id = $(this).attr('data-touserid');
		// $.ajax({
		// 	url:"chat_action.php",
		// 	method:"POST",
		// 	data:{to_user_id:to_user_id, action:'show_typing_status'},
		// 	dataType: "json",
		// 	success:function(response){				
		// 		$('#isTyping_'+to_user_id).html(response.message);			
		// 	}
		// });
	});
}


<?php 
session_start();
include('header.php');
//include ('Chat.php');
//$chat = new Chat();
$_SESSION['userid'] = $_GET['userid'];
$_SESSION['username'] = $_GET['username'];
$_SESSION['roomtype'] = $_GET['roomtype'];
$_SESSION['userType'] = $_GET['userType'];
$_SESSION['boothAdminId'] = $_GET['boothAdminId'];

if ($_GET['boothId']) {
	$_SESSION['boothId'] = $_GET['boothId'];
}


//$_SESSION['userid'] = $_GET['userId'];
//$chat->updateUserOnline($_GET['userId'], 1);
//$lastInsertId = $chat->insertUserLoginDetails($_GET['userId']);
//$_SESSION['login_details_id'] = $lastInsertId;
?>
<title></title>
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
<link href="css/style.css" rel="stylesheet" id="bootstrap-css">
<script>
	var userType = "<?php echo $_SESSION['userType']; ?>";
	var boothId = "<?php echo $_SESSION['boothId']; ?>";
	var userId = "<?php echo $_SESSION['userid']; ?>";
</script>

<script src="js/chat.js"></script>
<script src="js/array.js"></script>
<style>
	.modal-dialog {
    	width: 400px;
    	margin: 30px auto;	
	}

	.social-media {
		display: none;
	}

	#bottom-bar {
		display: none;
	}
</style>
<?php include('container.php');?>
<div class="container">
	<?php if(isset($_SESSION['userid']) && $_SESSION['userid']) { ?> 	
		<div class="chat">	
			<div id="frame">		
				<div id="sidepanel">
					<div id="profile">
					<?php
					include ('Chat.php');
					$chat = new Chat();
					$chat->updateUserOnline($_GET['userId'], 1);
					$loggedUser = $chat->getUserDetails($_SESSION['userid']);
					echo '<div class="wrap">';
					$currentSession = '';
					foreach ($loggedUser as $user) {
						$currentSession = $user['chat_current_session'];
						echo '<img id="profile-img" src="userpics/'.$user['avatar'].'" class="online" alt="" />';
						echo  '<p>'.$user['chat_username'].'</p>';
							echo '<i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>';
							// echo '<div id="status-options">';
							// echo '<ul>';
							// 	echo '<li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>';
							// 	echo '<li id="status-away"><span class="status-circle"></span> <p>Away</p></li>';
							// 	echo '<li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>';
							// 	echo '<li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>';
							// echo '</ul>';
							// echo '</div>';
							echo '<div id="expanded">';			
							echo '<a href="logout.php">Logout</a>';
							echo '</div>';
					}
					echo '</div>';
					?>
					</div>
					<div id="search">
						<label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
						<input type="text" placeholder="Search contacts..." />					
					</div>
					<div id="contacts">	
					<?php
					echo '<ul class="chat-contacts">';
					if ($_SESSION['userType'] == 'user') {
						$chatUsers = $chat->chatAdmins($_SESSION['userid']);
					} else {
						$chatUsers = $chat->adminChatUsers($_SESSION['userid']);
					}
					
					// foreach ($chatUsers as $user) {
					// 	$status = 'offline';						
					// 	if($user['online_booth_id'] == $_SESSION['boothId'] && strtotime($user['online_booth_timestamp']) >= time() - 5 * 60 * 1000) {
					// 		$status = 'online';
					// 	}
					// 	$activeUser = '';
					// 	if($user['id'] == $currentSession) {
					// 		$activeUser = "active";
					// 	}
					// 	// if($status == 'online') {
					// 		echo '<li id="'.$user['id'].'" class="contact '.$activeUser.'" data-touserid="'.$user['id'].'" data-tousername="'.$user['chat_username'].'">';
					// 		echo '<div class="wrap">';
					// 		echo '<span id="status_'.$user['id'].'" class="contact-status '.$status.'"></span>';
					// 		echo '<img src="userpics/'.$user['avatar'].'" alt="" />';
					// 		echo '<div class="meta">';
					// 		echo '<p class="name">'.$user['chat_username'].'<span id="unread_'.$user['id'].'" class="unread">'.$chat->getUnreadMessageCount($user['id'], $_SESSION['userid']).'</span></p>';
					// 		echo '<p class="preview"><span id="isTyping_'.$user['id'].'" class="isTyping"></span></p>';
					// 		echo '</div>';
					// 		echo '</div>';
					// 		echo '<div class="chat-profile-button"><img class="icon-chat-medium" src="../../images/icon-chat-new.png"/></div>';
					// 		echo '</li>'; 
					// //	}
					// }
					echo '</ul>';
					?>
					</div>
					<div id="bottom-bar">	
						<button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Add contact</span></button>
						<button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Settings</span></button>					
					</div>
				</div>			
				<div class="content" id="content"> 
					<div class="contact-profile" id="userSection">	
					<?php
					if ($_SESSION['roomType'] == 'user') {
						$userDetails = $chat->getUserDetails($_SESSION['boothAdminId']);
						foreach ($userDetails as $user) {										
							echo '<img src="userpics/'.$user['avatar'].'" alt="" />';
								echo '<div class="profile-container">';
								echo '<div class="profile-username">'.$user['chat_username'].'</div>';
								echo '<div class="profile-name">'.$user['firstname'].' '.$user['lastname'].'</div>';
								echo '</div>';
								echo '<div class="status-container">';
									echo '<div class="status-indicator"></div>';
									echo '<div class="status-text">Offline</div>';
								echo '</div>';
						}

					} else {
						if (count($chatUsers) > 0) {
							$userDetails = $chat->getUserDetails($currentSession);
							foreach ($userDetails as $user) {										
								echo '<img src="userpics/'.$user['avatar'].'" alt="" />';
									echo '<div class="profile-container">';
									echo '<div class="profile-username">'.$user['chat_username'].'</div>';
									echo '<div class="profile-name">'.$user['firstname'].' '.$user['lastname'].'</div>';
									// echo '<div class="profile-resume">Resume'.$user['']
									echo '</div>';
									if ($user['resume_filename'] && $user['resume_mime']) {
/*										echo '<div class="chat-resume" onclick="parent.openResume(\''.$user['resume_filename'].'\', \''.$user['resume_mime'].'\')">Open Resume</div>'; */
										echo '<div class="chat-resume" onclick="parent.openResume('.$user['id'].')">Open Resume</div>'; 
									}	
									// echo '<a href="downloadResume.php?resumeFilename=\''.$user['resume_filename'].'\'&resumeMime=\''.$user['resume_mime'].'\'">Download Resume</a>';
									echo '<div class="social-media">';
										// echo '<i class="fa fa-facebook" aria-hidden="true"></i>';
										// echo '<i class="fa fa-twitter" aria-hidden="true"></i>';
										// echo '<i class="fa fa-instagram" aria-hidden="true"></i>';
									echo '</div>';
							}	
						} else {

							echo '<div class="profile-empty" style="line-height: 60px; margin-left: 19px;">No User selected</div>';
						}
					}
					?>						
					</div>
					<div class="messages" id="conversation">		
					<?php
					if (count($chatUsers) > 0) {
						echo $chat->getUserChat($_SESSION['userid'], $currentSession);	
					}					
					?>
					</div>
					<div class="message-input" id="replySection">				
						<div class="message-input" id="replyContainer">
							<div class="wrap">
								<input type="text" class="chatMessage" id="chatMessage<?php echo $currentSession; ?>" placeholder="Write your message..." />
								<button class="submit chatButton" id="chatButton<?php echo $currentSession; ?>"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>	
							</div>
						</div>					
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<!-- <strong><a href="login.php"><h3>Login To Access Chat System</h3></a></strong>		 -->
	<?php } ?>
	<div style="margin:50px 0px 0px 0px;">
	</div>	
</div>	
<?php include('footer.php');?>
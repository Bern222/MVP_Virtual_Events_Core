<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


session_start();

require_once("../profanity/config/profanities.php");
require_once("../profanity/src/Check.php");

include ('Chat.php');
$chat = new Chat();
if($_POST['action'] == 'update_user_list') {
	$chatUsers;
	if($_POST['userType'] == 'user') {
		$chatUsers = $chat->chatAdmins($_SESSION['userid']);
	} else {
		$chatUsers = $chat->adminChatUsers($_SESSION['userid']);
	}

	// $chatUsers = $chat->chatUsers($_SESSION['userid']);
	$data = array(
		"profileHTML" => $chatUsers,	
	);
	echo json_encode($data);	
}
if($_POST['action'] == 'insert_chat') {
	/* default constructor */
	// $check = new Check();
	// $hasProfanity = $check->hasProfanity($badWords);
	// if ($hasProfanity) {
	// 	echo 'has-profanity: ' + $hasProfanity;
	// } else {
		$chat->insertChat($_POST['to_user_id'], $_SESSION['userid'], $_POST['chat_message'], $_POST['booth_id']);
	//}
}
if($_POST['action'] == 'show_chat') {
	$chat->showUserChat($_SESSION['userid'], $_POST['to_user_id']);
}
if($_POST['action'] == 'update_user_chat') {
	$conversation = $chat->getUserChat($_SESSION['userid'], $_POST['to_user_id']);
	$data = array(
		"conversation" => $conversation			
	);
	echo json_encode($data);
}
if($_POST['action'] == 'update_unread_message') {
	$count = $chat->getUnreadMessageCount($_POST['to_user_id'], $_SESSION['userid']);
	$data = array(
		"count" => $count			
	);
	echo json_encode($data);
}
if($_POST['action'] == 'update_typing_status') {
	$chat->updateTypingStatus($_POST["is_type"], $_SESSION["login_details_id"]);
}
if($_POST['action'] == 'show_typing_status') {
	$message = $chat->fetchIsTypeStatus($_POST['to_user_id']);
	$data = array(
		"message" => $message			
	);
	echo json_encode($data);
}

if($_POST['action'] == 'booth') {
	$_SESSION['userid'] = $_POST['userId'];
}

if($_POST['action'] == 'check_admin_status') {
	$message = $chat->getUserDetails($_POST['boothAdminId']);
	$data = array(
		"message" => $message			
	);
	echo json_encode($data);
}
if($_POST['action'] == 'remove_new_message_status') {
	$update = $chat->removeNewMessageStatus($_SESSION['userid'], $_POST['sender_userid']);
	// $data = array(
	// 	"conversation" => $conversation			
	// );
	echo $update;
}
?>
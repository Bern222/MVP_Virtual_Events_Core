<?php
class Chat{
	// require_once($root . "/core/common.php");
	// private $host = '127.0.0.1';
	// private $user = 'mvpvirtualevents_dbuser';
	// private $password = 'f1uOgykR$8#Z';
	// private $database = 'mvpvirtualevents_data';

// 	ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);
//    error_reporting(E_ALL);

	private $dbhost = '127.0.0.1';
	private $dbuser = 'womenofcoloronln_dbuser';
	private $dbpass = 'S!1SL$O,We;p';
	private $dbname = 'womenofcoloronln_data';
	private $userIds = [];
   
    private $chatTable = 'chat';
	private $chatUsersTable = 'users';
	private $chatLoginDetailsTable = 'chat_login_details';
	private $dbConnect = false;
    public function __construct(){
        if(!$this->dbConnect){ 
            $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error().' - '.$sqlQuery. ' - '. print_r($userIds));
		}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[]=$row;            
		}
		return $data;
	}
	private function getNumRows($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function loginUsers($username, $password){
		$sqlQuery = "
			SELECT id, chat_username 
			FROM ".$this->chatUsersTable." 
			WHERE chat_username='".$username."' AND password='".password_hash($password, PASSWORD_DEFAULT)."'";		
        return  $this->getData($sqlQuery);
	}		
	public function chatUsers($userid){
		$boothAnd = '';

		if ($_SESSION['boothId'] ) {
			$boothAnd = " AND online_booth_id=" . $_SESSION['boothId'] . " AND online_booth_timestamp >= NOW() - INTERVAL 5 MINUTE";
		}

		$sqlQuery = "
			SELECT * FROM ".$this->chatUsersTable." 
			WHERE id != '$userid'". $boothAnd;
		return  $this->getData($sqlQuery);
	}
	public function adminChatUsers($userid){
		$sqlQuery = "
			SELECT reciever_userid FROM ".$this->chatTable." 
			WHERE sender_userid = '".$userid."'
			AND chat_room_id IS NULL 
			ORDER BY timestamp ASC";

		$userIds = $this->getData($sqlQuery);

		$sqlQueryUsers = "
			SELECT * FROM ".$this->chatUsersTable." 
			WHERE id != ".$userid." AND ";

		if($userIds && count($userIds) > 0) {
			$sqlQueryUsers .= "(";

			for ($i = 0; $i < count($userIds); $i++) {
				$sqlQueryUsers .= "id = " . implode(" ",$userIds[$i]);

				if ($i + 1 != count($userIds)) {
					$sqlQueryUsers .= ' OR ';
				}
			}

			$sqlQueryUsers .= ") OR ";
		}

		$sqlQueryUsers .= "(online_booth_id=" . $_SESSION['boothId'] . " AND online_booth_timestamp >= NOW() - INTERVAL 5 MINUTE) AND id != ".$userid;
		
		return  $this->getData($sqlQueryUsers);
	}
	public function chatAdmins($userid){
		$sqlQuery = "
			SELECT * FROM ".$this->chatUsersTable." 
			WHERE id != ".$userid." AND admin_sponsor_id=".$_SESSION['boothId'];
		return  $this->getData($sqlQuery);
	}
	public function getUserDetails($userid){
		$sqlQuery = "
			SELECT * FROM ".$this->chatUsersTable." 
			WHERE id = '$userid'";
		return  $this->getData($sqlQuery);
	}
	public function getUserAvatar($userid){
		$sqlQuery = "
			SELECT avatar 
			FROM ".$this->chatUsersTable." 
			WHERE id = '$userid'";
		$userResult = $this->getData($sqlQuery);
		$userAvatar = '';
		foreach ($userResult as $user) {
			$userAvatar = $user['avatar'];
		}	
		return $userAvatar;
	}	
	public function updateUserOnline($userId, $online) {		
		$sqlUserUpdate = "
			UPDATE ".$this->chatUsersTable." 
			SET chat_online = '".$online."' 
			WHERE id = '".$userId."'";			
		mysqli_query($this->dbConnect, $sqlUserUpdate);		
	}
	public function removeNewMessageStatus($userId, $sender_id) {		
		$sqlChatUpdate = "
			UPDATE ".$this->chatTable." 
			SET viewed = 1 
			WHERE (sender_userid = '".$userId."' AND reciever_userid = '".$sender_id."') OR (sender_userid = '".$sender_id."' AND reciever_userid = '".$userId."') AND chat_room_id IS NULL";			
		mysqli_query($this->dbConnect, $sqlChatUpdate);	
		return $sqlChatUpdate;
	}
	public function insertChat($reciever_userid, $user_id, $chat_message, $booth_id) {		
		$sqlInsert = "
			INSERT INTO ".$this->chatTable." 
			(reciever_userid, sender_userid, message, status, viewed, booth_id) 
			VALUES ('".$reciever_userid."', '".$user_id."', '".$chat_message."', '1', 0, ". $booth_id .")";
		$result = mysqli_query($this->dbConnect, $sqlInsert);
		if(!$result){
			return ('Error in query: '. mysqli_error());
		} else {
			$conversation = $this->getUserChat($user_id, $reciever_userid);
			$data = array(
				"conversation" => $conversation			
			);
			echo json_encode($data);	
		}
	}
	public function getUserChat($from_user_id, $to_user_id) {
		$fromUserAvatar = $this->getUserAvatar($from_user_id);	
		$toUserAvatar = $this->getUserAvatar($to_user_id);			
		$sqlQuery = "
			SELECT * FROM ".$this->chatTable." 
			WHERE ((sender_userid = '".$from_user_id."' 
			AND reciever_userid = '".$to_user_id."') 
			OR (sender_userid = '".$to_user_id."' 
			AND reciever_userid = '".$from_user_id."'))
			AND chat_room_id IS NULL 
			ORDER BY timestamp ASC";
		$userChat = $this->getData($sqlQuery);	
		$conversation = '<ul>';
		foreach($userChat as $chat){
			$user_name = '';
			if($chat["sender_userid"] == $from_user_id) {
				$conversation .= '<li class="sent">';
				$conversation .= '<img width="22px" height="22px" src="userpics/'.$fromUserAvatar.'" alt="" />';
				$conversation .= '<p>'.$chat["message"].'<br><span class="chat-date chat-date-sent">'.date('M jS H:i', strtotime($chat["timestamp"])).'</span></p>';	
			} else {
				$conversation .= '<li class="replies">';
				$conversation .= '<img width="22px" height="22px" src="userpics/'.$toUserAvatar.'" alt="" />';
				$conversation .= '<p>'.$chat["message"].'<br><span class="chat-date chat-date-reply">'.date('M jS H:i', strtotime($chat["timestamp"])).'</span></p>';	
			}			
					
			$conversation .= '</li>';
		}		
		$conversation .= '</ul>';
		return $conversation;
	}
	public function showUserChat($from_user_id, $to_user_id) {		
		$userDetails = $this->getUserDetails($to_user_id);
		$toUserAvatar = '';
		foreach ($userDetails as $user) {
			$toUserAvatar = $user['avatar'];
			$resume = '';
			if ($user['resume_filename'] && $user['resume_mime']) {
				//$resume = '<div class="chat-resume" onclick="openResume(\''.$user['resume_filename'].'\', \''.$user['resume_mime'].'\')">Open Resume</div>';
				$resume = '<div class="chat-resume" onclick="parent.openResume('.$user['id'].')">Open Resume</div>';
			}
			$userSection = '<img src="userpics/'.$user['avatar'].'" alt="" />
				<div class="profile-container">
					<div class="profile-username">'.$user['chat_username'].'</div>
					<div class="profile-name">'.$user['firstname'].' '.$user['lastname'].'</div>
				</div>
				'.$resume.'
				<div class="social-media">
					
				</div>';
		}		
		// get user conversation
		$conversation = $this->getUserChat($from_user_id, $to_user_id);	
		// update chat user read status		
		$sqlUpdate = "
			UPDATE ".$this->chatTable." 
			SET status = '0' 
			WHERE sender_userid = '".$to_user_id."' AND reciever_userid = '".$from_user_id."' AND status = '1'";
		mysqli_query($this->dbConnect, $sqlUpdate);		
		// update users current chat session
		$sqlUserUpdate = "
			UPDATE ".$this->chatUsersTable." 
			SET chat_current_session = '".$to_user_id."' 
			WHERE id = '".$from_user_id."'";
		mysqli_query($this->dbConnect, $sqlUserUpdate);		
		$data = array(
			"userSection" => $userSection,
			"conversation" => $conversation			
		 );
		 echo json_encode($data);		
	}	
	public function getUnreadMessageCount($senderUserid, $recieverUserid) {
		$sqlQuery = "
			SELECT * FROM ".$this->chatTable."  
			WHERE sender_userid = '$senderUserid' AND reciever_userid = '$recieverUserid' AND status = '1'";
		$numRows = $this->getNumRows($sqlQuery);
		$output = '';
		if($numRows > 0){
			$output = $numRows;
		}
		return $output;
	}	
	public function updateTypingStatus($is_type, $loginDetailsId) {		
		$sqlUpdate = "
			UPDATE ".$this->chatLoginDetailsTable." 
			SET is_typing = '".$is_type."' 
			WHERE id = '".$loginDetailsId."'";
		mysqli_query($this->dbConnect, $sqlUpdate);
	}		
	public function fetchIsTypeStatus($userId){
		$sqlQuery = "
		SELECT is_typing FROM ".$this->chatLoginDetailsTable." 
		WHERE userid = '".$userId."' ORDER BY last_activity DESC LIMIT 1"; 
		$result =  $this->getData($sqlQuery);
		$output = '';
		foreach($result as $row) {
			if($row["is_typing"] == 'yes'){
				$output = ' - <small><em>Typing...</em></small>';
			}
		}
		return $output;
	}		
	public function insertUserLoginDetails($userId) {		
		$sqlInsert = "
			INSERT INTO ".$this->chatLoginDetailsTable."(userid) 
			VALUES ('".$userId."')";
		mysqli_query($this->dbConnect, $sqlInsert);
		$lastInsertId = mysqli_insert_id($this->dbConnect);
        return $lastInsertId;		
	}	
	public function updateLastActivity($loginDetailsId) {		
		$sqlUpdate = "
			UPDATE ".$this->chatLoginDetailsTable." 
			SET last_activity = now() 
			WHERE id = '".$loginDetailsId."'";
		mysqli_query($this->dbConnect, $sqlUpdate);
	}	
	public function getUserLastActivity($userId) {
		$sqlQuery = "
			SELECT last_activity FROM ".$this->chatLoginDetailsTable." 
			WHERE userid = '$userId' ORDER BY last_activity DESC LIMIT 1";
		$result =  $this->getData($sqlQuery);
		foreach($result as $row) {
			return $row['last_activity'];
		}
	}	
}
?>
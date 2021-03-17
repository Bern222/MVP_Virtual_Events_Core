<?php
class Chat{
	// require_once("common.php");
	// private $host = '127.0.0.1';
	// private $user = 'mvpvirtualevents_dbuser';
	// private $password = 'f1uOgykR$8#Z';
	// private $database = 'mvpvirtualevents_data';

	private $dbhost = '127.0.0.1';
	private $dbuser = 'beyadigital_dbuser';
	private $dbpass = '!KoqOzyVmVCa';
	private $dbname = 'beyadigital_data';


	// private $dbhost = '127.0.0.1';
	// private $dbuser = 'womenofcoloronln_dbuser';
	// private $dbpass = 'S!1SL$O,We;p';
	// private $dbname = 'womenofcoloronln_data';
   
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
			die('Error in query: '. mysqli_error());
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
		$sqlQuery = "
			SELECT * FROM ".$this->chatUsersTable." 
			WHERE id != '$userid'";
		return  $this->getData($sqlQuery);
	}
	public function chatRooms($sponsorId){
		$sqlQuery = "
			SELECT * FROM chat_room
			WHERE sponsor_id != '$sponsorId'";
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
	public function getUsername($userid){
		$sqlQuery = "
			SELECT chat_username 
			FROM ".$this->chatUsersTable." 
			WHERE id = '$userid'";
		$userResult = $this->getData($sqlQuery);
		$username = '';
		foreach ($userResult as $user) {
			$username = $user['chat_username'];
		}	
		return $username;
	}	
	public function updateUserOnline($userId, $online) {		
		$sqlUserUpdate = "
			UPDATE ".$this->chatUsersTable." 
			SET chat_online = '".$online."' 
			WHERE id = '".$userId."'";			
		mysqli_query($this->dbConnect, $sqlUserUpdate);		
	}
	public function insertChat($reciever_userid, $user_id, $chat_message, $chat_room_id) {		
		$sqlInsert = "
			INSERT INTO ".$this->chatTable." 
			(reciever_userid, sender_userid, message, status, chat_room_id) 
			VALUES ('".$reciever_userid."', '".$user_id."', '".$chat_message."', '1', '".$chat_room_id."')";
		$result = mysqli_query($this->dbConnect, $sqlInsert);
		if(!$result){
			return ('Error in query: '. mysqli_error());
		} else {
			$conversation = $this->getUserChat($user_id, $reciever_userid, $chat_room_id);
			$data = array(
				"conversation" => $conversation			
			);
			echo json_encode($data);	
		}
	}
	public function getUserChat($from_user_id, $to_user_id, $chat_room_id) {	
		$sqlQuery = "
			SELECT * FROM ".$this->chatTable." 
			WHERE chat_room_id = ".$chat_room_id." 
			ORDER BY timestamp ASC";
		$userChat = $this->getData($sqlQuery);	
		$conversation = '<ul>';
		foreach($userChat as $chat){
			$fromUserAvatar = $this->getUserAvatar($from_user_id);	
			$toUserAvatar = $this->getUserAvatar($chat["sender_userid"]);	
			$fromUsername = $this->getUsername($from_user_id);
			$toUsername = $this->getUsername($chat["sender_userid"]);
			$user_name = '';
			if($chat["sender_userid"] == $from_user_id) {
				$conversation .= '<li class="sent">';
				$conversation .= '<div class="avatar-username">';
				$conversation .= '<img width="22px" height="22px" src="userpics/'.$fromUserAvatar.'" alt="" />';
				$conversation .= '<p class="username username-sent">'.$fromUsername.'</p>';	
				$conversation .= '</div>';
				$conversation .= '<p class="message-text">'.$chat["message"].'<br><span class="chat-date chat-date-sent">'.date('M jS H:i', strtotime($chat["timestamp"])).'</span></p>';
			} else {
				$conversation .= '<li class="replies">';
				$conversation .= '<div class="avatar-username">';
				$conversation .= '<img width="22px" height="22px" src="userpics/'.$toUserAvatar.'" alt="" />';
				$conversation .= '<p class="username username-reply">'.$toUsername.'</p>';
				$conversation .= '</div>';
				$conversation .= '<p class="message-text">'.$chat["message"].'<br><span class="chat-date chat-date-reply">'.date('M jS H:i', strtotime($chat["timestamp"])).'</span></p>';
			}					
			$conversation .= '</li>';
		}		
		$conversation .= '</ul>';
		return $conversation;
	}
	public function showUserChat($from_user_id, $to_user_id, $chat_room_id) {		
		$userDetails = $this->getUserDetails($to_user_id);
		$toUserAvatar = '';
		foreach ($userDetails as $user) {
			$toUserAvatar = $user['avatar'];
			$userSection = '<img src="userpics/'.$user['avatar'].'" alt="" />
				<p>'.$user['chat_username'].'</p>
				<div class="social-media">
					<i class="fa fa-facebook" aria-hidden="true"></i>
					<i class="fa fa-twitter" aria-hidden="true"></i>
					<i class="fa fa-instagram" aria-hidden="true"></i>
				</div>';
		}		
		// get user conversation
		$conversation = $this->getUserChat($from_user_id, $to_user_id, $chat_room_id);	
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
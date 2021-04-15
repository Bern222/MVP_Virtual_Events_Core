<?php
   // ini_set('display_errors', 1);
   // ini_set('display_startup_errors', 1);
   // error_reporting(E_ALL);
   
   require_once("common.php");


   $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if (!$db) {
      echo "Error: Unable to connect to MySQL." . PHP_EOL;
      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
      exit;
   }

   $method = $_POST['method'];

   switch($method){
        case "eventLogger": echo eventLogger();
        break;
   }


   function eventLogger() {
    global $config;
    global $db;

    //$boothId = $_POST['boothId'];
    $currentUserId = $_POST['currentUserId'];
    //$title = $_POST['title'];
    //$url = $_POST['url'];
    
    $q = "INSERT INTO event_log (userid, sessionid, ip_address, user_agent, event_category, event_action, event_label) VALUES ('" . $currentUserId . "', '" . $_SESSION['session_id'] . "', '" . $_SERVER['REMOTE_ADDR'] . "', '" . $_SERVER['HTTP_USER_AGENT'] . "', '" . $_POST['event_category'] . "', '" . addslashes($_POST['event_action']) . "', '" . $_POST['event_label'] . "');";
    $r = $db->query($q);

    return 'success';
 }

?>
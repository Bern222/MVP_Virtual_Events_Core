<?php

//  Provide data for content sessions/videos for the Auditorium and Breakouts

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


require_once("../../common.php");

$method = $_POST['method'];

switch($method){
      case "getSession": echo json_encode(getSession());
      break;
      case "getRoomSessions": echo json_encode(getRoomSessions());
      break;
      case "getAllSessions": echo json_encode(getAllSessions());
      break;
}

function getSession() {
   global $config;
   global $db;

   $classKey = $_POST['classKey'];

   $results = array();
   $q = "SELECT eShowSessions.*, DATE_FORMAT(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0'),'%b-%d') as CLASS_DATE, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0')) as CLASS_START_TIMESTAMP, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) as CLASS_END_TIMESTAMP FROM eShowSessions WHERE CLASS_KEY = '$classKey' AND id != 11";
   $r = $db->query($q);
   
   $results = array();
   while($row = mysqli_fetch_assoc($r)) {
      $results[] = $row;
   }
   return $results;
}
 
function getRoomSessions() {
    global $config;
    global $db;

    $room = $_POST['room'];

    $results = array();
    $q = "SELECT eShowSessions.DESCRIPTION, eShowSessions.*, DATE_FORMAT(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0'),'%b-%d') as CLASS_DATE, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0')) as CLASS_START_TIMESTAMP, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) as CLASS_END_TIMESTAMP FROM eShowSessions WHERE UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) > UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AND VENUE = 'BEYA World I' AND ACTIVE = 1 AND ROOM = '" . $room . "' AND id != 11 ORDER BY CLASS_START";

    // $q = "SELECT eShowSessions.DESCRIPTION, eShowSessions.*, DATE_FORMAT(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0'),'%b-%d') as CLASS_DATE, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0')) as CLASS_START_TIMESTAMP, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) as CLASS_END_TIMESTAMP FROM eShowSessions WHERE UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) > UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AND VENUE ='" . $_POST['room'] . "' ORDER BY CLASS_START";

    // $q = "SELECT eShowSessions.DESCRIPTION, eShowSessions.*, DATE_FORMAT(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0'),'%b-%d') as CLASS_DATE, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0')) as CLASS_START_TIMESTAMP, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) as CLASS_END_TIMESTAMP FROM eShowSessions WHERE UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) > UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AND VENUE_MANUAL ='" . $_POST['room'] . "' ORDER BY CLASS_START";
    $r = $db->query($q);
    
    $results = array();
    while($row = mysqli_fetch_assoc($r)) {
       $results[] = $row;
    }
    return $results;
}

function getAllSessions() {
   global $config;
   global $db;

   $results = array();
   $q = "SELECT eShowSessions.*, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0')) as CLASS_START_TIMESTAMP, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) as CLASS_END_TIMESTAMP FROM eShowSessions WHERE ACTIVE = 1 AND id != 11 AND VENUE = 'BEYA World I' ORDER BY CLASS_START";
   $r = $db->query($q);
   
   $results = array();
   while($row = mysqli_fetch_assoc($r)) {
      $results[] = $row;
   }
   return $results;
}
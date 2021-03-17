<?php

//  Provide data for content sessions/videos for the Auditorium and Breakouts

require_once("common.php");

echo json_encode(getVod());

//$defualtVenue = 'BEYA World I';

function getVod() {
    global $config;
    global $db;

    $results = array();
    
    $q = "SELECT eShowSessions.DESCRIPTION, eShowSessions.*, DATE_FORMAT(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0'),'%b-%d') as CLASS_DATE, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_START,'%Y-%m-%d %H:%i:%s.0')) as CLASS_START_TIMESTAMP, UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) as CLASS_END_TIMESTAMP FROM eShowSessions where UNIX_TIMESTAMP(STR_TO_DATE(CLASS_END,'%Y-%m-%d %H:%i:%s.0')) < UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AND VOD_URL not like '' AND VENUE ='BEYA World I' AND ROOM ='" . $_POST['room'] . "' ORDER BY CLASS_START";
    $r = $db->query($q);
    
    $results = array();
    while($row = mysqli_fetch_assoc($r)) {
       $results[] = $row;
    }
    return $results;
  }
<?php

//  Provide data for user session bookmarks

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once("../../common.php");

$method = $_POST['method'];

switch($method){
      case "getUserBookmarks": echo json_encode(getUserBookmarks());
      break;
}

function getUserBookmarks() {
    global $config;
    global $db;

    // TODO: switch this back to Session Var from user
    // $badgeId = $_SESSION['badgeId'];
    $badgeId = '000000';

    $results = array();
    // $q = "SELECT eshowSearch.* FROM eshowSearch WHERE search_term LIKE '$searchString'";
    
    $q = "SELECT eshowBookmarks.SESSION_CODE FROM eshowBookmarks WHERE BADGE_ID = " . $badgeId;


    $r = $db->query($q);
    
    $results = array();
    while($row = mysqli_fetch_assoc($r)) {
       $results[] = $row;
    }
    return $results;
}

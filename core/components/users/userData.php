<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


//  Provide data for content speakers for sessions the Auditorium and Seminars

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once($root . "/core/common.php");

$method = $_POST['method'];

switch($method){
      case "updateUserNotifications": echo updateUserNotifications();
      break;
      case "getAnswers": echo json_encode(getAnswers());
      break;
      case "getResults": echo json_encode(getResults());
      break;
      case "checkResult": echo json_encode(checkResult());
      break;
      case "saveResults": echo json_encode(saveResults());
      break;
}
     
function updateUserNotifications() {
    global $config;
    global $db;

    $userId = $_POST['userId'];
    $showNotifications = $_POST['showNotifications'];

    $results = array();
    $q = "Update users SET show_notifications = " . $showNotifications . " WHERE id = " . $userId;

    $r = $db->query($q);
    
    return $q;
}

function getAnswers() {
    global $config;
    global $db;

    $results = array();
    $q = "SELECT trivia_answer.* FROM trivia_answer where is_active ='1'";

    $r = $db->query($q);
    
    $results = array();
    while($row = mysqli_fetch_assoc($r)) {
       $results[] = $row;
    }
    return $results;
}

function getResults() {
    global $config;
    global $db;

    $results = array();
    $q = "SELECT trivia_results.* FROM trivia_results where is_active ='1'";

    $r = $db->query($q);
    
    $results = array();
    while($row = mysqli_fetch_assoc($r)) {
       $results[] = $row;
    }
    return $results;
}

function checkResult() {
    global $config;
    global $db;

    $email = $_POST['email'];

    $results = array();
    $q = "SELECT trivia_results.* FROM trivia_results where user_email ='" . $email . "'";

    $r = $db->query($q);
    
    $results = array();
    while($row = mysqli_fetch_assoc($r)) {
       $results[] = $row;
    }
    return $results;
}

function saveResults() {
    global $config;
    global $db;

    $email = $_POST['email'];
    $score = $_POST['score'];
    $date = $_POST['date'];
    
    $q = "INSERT INTO trivia_results (email, score, date) VALUES ('" . $email . "', '" . $score . "', " . $date . ")";
    $r = $db->query($q);

    return 'success';
 }
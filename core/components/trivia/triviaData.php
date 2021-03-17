<?php

//  Provide data for content speakers for sessions the Auditorium and Seminars

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once("common.php");

$method = $_POST['method'];

switch($method){
      case "getQuestions": echo json_encode(getQuestions());
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
     
function getQuestions() {
    global $config;
    global $db;

    $results = array();
    $q = "SELECT trivia_questions.* FROM trivia_questions where is_active ='1'";

    $r = $db->query($q);
    
    $results = array();
    while($row = mysqli_fetch_assoc($r)) {
       $results[] = $row;
    }
    return $results;
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
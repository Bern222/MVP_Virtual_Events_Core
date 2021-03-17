<?php

//  Provide data for sessions for the session search

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once("../../common.php");

$method = $_POST['method'];

switch($method){
      case "getSearchResults": echo json_encode(getSearchResults());
      break;
}

function getSearchResults() {
    global $config;
    global $db;

    $searchString = $_POST['searchString'];

    if ($searchString == '') {
        return '';
    }

    $results = array();
    // $q = "SELECT eshowSearch.* FROM eshowSearch WHERE search_term LIKE '$searchString'";
    
    $q = "SELECT eshowSearch.* FROM eshowSearch WHERE INSTR(search_term,'$searchString')>0";


    $r = $db->query($q);
    
    $results = array();
    while($row = mysqli_fetch_assoc($r)) {
       $results[] = $row;
    }
    return $results;
}

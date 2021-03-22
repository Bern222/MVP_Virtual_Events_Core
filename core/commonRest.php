<?php
   // ini_set('display_errors', 1);
   // ini_set('display_startup_errors', 1);
   // error_reporting(E_ALL);
       $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';

   
   require_once($root . "/core/common.php");

   $method = $_POST['method'];

   switch($method){
      case "checkVersion": echo json_encode(checkVersion());
      break;
      case "updateSession": echo json_encode(updateSessionTimestamp());
      break;
   }

   function checkVersion() {
      global $config;
      global $db;

      $results = array();
      
      $q = "SELECT * FROM configuration WHERE name='app_version'";
      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return $results;
    }

    function updateSessionTimestamp() {
      return updateSession($_SESSION['userid'], $_SESSION['session_id']);
    }
?>
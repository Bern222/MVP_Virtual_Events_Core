<?php

//  Provide data for notifications

// require_once($root . "/core/common.php");

$method = $_POST['method'];

switch($method){
      case "getServerTimestamp": echo time();
      break;
}

?>

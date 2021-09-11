<?php

  $dbhost = '127.0.0.1';
  $dbuser = 'aaaatthegarage_dbuser';
  $dbpass = 'Dgzh#85xZ3K^';
  $dbname = 'aaaatthegarage_nightout';

  $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

  if (!$db) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
  }
?>
<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


ini_set("session.gc_maxlifetime",86400);
ini_set("session.save_path","/home/womenofcoloronln/sessiondata");

// set local time zone if needed
if(function_exists("date_default_timezone_set"))
{
  date_default_timezone_set('America/New_York');
}

session_start();

require_once($root . "/core/modules/dbaccess.php");
require_once($root . "/core/modules/config.php");
require_once($root . "/core/modules/sessions.php");
require_once($root . "/core/modules/users.php");

loadConfig();
timeoutSessions();

?>
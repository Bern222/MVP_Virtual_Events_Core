<?php
require_once("../modules/dbaccess.php");
require_once("../modules/config.php");

loadConfig();

updateSearch();

exit;

function updateSearch()
{
  global $db;

  // Load the sessions

  $sessions = array();
  $q = "SELECT * FROM eShowSessions";
  if($r = $db->query($q))
  {
    for($i=0;$i<$r->num_rows;$i++)
    {
      $sessions[$i] = $r->fetch_assoc();
    }
    $r->close();
  }

  // Delete previous search records that were imported
  $q = "DELETE FROM eshowSearch WHERE from_import=1";
  $db->query($q);

  // Add search records
  for($i=0;$i<count($sessions);$i++)
  {
    // Set the class name search term
   
    $sets = array();
    $sets[] = sprintf("search_term='%s'",$db->real_escape_string($sessions[$i]['TITLE']));
    $sets[] = sprintf("session_name='%s'",$db->real_escape_string($sessions[$i]['TITLE']));
    $sets[] = sprintf("session_ID='%s'",$db->real_escape_string($sessions[$i]['CLASS_KEY']));
    $sets[] = "from_import=1";

    $q = "INSERT INTO eshowSearch SET ".implode(",",$sets);
    $db->query($q);

    // Speakers

    $speakers = json_decode($sessions[$i]['SPEAKER'],1);

    for($j=0;$j<count($speakers);$j++)
    {
      $sets = array();
      $name = $speakers[$j]['FIRST_NAME']." ".$speakers[$j]['LAST_NAME'];
      $sets[] = sprintf("search_term='%s'",$db->real_escape_string($name));
      $sets[] = sprintf("session_name='%s'",$db->real_escape_string($sessions[$i]['TITLE']));
      $sets[] = sprintf("session_ID='%s'",$db->real_escape_string($sessions[$i]['CLASS_KEY']));
      $sets[] = "from_import=1";

      $q = "INSERT INTO eshowSearch SET ".implode(",",$sets);
      $db->query($q);
    }
  }
}


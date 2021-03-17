<?php

  /*****************************************************************************/
  /* This module allows access to the session table in the database            */
  /*****************************************************************************/

  if(!isset($_SESSION['session_id'])) $_SESSION['session_id'] = "";
  if(!isset($_SESSION['logged_in'])) $_SESSION['logged_in'] = 0;
  if(!isset($_SESSION['userid'])) $_SESSION['userid'] = 0;
  if(!isset($_SESSION['username'])) $_SESSION['username'] = 0;
  if(!isset($_SESSION['chat_username'])) $_SESSION['chat_username'] = "";

  function createSession($userid)
  {
    global $config;
    global $db;
    global $userdata;

    $sidok=0;
    $sid = "";

    $length=$config['session_keylength'];

    // define possible characters
    $possible = "abcdefghjklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ!$_";

    // Now generate a code value
    $maxattempts = 20;

    while(!$sidok && $maxattempts > 0)
    {
      $sid = "";
      // add random characters to id until length is reached
      while (strlen($sid) < $length)
      {
        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        $sid .= $char;
      }

      // Query the access_token database to make sure the code hasn't been used.

      $q = sprintf("SELECT * FROM sessions WHERE sessionid=BINARY '%s'",$db->real_escape_string($sid));
      if($r = $db->query($q))
      {
        if($r->num_rows == 0) $sidok=1;
        $r->close();
      }

      $maxattempts--;
    }

    if($sidok == 1)
    {
      // Create the session record in the database.
      $sets = array();
      $sets[] = sprintf("userid=%d",intval($userid));
      $sets[] = sprintf("sessionid='%s'",addslashes($sid));
      $sets[] = "active=1";
      $sets[] = sprintf("time_start=%d",time());
      $sets[] = sprintf("time_lastseen=%d",time());

      $q = "INSERT INTO sessions SET ".implode(",",$sets);
      $db->query($q);

      $q = sprintf("update users SET lastseen=%d WHERE id=%d",time(),$userid);
      $db->query($q);

      $_SESSION['userdata'] = $userdata;
      $_SESSION['userdata']['password'] = ' ';

      $_SESSION['session_id'] = $sid;
      $_SESSION['logged_in'] = 1;
      $_SESSION['userid'] = intval($userid);
      $_SESSION['username'] = $userdata['email'];
      $_SESSION['chat_username'] = $userdata['chat_username'];

      // Set the session variable
      return $sid;
    }

    return "";
  }

  function validSession($userid,$sessionid)
  {
    global $config;
    global $db;

    $ret = false;

    timeoutSessions();

    $q = sprintf("SELECT * FROM sessions WHERE sessionid=BINARY '%s' and userid=%d and active=1",$db->real_escape_string($sessionid),$userid);
    if($r = $db->query($q))
    {
      if($r->num_rows > 0) $ret = true;
      $r->close();
    }

    return $ret;
  }

  function updateSession($userid,$sessionid)
  {
    global $config;
    global $db;

    if(validSession($userid,$sessionid))
    {
      $q = sprintf("UPDATE sessions set time_lastseen=%d WHERE sessionid=BINARY '%s' and active=1",time(),$db->real_escape_string($sessionid));
      $db->query($q);
      return true;
    }
    return false;
  }

  function expireSession($userid,$sessionid)
  {
    global $config;
    global $db;

    if(validSession($userid,$sessionid))
    {
      $q = sprintf("UPDATE sessions set active=0 WHERE sessionid=BINARY '%s' and active=1",$db->real_escape_string($sessionid));
      $db->query($q);
    }
  }

  function timeoutSessions()
  {
    global $config;
    global $db;

    $sessions = array();

    $q = sprintf("SELECT * FROM sessions WHERE active=1");
    if($r = $db->query($q))
    {
      for($i=0;$i<$r->num_rows;$i++)
      {
        $sessions[] = $r->fetch_assoc();
      }
      $r->close();
    }

    if(count($sessions) > 0)
    {
      $idletime = time() - intval($config['session_idletimeout']);
      $maxtime = time() - intval($config['session_fulltimeout']);

      for($i=0;$i<count($sessions);$i++)
      {
        $expire = 0;

        // Check idle time
        if($config['session_idletimeout'] != 0)
        {
          if($sessions[$i]['time_lastseen'] < $idletime) $expire = 1;
        }

        // Check max login time
        if($config['session_fulltimeout'] != 0)
        {
          if($sessions[$i]['time_start'] < $maxtime) $expire = 1;
        }

        if($expire == 1)
        {
          $q = sprintf("UPDATE sessions set active=0 WHERE id=%d",intval($sessions[$i]['id']));
          $db->query($q);
        }
      }
    }
  }

?>
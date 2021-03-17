<?php
  // Back end processing for login module
ini_set("session.gc_maxlifetime",86400);
ini_set("session.save_path","/home/womenofcoloronln/sessiondata");

// set local time zone if needed
if(function_exists("date_default_timezone_set"))
{
  date_default_timezone_set('America/New_York');
}

session_start();

require_once("../modules/dbaccess.php");
require_once("../modules/config.php");
require_once("../modules/sessions.php");
require_once("../modules/users.php");

loadConfig();

timeoutSessions();

$method = $_POST['method'];

switch($method){
	  case "activeSessions": echo activeSessions();
      break;
	  case "dupeSessions": echo dupeSessions();
      break;
	  case "checkMySession": echo checkMySession();
      break;
	  case "topLogins": echo topLogins();
      break;
	  case "topIPs": echo topIPs();
      break;
}

function activeSessions()
{
  global $db;

  $now = time();
  $today_start = mktime(0,0,0,date("n",$now),date("j",$now),date("Y",$now));
  $today_end = mktime(23,59,59,date("n",$now),date("j",$now),date("Y",$now));

	// get all active sessions for today
	$activeSessions = array();
	$q = "SELECT * FROM sessions WHERE active=1";
	if($r = $db->query($q))
	{
		for($i=0;$i<$r->num_rows;$i++)
		{
			$activeSessions[$i] = $r->fetch_assoc();
		}
		$r->close();
	}
	else
	{
		$reply = array();
		$reply['status'] = "error";
		$reply['sessionCount'] = count($activeSessions);
		$reply['error'] = "Database Error: ".$db->last_error;
		return json_encode($reply);
	}

	$reply = array();
	$reply['status'] = "success";
	$reply['sessionCount'] = count($activeSessions);
	if(count($activeSessions) == 0)
	{
		$reply['sessions'] = "[]";
		return json_encode($reply);
  }

	$reply['sessions'] = json_encode($activeSessions);

if(1)
{  
	$sessionAge = array();
	$sessionAge[0] = array("age"=>"0-2","lastcount"=>0,"startcount"=>0);
	$sessionAge[1] = array("age"=>"2-4","lastcount"=>0,"startcount"=>0);
	$sessionAge[2] = array("age"=>"4-6","lastcount"=>0,"startcount"=>0);
	$sessionAge[3] = array("age"=>"6-8","lastcount"=>0,"startcount"=>0);
	$sessionAge[4] = array("age"=>"8-10","lastcount"=>0,"startcount"=>0);
	$sessionAge[5] = array("age"=>"10 Plus","lastcount"=>0,"startcount"=>0);

	for($i=0;$i<count($activeSessions);$i++)
	{
		$lastdiff = intval(($now - $activeSessions[$i]['time_lastseen'])/3600);

		if($lastdiff > 10)
		{
			$sessionAge[5]['lastcount']++;
			continue;
		}

		if($lastdiff > 8)
		{
			$sessionAge[4]['lastcount']++;
			continue;
		}

		if($lastdiff > 6)
		{
			$sessionAge[3]['lastcount']++;
			continue;
		}

		if($lastdiff > 4)
		{
			$sessionAge[2]['lastcount']++;
			continue;
		}

		if($lastdiff > 2)
		{
			$sessionAge[1]['lastcount']++;
			continue;
		}

		$sessionAge[0]['lastcount']++;
	}

	for($i=0;$i<count($activeSessions);$i++)
	{
		$startdiff = intval(($now - $activeSessions[$i]['time_start'])/3600);

		if($startdiff > 10)
		{
			$sessionAge[5]['startcount']++;
			continue;
		}

		if($startdiff > 8)
		{
			$sessionAge[4]['startcount']++;
			continue;
		}

		if($startdiff > 6)
		{
			$sessionAge[3]['startcount']++;
			continue;
		}

		if($startdiff > 4)
		{
			$sessionAge[2]['startcount']++;
			continue;
		}

		if($startdiff > 2)
		{
			$sessionAge[1]['startcount']++;
			continue;
		}

		$sessionAge[0]['startcount']++;
	}
}
else
{
	$sessionAge = array();
	$sessionAge[0] = array("age"=>"0-1","lastcount"=>0,"startcount"=>0);
	$sessionAge[1] = array("age"=>"1-2","lastcount"=>0,"startcount"=>0);
	$sessionAge[2] = array("age"=>"2-3","lastcount"=>0,"startcount"=>0);
	$sessionAge[3] = array("age"=>"3-4","lastcount"=>0,"startcount"=>0);
	$sessionAge[4] = array("age"=>"4-5","lastcount"=>0,"startcount"=>0);
	$sessionAge[5] = array("age"=>"5-6","lastcount"=>0,"startcount"=>0);
	$sessionAge[6] = array("age"=>"6-7","lastcount"=>0,"startcount"=>0);
	$sessionAge[7] = array("age"=>"7-8","lastcount"=>0,"startcount"=>0);
	$sessionAge[8] = array("age"=>"8-9","lastcount"=>0,"startcount"=>0);
	$sessionAge[9] = array("age"=>"9-10","lastcount"=>0,"startcount"=>0);
	$sessionAge[10] = array("age"=>"10 Plus","lastcount"=>0,"startcount"=>0);

	for($i=0;$i<count($activeSessions);$i++)
	{
		$lastdiff = intval(($now - $activeSessions[$i]['time_lastseen'])/3600);

		if($lastdiff > 10)
		{
			$sessionAge[10]['lastcount']++;
		}
     else
     {
       $sessionAge[$lastdiff][lastcount]++;
     }
	}

	for($i=0;$i<count($activeSessions);$i++)
	{
		$startdiff = intval(($now - $activeSessions[$i]['time_start'])/3600);

		if($startdiff > 10)
		{
			$sessionAge[10]['startcount']++;
		}
     else
     {
       $sessionAge[$startdiff][startcount]++;
     }
	}
}

	$reply['ages'] = json_encode($sessionAge);

	return json_encode($reply);
}

function licmp($a,$b)
{
  return($b['logincount']-$a['logincount']);
}

function topLogins()
{
  global $db;

  $now = time();
  $today_start = mktime(0,0,0,date("n",$now),date("j",$now),date("Y",$now));
  $today_end = mktime(23,59,59,date("n",$now),date("j",$now),date("Y",$now));

	// get all logins for today
  $logins = array();
	$q = sprintf("SELECT * FROM loginTrack WHERE login_time > %d and login_time < %d and userid!=0",$today_start,$today_end);
	if($r = $db->query($q))
	{
		for($i=0;$i<$r->num_rows;$i++)
		{
			$logins[$i] = $r->fetch_assoc();
		}
		$r->close();
	}
	else
	{
		$reply = array();
		$reply['status'] = "error";
		$reply['error'] = "Database Error: ".$db->last_error;
		return json_encode($reply);
	}

	$logindata = array();

	for($l=0;$l<count($logins);$l++)
	{
     $found = false;
		for($d=0;$d<count($logindata) && !$found;$d++)
		{
			if($logindata[$d]['userid'] == $logins[$l]['userid'])
			{
				$logindata[$d]['logincount']++;
				$found = true;

				// check IP addresses?
				$ipfound = false;
				for($i=0;$i<count($logindata[$d]['iplist']) && !$ipfound;$i++)
				{
					if($logindata[$d]['iplist'][$i]['ip'] == $logins[$l]['ip_address'])
					{
						$logindata[$d]['iplist'][$i]['count']++;
						$ipfound = true;
					}
				}

				if(!$ipfound)
				{
					$logindata[$d]['iplist'][] = array("ip"=>$logins[$l]['ip_address'],"count"=>1);
				}
			}
		}

		if(!$found)
		{
			// Add a record
			$ld = count($logindata);
			$logindata[$ld] = array();
			$logindata[$ld]['userid'] = $logins[$l]['userid'];
			$logindata[$ld]['username'] = $logins[$l]['username'];
			$logindata[$ld]['logincount'] = 1;
			$logindata[$ld]['iplist'] = array();
			$logindata[$ld]['iplist'][] = array("ip"=>$logins[$l]['ip_address'],"count"=>1);
		}
	}

	usort($logindata,"licmp");

	$reply = array();
	$reply['status'] = "success";
	$reply['count'] = count($logindata);
	$reply['data'] = json_encode($logindata);

	return json_encode($reply);
}

define("_ADMINONLY_",true); // Only admins can run this script
function checkMySession()
{
  global $userdata;
  global $db;

  $reply = array();
  if(_ADMINONLY_) $reply['comment'] = "Admin Only Access";
  $reply['userid'] = $_SESSION['userid'];
  $reply['session_id'] = $_SESSION['session_id'];

  $sessionvalid = validSession($_SESSION['userid'], $_SESSION['session_id']);
  $reply['sv'] = $sessionvalid;
  if($sessionvalid)
  {
    if(_ADMINONLY_)
    {
      if(userGetById($_SESSION['userid']))
      {
        if($userdata['admin'] == 0)
        {
          $reply['status'] = "Invalid!";
          $reply['error'] = "Not Admin";
          return json_encode($reply);
        }
      }
      updateSession($_SESSION['userid'], $_SESSION['session_id']);
      $reply['status'] = "Valid!";
      return json_encode($reply);
    }
    else
    {
      updateSession($_SESSION['userid'], $_SESSION['session_id']);
      $reply['status'] = "Valid!";
      return json_encode($reply);
    }
  }

  // This is an invalid session
  $reply['status'] = "Invalid!";
  $reply['error'] = "Not Valid?";
  return json_encode($reply);
}

function ipcmp($a,$b)
{
  return(count($b['iplist'])-count($a['iplist']));
}

function topIPs()
{
  global $db;

  $now = time();
  $today_start = mktime(0,0,0,date("n",$now),date("j",$now),date("Y",$now));
  $today_end = mktime(23,59,59,date("n",$now),date("j",$now),date("Y",$now));

	// get all logins for today
  $logins = array();
	$q = sprintf("SELECT * FROM loginTrack WHERE login_time > %d and login_time < %d and userid!=0",$today_start,$today_end);
	if($r = $db->query($q))
	{
		for($i=0;$i<$r->num_rows;$i++)
		{
			$logins[$i] = $r->fetch_assoc();
		}
		$r->close();
	}
	else
	{
		$reply = array();
		$reply['status'] = "error";
		$reply['error'] = "Database Error: ".$db->last_error;
		return json_encode($reply);
	}

	$logindata = array();

	for($l=0;$l<count($logins);$l++)
	{
     $found = false;
		for($d=0;$d<count($logindata) && !$found;$d++)
		{
			if($logindata[$d]['userid'] == $logins[$l]['userid'])
			{
				$logindata[$d]['logincount']++;
				$found = true;

				// check IP addresses?
				$ipfound = false;
				for($i=0;$i<count($logindata[$d]['iplist']) && !$ipfound;$i++)
				{
					if($logindata[$d]['iplist'][$i]['ip'] == $logins[$l]['ip_address'])
					{
						$logindata[$d]['iplist'][$i]['count']++;
						$ipfound = true;
					}
				}

				if(!$ipfound)
				{
					$logindata[$d]['iplist'][] = array("ip"=>$logins[$l]['ip_address'],"count"=>1);
				}
			}
		}

		if(!$found)
		{
			// Add a record
			$ld = count($logindata);
			$logindata[$ld] = array();
			$logindata[$ld]['userid'] = $logins[$l]['userid'];
			$logindata[$ld]['username'] = $logins[$l]['username'];
			$logindata[$ld]['logincount'] = 1;
			$logindata[$ld]['iplist'] = array();
			$logindata[$ld]['iplist'][] = array("ip"=>$logins[$l]['ip_address'],"count"=>1);
		}
	}

	usort($logindata,"ipcmp");

	$reply = array();
	$reply['status'] = "success";
	$reply['count'] = count($logindata);
	$reply['data'] = json_encode($logindata);

	return json_encode($reply);
}

function dupeSessions()
{
  global $db;

  $now = time();

	// get all active sessions
	$activeSessions = array();
	$q = "SELECT * FROM sessions WHERE active=1 ORDER BY time_start";
	if($r = $db->query($q))
	{
		for($i=0;$i<$r->num_rows;$i++)
		{
			$activeSessions[$i] = $r->fetch_assoc();
		}
		$r->close();
	}
	else
	{
		$reply = array();
		$reply['status'] = "error";
		$reply['dupeCount'] = 0;
		$reply['duplicates'] = "[]";
		$reply['error'] = "Database Error: ".$db->last_error;
		return json_encode($reply);
	}

	if(count($activeSessions) == 0)
	{
		$reply = array();
		$reply['status'] = "success";
		$reply['comment'] = "No active sessions";
		$reply['dupeCount'] = 0;
		$reply['duplicates'] = "[]";
		return json_encode($reply);
  }

	// Get list of usernames
	$usernames = array();
	$q = "SELECT id,email from users";
	if($r = $db->query($q))
	{
		while($u = $r->fetch_assoc())
		{
			$usernames[$u['id']] = $u['email'];
		}
		$r->close();
	}

  $scount = array();
  for($i=0;$i<count($activeSessions);$i++)
  {
		$found = false;
		for($j=0;$j<count($scount) && !$found;$j++)
		{
			if($scount[$j]['userid'] == $activeSessions[$i]['userid'])
			{
				$found = true;
				$scount[$j]['count']++;
			}
		}

		if(!$found)
		{
			$scount[] = array("userid"=>$activeSessions[$i]['userid'],"count"=>1);
		}
	}

	// now build the list of duplicate sessions
	$dups = array();
	for($i=0;$i<count($scount);$i++)
	{
		if($scount[$i]['count'] > 1)
		{
			// It's a dupe!
			$rec = count($dups);
			$dups[$rec] = array();
			$dups[$rec]['userid'] = $scount[$i]['userid'];
			$dups[$rec]['username'] = $usernames[$scount[$i]['userid']];
			$dups[$rec]['count'] = $scount[$i]['count'];

			$list = array();
			for($j=0;$j<count($activeSessions);$j++)
			{
				if($activeSessions[$j]['userid'] == $scount[$i]['userid'])
				{
					$l = count($list);
					$list[$l] = $activeSessions[$j];

					$start = time() - $list[$l]['time_start'];
					$start = intval($start/60); // Convert to minutes
					$h = intval($start/60); // hours
					$m = $start - ($h * 60); // minutes
					$list[$l]['since_start'] = sprintf("%d:%02d",$h,$m);
 
					$last = time() - $list[$l]['time_lastseen'];
					$last = intval($last/60); // Convert to minutes
					$h1 = intval($last/60); // hours
					$m1 = $last - ($h1 * 60); // minutes
					$list[$l]['since_lastseen'] = sprintf("%d:%02d",$h1,$m1);
				}
			}
			$dups[$rec]['list'] = json_encode($list);
		}
	}	

	if(count($dups) == 0)
	{
		$reply = array();
		$reply['status'] = "success";
		$reply['comment'] = "No duplicate sessions";
		$reply['dupeCount'] = 0;
		$reply['duplicates'] = "[]";
		return json_encode($reply);
	}

	// Create the reply
	$reply = array();
	$reply['status'] = "success";
	$reply['comment'] = "success";
	$reply['dupeCount'] = count($dups);
	$reply['duplicates'] = json_encode($dups);
	return json_encode($reply);
}


?>

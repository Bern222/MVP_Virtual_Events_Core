<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


require_once($root . "/core/modules/dbaccess.php");
require_once($root . "/core/modules/config.php");

// Making these defines makes them global without having to declare them as globals in functions

define("_eshowToken","0cb3dc2e22741e5fd4f2fbfbc1df9aeaa2e6831e584ccda4a13305c0cf4390071edf7f21165c734c79abc453b10d73da");
define("_eshowEndpoint","https://s4.goeshow.com/webservices/eshow/Registration.cfc");

loadConfig();

// We need a list of bookmarks to check for deleted items.

$bookmarks = array();
$q = "SELECT * FROM eshowBookmarks ORDER BY id";
if($result = $db->query($q))
{
  while($ud = $result->fetch_assoc())
  {
    $rec = count($bookmarks);
    $bookmarks[$rec] = $ud;
    $bookmarks[$rec]['found'] = 0; 
  }
  $result->close();
}

echo "Found ".count($bookmarks)." local bookmarks<br>\n";

$addCount = 0;
$updtCount = 0;
$delCount = 0;

echo "Requesting Data ".($page+1)."<br>\n";

$list = esBookmarks($page);

if(isset($list['SUCCESS']))
{
  if($list['SUCCESS'])
  {
    // Loop through retrieved records...
echo "Received ".count($list['ATTENDEES'])." bookmarks<br>\n";
    for($i=0;$i<count($list['ATTENDEES']);$i++)
    {
      $badgeid = $list['ATTENDEES'][$i]['BADGE_ID'];
      $session = $list['ATTENDEES'][$i]['SESSION_CODE'];

      $found = 0;
      $rec = -1;

      for($j=0;$j<count($bookmarks) && found == 0;$j++)
      {
        if($bookmarks[$j]['BADGE_ID'] == $badgeid && $bookmarks[$j]['SESSION_CODE'] == $session)
        {
          $rec = $j;
          $found = $bookmarks[$j]['id'];
          $bookmars[$j]['found'] = 1;
        }
      }

      if($found > 0)
      {
        // Update the record ($found is the record ID)
        $sets = array();

        // Update only if changes
        if($bookmarks[$rec]['FIRST_NAME'] != $list['ATTENDEES'][$i]['FIRST_NAME'])
        {
          $sets[] = sprintf("FIRST_NAME='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['FIRST_NAME']));
        }

        if($bookmarks[$rec]['LAST_NAME'] != $list['ATTENDEES'][$i]['LAST_NAME'])
        {
          $sets[] = sprintf("LAST_NAME='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['LAST_NAME']));
        }

        if($bookmarks[$rec]['KEY_ID'] != $list['ATTENDEES'][$i]['KEY_ID'])
        {
          $sets[] = sprintf("KEY_ID='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['KEY_ID']));
        }

        if($bookmarks[$rec]['TITLE'] != $list['ATTENDEES'][$i]['TITLE'])
        {
          $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['TITLE']));
        }

        if(count($sets) > 0)
        {
          $q = "UPDATE eshowBookmarks SET ".implode(",",$sets).sprintf(" WHERE id=%d",$found);
          $db->query($q);
          $updtCount++;
        }
      }
      else
      {
        // Add new record
        $sets = array();
        $sets[] = sprintf("BADGE_ID='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['BADGE_ID']));
        $sets[] = sprintf("FIRST_NAME='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['FIRST_NAME']));
        $sets[] = sprintf("LAST_NAME='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['LAST_NAME']));
        $sets[] = sprintf("KEY_ID='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['KEY_ID']));
        $sets[] = sprintf("SESSION_CODE='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['SESSION_CODE']));
        $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['TITLE']));

        $q = "INSERT INTO eshowBookmarks SET ".implode(",",$sets);
        $db->query($q);
        $addCount++;
      }
    }

    $delCount = 0;
    for($i=0;$i<count($bookmarks);$i++)
    {
      if($bookmarks[$i]['found'] == 0)
      {
        // Information not in retrieved data, delete from our database
        $q = sprintf("DELETE FROM eshowBookmarks WHERE id=%d",$bookmarks[$i]['id']);
        $db->query($q);
        $delCount++;
      }
    }

    if($delCount > 0)
    {
      echo "-- Deleted ".$delCount." record";
      if($delCount != 1) echo "s";
      echo "<br>\n";
    }

    if($addCount > 0)
    {
      echo "-- Added ".$addCount." record";
      if($addCount != 1) echo "s";
      echo "<br>\n";
    }
   
    if($updtCount > 0)
    {
      echo "-- Updated ".$updtCount." sales response";
      if($updtCount != 1) echo "s";
      echo "<br>\n";
    }
  }
  else
  {
    echo "REQUEST_FAILED<br><br>\n";
  }
}


function esBookmarks()
{
  $elements = array();

  $elements[] = "method=Bookmarks";
  $elements[] = "token="._eshowToken;

  $url = _eshowEndpoint."?".implode("&",$elements);

  $curl_handle = curl_init();

  curl_setopt($curl_handle, CURLOPT_URL,$url); 
  curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2); 
  curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1); 
  //curl_setopt($curl_handle, CURLOPT_USERAGENT, 'WomenOfColorOnline'); 
  curl_setopt($curl_handle, CURLOPT_VERBOSE, true);

  $headers = array();
  $headers[] = "Accept: application/json";
  curl_setopt($curl_handle,  CURLOPT_HTTPHEADER, $headers );

  $response = curl_exec($curl_handle); 
  curl_close($curl_handle); 

//echo $response;

  $data = json_decode($response,1);

  return $data;
}

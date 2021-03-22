<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


require_once($root . "/core/modules/dbaccess.php");
require_once($root . "/core/modules/config.php");

// This script will download and mirror the session list from eShow

// Making these defines makes them global without having to declare them as globals in functions

define("_eshowToken","0cb3dc2e22741e5fd4f2fbfbc1df9aeaa2e6831e584ccda4a13305c0cf4390071edf7f21165c734c79abc453b10d73da");
define("_eshowEndpoint","https://s4.goeshow.com/webservices/eshow/Conference.cfc");
define("_eshowIncludeSponsorLogo",true);

loadConfig();

$sessions = array();
$q = "SELECT * FROM eShowSessions";
if($r = $db->query($q))
{
  for($i=0;$i<$r->num_rows;$i++)
  {
    $sessions[$i] = $r->fetch_assoc();
    $sessions[$i]['found'] = 0;
    if($sessions[$i]['protected'] == 1) $sessions[$i]['found'] = 1;  // Protect this session from being marked inactive.
  }
  $r->close();
}

$pagecount = 1;
for($page=0;$page < $pagecount;$page++)
{
  echo "Requesting page ".($page+1)."<br><br>\n";

  $list = esSessionList($page);

  if(isset($list['SUCCESS']))
  {
    if($list['SUCCESS'])
    {
      // We should have data, set the total number of pages for the loop
      $pagecount = intval($list['TOTAL_PAGES']);

      // Loop through retrieved records...
      for($i=0;$i<count($list['SESSION']);$i++)
      {
        // Check to see if it already exists
        $sessionRecord = -1;
        for($j=0;$j<count($sessions) && $sessionRecord == -1;$j++)
        {
          if($list['SESSION'][$i]['CLASS_KEY'] == $sessions[$j]['CLASS_KEY'])
          {
            $sessionRecord = $j;

            // Set the found flag in the local session array for this session. We'll use that flag later to
            // disable sessions no longer being returned from the api.
            $sessions[$j]['found'] = 1;
          }
        }

        $sets = array();
        if($sessionRecord == -1)
        {
          echo "Adding Session: ".$list['SESSION'][$i]['TITLE']."<br>\n";

          // New record, set all of the fields
          $sets[] = sprintf("ACTIVE=%d",$list['SESSION'][$i]['ACTIVE']);
          $sets[] = sprintf("CE_CREDITS='%s'",$db->real_escape_string($list['SESSION'][$i]['CE_CREDITS']));
          $sets[] = sprintf("CLASS_CODE='%s'",$db->real_escape_string($list['SESSION'][$i]['CLASS_CODE']));
          $sets[] = sprintf("CLASS_END='%s'",$db->real_escape_string($list['SESSION'][$i]['CLASS_END']));
          $sets[] = sprintf("CLASS_KEY='%s'",$db->real_escape_string($list['SESSION'][$i]['CLASS_KEY']));
          $sets[] = sprintf("CLASS_START='%s'",$db->real_escape_string($list['SESSION'][$i]['CLASS_START']));
          $sets[] = sprintf("DESCRIPTION='%s'",$db->real_escape_string($list['SESSION'][$i]['DESCRIPTION']));
          $sets[] = sprintf("DISPLAY_GROUP='%s'",$db->real_escape_string($list['SESSION'][$i]['DISPLAY_GROUP']));
          $sets[] = sprintf("DISPLAY_ORDER=%d",$list['SESSION'][$i]['DISPLAY_ORDER']);
          $sets[] = sprintf("DONOTDISPLAY=%d",$list['SESSION'][$i]['DONOTDISPLAY']);
          $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string($list['SESSION'][$i]['FEE_CODE']));
          $sets[] = sprintf("GLANCE=%d",$list['SESSION'][$i]['GLANCE']);
          $sets[] = sprintf("KEY_ID='%s'",$db->real_escape_string($list['SESSION'][$i]['KEY_ID']));
          $sets[] = sprintf("MAXIMUM_SEATS='%s'",$db->real_escape_string($list['SESSION'][$i]['MAXIMUM_SEATS']));
          $sets[] = sprintf("PAID=%d",$list['SESSION'][$i]['PAID']);
          $sets[] = sprintf("PARENT_KEY='%s'",$db->real_escape_string($list['SESSION'][$i]['PARENT_KEY']));
          $sets[] = sprintf("ROOM='%s'",$db->real_escape_string($list['SESSION'][$i]['ROOM']));
          $sets[] = sprintf("ROOM_CAPACITY='%s'",$db->real_escape_string($list['SESSION'][$i]['ROOM_CAPACITY']));
          $sets[] = sprintf("ROOM_LEVEL='%s'",$db->real_escape_string($list['SESSION'][$i]['ROOM_LEVEL']));
          $sets[] = sprintf("SESSION_CODE='%s'",$db->real_escape_string($list['SESSION'][$i]['SESSION_CODE']));
          $sets[] = sprintf("SESSION_CODE2='%s'",$db->real_escape_string($list['SESSION'][$i]['SESSION_CODE2']));
          $sets[] = sprintf("SESSION_LINK='%s'",$db->real_escape_string($list['SESSION'][$i]['SESSION_LINK']));
          $sets[] = sprintf("SPONSOR='%s'",$db->real_escape_string($list['SESSION'][$i]['SPONSOR']));
          $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($list['SESSION'][$i]['TITLE']));
          $sets[] = sprintf("TRACK='%s'",$db->real_escape_string($list['SESSION'][$i]['TRACK']));
          $sets[] = sprintf("TRACK_KEY='%s'",$db->real_escape_string($list['SESSION'][$i]['TRACK_KEY']));
          $sets[] = sprintf("TYPE='%s'",$db->real_escape_string($list['SESSION'][$i]['TYPE']));
          $sets[] = sprintf("UPDATED='%s'",$db->real_escape_string($list['SESSION'][$i]['UPDATED']));
          $sets[] = sprintf("VENUE='%s'",$db->real_escape_string($list['SESSION'][$i]['VENUE']));

          $speakers = json_encode($list['SESSION'][$i]['SPEAKER']);
          $sets[] = sprintf("SPEAKER='%s'",$db->real_escape_string($speakers));

          $q = "INSERT INTO eShowSessions SET ".implode(",",$sets);
          $db->query($q);
        }
        else
        {
          // Existing record, update status if necessary
          echo "Updating Session: ".$list['SESSION'][$i]['TITLE']."<br>\n";
          $sets[] = sprintf("ACTIVE=%d",$list['SESSION'][$i]['ACTIVE']);
          $sets[] = sprintf("CE_CREDITS='%s'",$db->real_escape_string($list['SESSION'][$i]['CE_CREDITS']));
          $sets[] = sprintf("CLASS_CODE='%s'",$db->real_escape_string($list['SESSION'][$i]['CLASS_CODE']));
          $sets[] = sprintf("CLASS_END='%s'",$db->real_escape_string($list['SESSION'][$i]['CLASS_END']));
          $sets[] = sprintf("CLASS_KEY='%s'",$db->real_escape_string($list['SESSION'][$i]['CLASS_KEY']));
          $sets[] = sprintf("CLASS_START='%s'",$db->real_escape_string($list['SESSION'][$i]['CLASS_START']));
          $sets[] = sprintf("DESCRIPTION='%s'",$db->real_escape_string($list['SESSION'][$i]['DESCRIPTION']));
          $sets[] = sprintf("DISPLAY_GROUP='%s'",$db->real_escape_string($list['SESSION'][$i]['DISPLAY_GROUP']));
          $sets[] = sprintf("DISPLAY_ORDER=%d",$list['SESSION'][$i]['DISPLAY_ORDER']);
          $sets[] = sprintf("DONOTDISPLAY=%d",$list['SESSION'][$i]['DONOTDISPLAY']);
          $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string($list['SESSION'][$i]['FEE_CODE']));
          $sets[] = sprintf("GLANCE=%d",$list['SESSION'][$i]['GLANCE']);
          $sets[] = sprintf("KEY_ID='%s'",$db->real_escape_string($list['SESSION'][$i]['KEY_ID']));
          $sets[] = sprintf("MAXIMUM_SEATS='%s'",$db->real_escape_string($list['SESSION'][$i]['MAXIMUM_SEATS']));
          $sets[] = sprintf("PAID=%d",$list['SESSION'][$i]['PAID']);
          $sets[] = sprintf("PARENT_KEY='%s'",$db->real_escape_string($list['SESSION'][$i]['PARENT_KEY']));
          $sets[] = sprintf("ROOM='%s'",$db->real_escape_string($list['SESSION'][$i]['ROOM']));
          $sets[] = sprintf("ROOM_CAPACITY='%s'",$db->real_escape_string($list['SESSION'][$i]['ROOM_CAPACITY']));
          $sets[] = sprintf("ROOM_LEVEL='%s'",$db->real_escape_string($list['SESSION'][$i]['ROOM_LEVEL']));
          $sets[] = sprintf("SESSION_CODE='%s'",$db->real_escape_string($list['SESSION'][$i]['SESSION_CODE']));
          $sets[] = sprintf("SESSION_CODE2='%s'",$db->real_escape_string($list['SESSION'][$i]['SESSION_CODE2']));
          $sets[] = sprintf("SESSION_LINK='%s'",$db->real_escape_string($list['SESSION'][$i]['SESSION_LINK']));
          $sets[] = sprintf("SPONSOR='%s'",$db->real_escape_string($list['SESSION'][$i]['SPONSOR']));
          $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($list['SESSION'][$i]['TITLE']));
          $sets[] = sprintf("TRACK='%s'",$db->real_escape_string($list['SESSION'][$i]['TRACK']));
          $sets[] = sprintf("TRACK_KEY='%s'",$db->real_escape_string($list['SESSION'][$i]['TRACK_KEY']));
          $sets[] = sprintf("TYPE='%s'",$db->real_escape_string($list['SESSION'][$i]['TYPE']));
          $sets[] = sprintf("UPDATED='%s'",$db->real_escape_string($list['SESSION'][$i]['UPDATED']));
          $sets[] = sprintf("VENUE='%s'",$db->real_escape_string($list['SESSION'][$i]['VENUE']));

          $speakers = json_encode($list['SESSION'][$i]['SPEAKER']);
          $sets[] = sprintf("SPEAKER='%s'",$db->real_escape_string($speakers));

          $q = "UPDATE eShowSessions SET ".implode(",",$sets).sprintf(" WHERE id=%d",$sessions[$sessionRecord]['id']);
          $db->query($q);
        }
        echo "<br>\n";       
      }
    }
    else
    {
      echo "REQUEST_FAILED<br><br>\n";
      foreach($list as $key => $value)
      {
        echo $key.":".$value."<br>\n";
      }
    }
  }
}

echo "Checking for deleted sessions...<br>\n";
$disablecount = 0;
for($i = 0;$i <count($sessions);$i++)
{
  if($sessions[$i]['found'] == 0 and $sessions[$i]['ACTIVE'] == 1)
  {
    $q = sprintf("UPDATE eShowSessions SET ACTIVE=0 WHERE id=%d",$sessions[$i]['id']);
    $db->query($q);
    $disablecount++;
  }
}
echo "Disabled ".$disablecount." Session(s).<br>\n";

unset($sessions); // Free up the memory from the data

$speakers = array();
$q = "SELECT * FROM eShowSpeakers";
if($r = $db->query($q))
{
  for($i=0;$i<$r->num_rows;$i++)
  {
    $speakers[$i] = $r->fetch_assoc();
  }
  $r->close();
}
echo "Speaker Count: ".count($speakers)."<br>\n";

$pagecount = 1;
for($page=0;$page < $pagecount;$page++)
{
  echo "Requesting page ".($page+1)."<br><br>\n";

  $list = esSpeakerList($page);

  if(isset($list['SUCCESS']))
  {
    if($list['SUCCESS'])
    {
      // We should have data, set the total number of pages for the loop
      $pagecount = intval($list['TOTAL_PAGES']);

      // Loop through retrieved records...
      for($i=0;$i<count($list['SPEAKERS']);$i++)
      {
        // Check to see if it already exists
        $speakerRecord = -1;
        for($j=0;$j<count($speakers) && $speakerRecord == -1;$j++)
        {
          if($list['SPEAKERS'][$i]['GUID'] == $speakers[$j]['GUID'])
          {
            $speakerRecord = $j;
          }
        }

        $sets = array();
        $sets[] = sprintf("GUID='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['GUID']));
        $sets[] = sprintf("LAST_NAME='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['LAST_NAME']));
        $sets[] = sprintf("FIRST_NAME='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['FIRST_NAME']));
        $sets[] = sprintf("MIDDLE_NAME='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['MIDDLE_NAME']));
        $sets[] = sprintf("COMPANY_NAME='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['COMPANY_NAME']));
        $sets[] = sprintf("ADDRESS1='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['ADDRESS1']));
        $sets[] = sprintf("ADDRESS2='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['ADDRESS2']));
        $sets[] = sprintf("CITY='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['CITY']));
        $sets[] = sprintf("STATE='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['STATE']));
        $sets[] = sprintf("ZIP='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['ZIP']));
        $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['TITLE']));
        $sets[] = sprintf("EMAIL='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['EMAIL']));
        $sets[] = sprintf("PHONE='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['PHONE']));
        $sets[] = sprintf("WORK_PHONE='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['WORK_PHONE']));
        $sets[] = sprintf("COUNTRY='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['COUNTRY']));
        $sets[] = sprintf("MEMBER_NUMBER='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['MEMBER_NUMBER']));
        $sets[] = sprintf("CREDENTIALS='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['CREDENTIALS']));
        $sets[] = sprintf("CUSTOM_MEMBER_NUMBER='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['CUSTOM_MEMBER_NUMBER']));
        $sets[] = sprintf("DISPLAY_PROPERTY='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['DISPLAY_PROPERTY']));
        $sets[] = sprintf("PROFILE_PICTURE='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['PROFILE_PICTURE']));
        $sets[] = sprintf("SALUTATION='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['SALUTATION']));
        $sets[] = sprintf("TWITTER_HANDLE='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['TWITTER_HANDLE']));
        $sets[] = sprintf("UPDATED='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['UPDATED']));
        $sets[] = sprintf("WEBSITE='%s'",$db->real_escape_string($list['SPEAKERS'][$i]['WEBSITE']));

        $qdata = json_encode($list['SPEAKERS'][$i]['QUESTION']);
        $sets[] = sprintf("QUESTION='%s'",$db->real_escape_string($qdata));

        if($speakerRecord == -1)
        {
          echo "Adding Speaker: ".$list['SPEAKERS'][$i]['FIRST_NAME']." ".$list['SPEAKERS'][$i]['LAST_NAME']."<br>\n";
          $q = "INSERT INTO eShowSpeakers SET ".implode(",",$sets);
          $db->query($q);
        }
        else
        {
          // Existing record, update status if necessary
          echo "Updating Session: ".$list['SPEAKERS'][$i]['FIRST_NAME']." ".$list['SPEAKERS'][$i]['LAST_NAME']."<br>\n";
          $q = "UPDATE eShowSpeakers SET ".implode(",",$sets).sprintf(" WHERE id=%d",$speakers[$speakerRecord]['id']);
          $db->query($q);
        }

        echo "<br>\n";       
      }
    }
    else
    {
      echo "REQUEST_FAILED<br><br>\n";
      foreach($list as $key => $value)
      {
        echo $key.":".$value."<br>\n";
      }
    }
  }
}













function esSessionList($page=0)
{
  $elements = array();

  $elements[] = "method=Session_list";
  $elements[] = "token="._eshowToken;

  if(_eshowIncludeSponsorLogo) $elements[] = "IncludeSponsorLogo=true";

  if($page != 0) $elements[] = sprintf("Page=%d",($page+1));

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

  $data = json_decode($response,1);

  return $data;
}

function esSpeakerList($page=0)
{
  $elements = array();

  $elements[] = "method=Speaker_list";
  $elements[] = "token="._eshowToken;

  if($page != 0) $elements[] = sprintf("Page=%d",($page+1));

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

  $data = json_decode($response,1);

  return $data;
}


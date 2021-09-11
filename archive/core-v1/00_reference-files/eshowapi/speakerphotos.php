<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


require_once($root . "/core/modules/dbaccess.php");
require_once($root . "/core/modules/config.php");

// Making these defines makes them global without having to declare them as globals in functions

loadConfig();

define("_eshowSpeakerPath",$config['speaker_path']);

// We need a list of users to check for disable status.
// We ignore records marked as admin and those that don't have
// a unique code (Badge ID) assigned.

$userlist = array();
$q = "SELECT * FROM eShowSpeakers ORDER BY id";
if($result = $db->query($q))
{
  while($ud = $result->fetch_assoc())
  {
    $pp = trim($ud['PROFILE_PICTURE']);
/*
    if($pp == '')
    {
      $q = json_decode($ud['QUESTION'],true);
      for($i=0;$i<count($q);$i++)
      {
        if($q[$i]['CODE'] == 'PHOTO')
        {
          $pp = trim($q[$i]['RESPONSE']);
          $i = count($q);
        }
      }
    }
*/

    if($pp == "") continue;
    if(trim($ud['PROFILE_PICTURE']) == '') $ud['PROFILE_PICTURE'] = $pp;

    $rec = count($userlist);
    $userlist[$rec] = $ud;
    $userlist[$rec]['found'] = 0; 
  }
  $result->close();
}

echo "Found ".count($userlist)." Speakers<br>\n";

// Clear the LOCAL_PICTURE field
$db->query("UPDATE eShowSpeakers SET LOCAL_PICTURE=''");

for($r=0;$r<count($userlist);$r++)
//for($r=0;$r<3;$r++)
{
  echo "-------------<br>\n";
  echo "File #".$r."<br>\n";
  //$filename = _eshowSpeakerPath."/".sprintf("%05d.pdf",$userlist[$i]['id']);
  $url = $userlist[$r]['PROFILE_PICTURE'];

  $curl_handle = curl_init();

  curl_setopt($curl_handle, CURLOPT_URL,$url); 
  curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2); 
  curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($curl_handle, CURLOPT_HEADER, 1); // return HTTP headers with response
  curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
  //curl_setopt($curl_handle, CURLOPT_USERAGENT, 'WomenOfColorOnline'); 
  curl_setopt($curl_handle, CURLOPT_VERBOSE, true);

  $headers = array();
  $headers[] = 'Accept: */*';
  curl_setopt($curl_handle,  CURLOPT_HTTPHEADER, $headers );

  $response = curl_exec($curl_handle); 
  if($response === false)
  {
    echo "Error: ".curl_error($curl_handle)."<br>\n";;
  }
  else
  {
    // Save the file.
    $contenttype = "";
    $headers = array();

    list($rawheaders, $resp) = explode("\r\n\r\n", $response, 2);

    $headerlist = explode("\n",$rawheaders);
    for($i=0;$i<count($headerlist);$i++)
    {
      $hd = explode(":",$headerlist[$i]);
      if(count($hd) == 2)
      {
        $headers[trim($hd[0])] = trim($hd[1]);
      }
    }
      
    $url = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
    $headerlist = explode("\n",$url);
    $params = explode('/', $url);
    $fn = $params[count($params) - 1];
    $f = explode("?",$fn);
    $fileNameFromHeader = $f[0];

    echo "Original File Name: ".$fileNameFromHeader."<br>\n";

    $fnparts = explode(".",$fileNameFromHeader);

    $ext = $fnparts[count($fnparts)-1];
    $destination = $config['speaker_path']."/".sprintf("%05d",$userlist[$r]['id']).".".$ext;
    $filename = "speakers/".sprintf("%05d",$userlist[$r]['id']).".".$ext;

    echo "Content-Length: ".$headers['Content-Length']."<br>\n";
    echo "Content-Type: ".$headers['Content-Type']."<br>\n";
    echo "Destination: ".$destination."<br>\n";
    $filesize = intval($headers['Content-Length']);
    echo "File Size: ".$filesize."<br>\n";

    if(file_exists($destination)) unlink($destination);
    $handle = fopen($destination,"wb");
    if($handle !== false)
    {
      fwrite($handle,$resp,$filesize);
      fclose($handle);
    }

    $query = sprintf("UPDATE eShowSpeakers SET LOCAL_PICTURE='%s' WHERE id=%d",$db->real_escape_string($filename),$userlist[$r]['id']);
    $db->query($query);
  }

  curl_close($curl_handle); 
}

<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


require_once($root . "/core/modules/dbaccess.php");
require_once($root . "/core/modules/config.php");

// Making these defines makes them global without having to declare them as globals in functions

loadConfig();

define("_eshowResumePath",$config['resume_path']);

// We need a list of users to check for disable status.
// We ignore records marked as admin and those that don't have
// a unique code (Badge ID) assigned.

$userlist = array();
$q = "SELECT id,admin,disabled,unique_code FROM users WHERE disabled=0 ORDER BY id";
if($result = $db->query($q))
{
  while($ud = $result->fetch_assoc())
  {
    if(trim($ud['unique_code']) == '') continue;
    if($ud['admin'] == 1) continue;

    $rec = count($userlist);
    $userlist[$rec] = $ud;
    $userlist[$rec]['found'] = 0; 
  }
  $result->close();
}

echo "Found ".count($userlist)." enabled users<br>\n";

// Get a resume listing
$resumelist = array();
$q = "SELECT * FROM eShowQuestions where KEY_ID='05931DC7-62CA-42E0-A7D6-5891E5445497' and ANSWER != ''";
if($result = $db->query($q))
{
  while($rd = $result->fetch_assoc())
  {
    for($i=0;$i<count($userlist);$i++)
    {
      if($userlist[$i]['id'] == $rd['userid'])
      {
        $rec = count($resumelist);
        $resumelist[$rec] = $rd;
        $resumelist[$rec]['unique_code'] = $userlist[$i]['unique_code'];
      }
    }
  }
  $result->close();
}
echo "Found ".count($resumelist)." resume records<br>\n";

for($r=0;$r<count($resumelist);$r++)
//for($r=0;$r<10;$r++)
{
  echo "-------------<br>\n";
  echo "File #".$r."<br>\n";
  $filename = _eshowResumePath."/".sprintf("%05d.pdf",$resumelist[$i]['id']);
  $url = $resumelist[$r]['ANSWER'];

  $curl_handle = curl_init();

  curl_setopt($curl_handle, CURLOPT_URL,$url); 
  curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2); 
  curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($curl_handle, CURLOPT_HEADER, 1); // return HTTP headers with response
  curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
  //curl_setopt($curl_handle, CURLOPT_USERAGENT, 'WomenOfColorOnline'); 
  curl_setopt($curl_handle, CURLOPT_VERBOSE, true);

  $headers = array();
  $headers[] = "Accept: */*";
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
    // $url = 'http://stackoverflow.com/questions/4091203/how-can-i-get-file-name-from-header-using-curl-in-php'; 
    $params = explode('/', $url);
    $fn = $params[count($params) - 1];
    $f = explode("?",$fn);
    $fileNameFromHeader = $f[0];

    echo "Original File Name: ".$fileNameFromHeader."<br>\n";

    $fnparts = explode(".",$fileNameFromHeader);

    $ext = $fnparts[count($fnparts)-1];

    $destination = $config['resume_folder']."/".trim($resumelist[$r]['unique_code']).".".$ext;
    $filename = trim($resumelist[$r]['unique_code']).".".$ext;

    echo "Content-Length: ".$headers['Content-Length']."<br>\n";
    echo "Content-Type: ".$headers['Content-Type']."<br>\n";
    echo "Destination: ".$destination."<br>\n";
    $filesize = intval($headers['Content-Length']);
    echo "File Size: ".$filesize."<br>\n";
    echo "User ID: ".$resumelist[$r]['userid']."<br><br>\n";

    if(file_exists($destination)) unlink($destination);
    $handle = fopen($destination,"wb");
    if($handle !== false)
    {
      fwrite($handle,$resp,$filesize);
      fclose($handle);
    }

    $sets = array();
    $sets[] = sprintf("resume_filename='%s'",$db->real_escape_string($filename));
    $sets[] = sprintf("resume_mime='%s'",$db->real_escape_string($headers['Content-Type']));
    $q = "UPDATE users SET ".implode(",",$sets).sprintf(" WHERE id=%d",$resumelist[$r]['userid']);
    $db->query($q);
  }

  curl_close($curl_handle); 
}

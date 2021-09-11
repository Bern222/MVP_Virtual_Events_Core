<?php
// Back end processing for login module
ini_set("session.gc_maxlifetime",86400);
ini_set("session.save_path","/home/mvpvirtualevents/sessiondata");

// set local time zone if needed
if(function_exists("date_default_timezone_set"))
{
  date_default_timezone_set('America/New_York');
}

session_start();

require_once("dbaccess.php");
require_once("config.php");
require_once("sessions.php");
require_once("users.php");
require_once("../libs/phpMailer/PHPMailerAutoload.php");

loadConfig();

// Making these defines makes them global without having to declare them as globals in functions

// define("_eshowToken","0cb3dc2e22741e5fd4f2fbfbc1df9aeaa2e6831e584ccda4a13305c0cf4390071edf7f21165c734c79abc453b10d73da");
// define("_eshowEndpoint","https://s4.goeshow.com/webservices/eshow/Registration.cfc");
// define("_eshowIncludeSurvey",true);
// define("_eshowIncludeStatus",true);
// define("_eshowIncludeBooth",false);
// define("_eshowIncludePrimarySales",false); // Cannot be used with  _eshowIncludeAllSales
// define("_eshowIncludeAllSales",true);  //  Cannot be used with _eshowIncludePrimarySales
// define("_eshowDownloadResume",false); // Download resume if it exists
// define("_eshowSearchByEmail",true); // Search by Email address
//define("_eshowResumePath",$config['resume_path']);

timeoutSessions();

$method = $_POST['method'];

switch($method){
	  case "processLogin": echo processLogin();
      break;
    case "processLogout": echo processLogout();
      break;
	  case "lostPasswordEmail": echo lostPasswordEmail();
		  break;
	  case "changePassword": echo changePassword();
      break;
    case "updatePassword": echo updatePassword();
      break;
    case "getAllSales": echo getAllSales();
      break;
    case "getAccessByDay": echo getAccessByDay();
      break;
    case "logAccess": echo logAccess();
      break;
}

function processLogin()
{
  global $userdata; // On successful validation, this array will be populated with user data

  $username="";
  $password="";

  if(isset($_POST['username'])) $username=$_POST['username'];
  if(isset($_POST['password'])) $password=$_POST['password'];

  $status = userVerify($username,$password);

  // if($status == _USER_UNKNOWN)
  // {
  //   // We need to check with eShow
  //   $userid = checkeShow($username,$password);
  //   if($userid != 0)
  //   {
  //     // User found, call verify again
  //     $status = userVerify($username,$password);
  //   }
  // }

  if($status <= 0)
  {
    // An error occurred
    $error = "Unknown Error";
    switch($status)
    {
      case _USER_MISSING     : // Either user name or password missing
                               $error = "You must enter both an email address and a password to log in.";
                               break;
      case _USER_UNKNOWN     : // Unknown User
      case _USER_BADPASSWORD : // Invalid Password
                               $error = "User Email or Password is invalid, please try again.";
                               break;
      case _USER_INVALIDATED : // Account not validated
                               $error = "Your account has not been validated by the administrator.";
                               break;
      case _USER_DISABLED    : // Account disabled
                               $error = "Your account has been disabled.";
                               break;
      case _USER_DBERROR     : // Database error
                               $error = "A database error has occurred. Please try again later.";
                               break;
      default                : // Unknown Error
                               $error = "Unknown Error. Please try again later.";
                               break;
    }
    return $error;
  }

  // Successful validataion, create a session now.
  $sessionid = createSession($userdata['id']);

  if($sessionid == "")
  {
    // Session creation failed
    return "Unable to create session";
  }

  // If we get here, the user has been verified and a login session has been created
  // in the sessions table.  The following $_SESSION variables should now be set:
  // sessionid = The session id as a random character string
  // userid = The user id number from the id field in the user table.
  // logged_in = 1 if successfully logged in.

  return "Success!";
}

// This function will check for the individual and add them "on the fly" if needed
// function checkeShow($username,$password)
// {
//   global $db;
//   global $userdata;
//   global $config;

//   $uid = 0; // user ID number

//   $elements = array();

//   $elements[] = "method=Registration_list";
//   $elements[] = "token="._eshowToken;

//   if(_eshowIncludeSurvey) $elements[] = "IncludeSurvey=true";
//   if(_eshowIncludeStatus) $elements[] = "IncludeStatus=true";
//   if(_eshowIncludeBooth) $elements[] = "IncludeBooth=true";
//   if(_eshowIncludePrimarySales) $elements[] = "IncludePrimarySales=true";
//   if(_eshowIncludeAllSales) $elements[] = "IncludeAllSales=true";
//   $elements[] = "reducedSizePhoto=true";

//   if(_eshowSearchByEmail)
//   {
//     $elements[] = sprintf("searchByEmail=%s",$username);
//   }
//   else
//   {
//     $elements[] = sprintf("SearchByBadgeID=%s",$password);
//   }

//   if($page != 0) $elements[] = sprintf("Page=%d",($page+1));

//   $url = _eshowEndpoint."?".implode("&",$elements);

//   $curl_handle = curl_init();

//   curl_setopt($curl_handle, CURLOPT_URL,$url); 
//   curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2); 
//   curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1); 
//   //curl_setopt($curl_handle, CURLOPT_USERAGENT, 'WomenOfColorOnline'); 
//   curl_setopt($curl_handle, CURLOPT_VERBOSE, true);

//   $headers = array();
//   $headers[] = "Accept: application/json";
//   curl_setopt($curl_handle,  CURLOPT_HTTPHEADER, $headers );

//   $response = curl_exec($curl_handle); 
//   curl_close($curl_handle); 

//   $list = json_decode($response,1);

//   if(isset($list['SUCCESS']))
//   {
//     if($list['SUCCESS'])
//     {
//       // We should have data, set the total number of pages for the loop (should always be 1 for this call)
//       $pagecount = intval($list['TOTAL_PAGES']);

//       // Loop through retrieved records...
//       for($i=0;$i<count($list['ATTENDEES']);$i++)
//       {
//         $resumeURL = "";

//         $email = $list['ATTENDEES'][$i]['EMAIL'];

//         // Check to see if it already exists (if we got here, it should not!)
//         $ud = array("id"=>0);
//         $q = sprintf("SELECT * FROM users WHERE email='%s';",$db->real_escape_string($email));
//         if($r = $db->query($q))
//         {
//           if($r->num_rows > 0)
//           {
//             $ud = $r->fetch_assoc();
//           }
//           $r->close();
//         }

//         if($ud['id'] != 0)
//         {
//           // User already exists, return the user ID (how did we get here?)
//           return($ud['id']);
//         }

//         // New record, set all of the fields
//         $sets = array();

//         $sets[] = sprintf("email='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['EMAIL']));

//         $pw = $list['ATTENDEES'][$i]['BADGE_ID'];
//         if($config['login_encrypt_pw'] == 1)
//         {
//           $sets[] = sprintf("password='%s'",$db->real_escape_string(password_hash($pw,PASSWORD_DEFAULT)));
//           $sets[] = "password_encrypted=1";
//         }
//         else
//         {
//           $sets[] = sprintf("password='%s'",$db->real_escape_string($pw));
//           $sets[] = "password_encrypted=0";
//         }
//         $sets[] = sprintf("firstname='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['FIRST_NAME']));
//         $sets[] = sprintf("lastname='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['LAST_NAME']));
//         $sets[] = sprintf("company='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['COMPANY_NAME']));

//         if($list['ATTENDEES'][$i]['STATUS'] == "Approved")
//         {
//           $sets[] = "validated=1";
//         }
//         else
//         {
//           switch($list['ATTENDEES'][$i]['STATUS'])
//           {
//             case "Pending"   : break; // Just don't validate
//             case "Rejected"  : 
//             case "Cancelled" : $sets[] = "disabled=1"; break;
//             default          : break;
//           }
//         }
//         $cu = $list['ATTENDEES'][$i]['FIRST_NAME'].substr($list['ATTENDEES'][$i]['LAST_NAME'],0,1);
//         $sets[] = sprintf("chat_username='%s'",$db->real_escape_string($cu));
//         $sets[] = sprintf("avatar='%s'",$db->real_escape_string("user1.jpg"));
//         $sets[] = sprintf("unique_code='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['BADGE_ID']));

//         $q = "INSERT INTO users SET ".implode(",",$sets);
//         $uid = 0;
//         if($db->query($q))
//         {
//           // Query successful
//           $uid = $db->insert_id;
//           $ud['id'] = $uid;
//         }

//         if($uid != 0)
//         {
//           if(_eshowIncludeSurvey)
//           {
//             $questions = $list['ATTENDEES'][$i]['QUESTION'];
//             // Store the question array
//             for($j=0;$j<count($questions);$j++)
//             {
//               $sets = array();
//               $sets[] = sprintf("userid=%d",$uid);
//               $sets[] = sprintf("CODE='%s'",$db->real_escape_string($questions[$j]['CODE']));
//               $sets[] = sprintf("ANSWER='%s'",$db->real_escape_string($questions[$j]['ANSWER']));
//               $sets[] = sprintf("KEY_ID='%s'",$db->real_escape_string($questions[$j]['KEY_ID']));
//               $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($questions[$j]['TITLE']));
//               $q = "INSERT INTO eShowQuestions SET ".implode(",",$sets);
//               $db->query($q);

//               if(_eshowDownloadResume)
//               {
//                 // see if it's the resume field
//                 if($questions[$j]['KEY_ID'] == "05931DC7-62CA-42E0-A7D6-5891E5445497")
//                 {
//                   if($questions[$j]['ANSWER'] != "") $resumeURL = $questions[$j]['ANSWER']; 
//                 }
//               }
//             }
//           }                  

//           if(_eshowIncludePrimarySales)
//           {
//             $sales = $list['ATTENDEES'][$i]['SALES_ITEMS_INCLUDE_PRIMARY'];
//             // Store the info array
//             for($j=0;$j<count($sales);$j++)
//             {
//               $sets = array();
//               $sets[] = sprintf("userid=%d",$uid);
//               $sets[] = sprintf("GL_CODE='%s'",$db->real_escape_string($sales[$j]['GL_CODE']));
//               $sets[] = sprintf("CATEGORY_KEY='%s'",$db->real_escape_string($sales[$j]['CATEGORY_KEY']));
//               $sets[] = sprintf("AMOUNT='%s'",$db->real_escape_string($sales[$j]['AMOUNT']));
//               $sets[] = sprintf("QTY='%s'",$db->real_escape_string($sales[$j]['QTY']));
//               $sets[] = sprintf("GUID='%s'",$db->real_escape_string($sales[$j]['GUID']));
//               $sets[] = sprintf("PRIMARYFEE='%s'",$db->real_escape_string($sales[$j]['PRIMARYFEE']));
//               $sets[] = sprintf("ITEM_CODE='%s'",$db->real_escape_string($sales[$j]['ITEM_CODE']));
//               $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($sales[$j]['TITLE']));
//               $sets[] = sprintf("PRODUCTGUID='%s'",$db->real_escape_string($sales[$j]['PRODUCTGUID']));
//               $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string($sales[$j]['FEE_CODE']));
//               $sets[] = sprintf("SALESDATE='%s'",$db->real_escape_string($sales[$j]['SALESDATE']));
//               $q = "INSERT INTO eShowSalesItemsShowPrimary SET ".implode(",",$sets);
//               $db->query($q);
//             }
//           }  
            
//           if(_eshowIncludeAllSales)
//           {
//             $sales = $list['ATTENDEES'][$i]['SALES_ITEMS_INCLUDE_ALL_SALES'];
//             // Store the info array
//             for($j=0;$j<count($sales);$j++)
//             {
//               $sets = array();
//               $sets[] = sprintf("userid=%d",$uid);
//               $sets[] = sprintf("GL_CODE='%s'",$db->real_escape_string($sales[$j]['GL_CODE']));
//               $sets[] = sprintf("CATEGORY_KEY='%s'",$db->real_escape_string($sales[$j]['CATEGORY_KEY']));
//               $sets[] = sprintf("AMOUNT='%s'",$db->real_escape_string($sales[$j]['AMOUNT']));
//               $sets[] = sprintf("QTY='%s'",$db->real_escape_string($sales[$j]['QTY']));
//               $sets[] = sprintf("GUID='%s'",$db->real_escape_string($sales[$j]['GUID']));
//               $sets[] = sprintf("PRIMARYFEE='%s'",$db->real_escape_string($sales[$j]['PRIMARYFEE']));
//               $sets[] = sprintf("ITEM_CODE='%s'",$db->real_escape_string($sales[$j]['ITEM_CODE']));
//               $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($sales[$j]['TITLE']));
//               $sets[] = sprintf("PRODUCTGUID='%s'",$db->real_escape_string($sales[$j]['PRODUCTGUID']));
//               $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string($sales[$j]['FEE_CODE']));
//               $sets[] = sprintf("SALESDATE='%s'",$db->real_escape_string($sales[$j]['SALESDATE']));
//               $q = "INSERT INTO eShowSalesItemsIncludeAllSales SET ".implode(",",$sets);
//               $db->query($q);
//             }
//           }  

//           if(_eshowDownloadResume)
//           {
//             if($resumeURL != "")
//             {
//               $curl_handle = curl_init();

//               curl_setopt($curl_handle, CURLOPT_URL,$resumeURL); 
//               curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2); 
//               curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1); 
//               curl_setopt($curl_handle, CURLOPT_HEADER, 1); // return HTTP headers with response
//               curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
//               //curl_setopt($curl_handle, CURLOPT_USERAGENT, 'WomenOfColorOnline'); 
//               curl_setopt($curl_handle, CURLOPT_VERBOSE, true);

//               $headers = array();
//               $headers[] = "Accept: */*";
//               curl_setopt($curl_handle,  CURLOPT_HTTPHEADER, $headers );

//               $response = curl_exec($curl_handle); 
//               if(curl_errno == 0)
//               {
//                 // Save the file.
//                 $headers = array();

//                 list($rawheaders, $resp) = explode("\r\n\r\n", $response, 2);
//                 $headerlist = explode("\n",$rawheaders);
//                 for($j=0;$j<count($headerlist);$j++)
//                 {
//                   $hd = explode(":",$headerlist[$j]);
//                   if(count($hd) == 2)
//                   {
//                     $headers[trim($hd[0])] = trim($hd[1]);
//                   }
//                 }
      
//                 $url = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
//                 $headerlist = explode("\n",$url);
//                 // $url = 'http://stackoverflow.com/questions/4091203/how-can-i-get-file-name-from-header-using-curl-in-php'; 
//                 $params = explode('/', $url);
//                 $fn = $params[count($params) - 1];
//                 $f = explode("?",$fn);
//                 $fileNameFromHeader = $f[0];

//                 $fnparts = explode(".",$fileNameFromHeader);

//                 $ext = $fnparts[count($fnparts)-1];

//                 $destination = $config['resume_folder']."/".trim($list['ATTENDEES'][$i]['BADGE_ID']).".".$ext;
//                 $filename = trim($list['ATTENDEES'][$i]['BADGE_ID']).".".$ext;
//                 $filesize = intval($headers['Content-Length']);

//                 if(file_exists($destination)) unlink($destination);
//                 $handle = fopen($destination,"wb");
//                 if($handle !== false)
//                 {
//                   fwrite($handle,$resp,$filesize);
//                   fclose($handle);
//                 }

//                 // Update the user table
//                 $sets = array();
//                 $sets[] = sprintf("resume_filename='%s'",$db->real_escape_string($filename));
//                 $sets[] = sprintf("resume_mime='%s'",$db->real_escape_string($headers['Content-Type']));
//                 $q = "UPDATE users SET ".implode(",",$sets).sprintf(" WHERE id=%d",$uid);
//                 $db->query($q);
//               }

//               curl_close($curl_handle); 
//             }
//           }  // post add 
//         }  // user Added, additional additions
//       }  // for i loop
//     }  // success true
//   }  // isset success
//   return $uid;
// }

function processLogout() 
{
  expireSession($_SESSION['userid'], $_SESSION['session_id']);
  session_unset();
}

/*****************************************************************************/
/* Function: lostPasswordEmail                                               */
/*                                                                           */
/* Description: This function will send an email to a user that has requested*/
/*              a lost password. The email will contain a link that will     */
/*              allow the user to reset their password.                      */
/*                                                                           */
/*              The link will have a code that is stored in the              */
/*              password_tokens table with and expiration timestamp.         */
/*****************************************************************************/

function lostPasswordEmail()
{
  global $db;
  global $config;

  $username="";
  if(isset($_POST['username'])) $username=trim($_POST['username']);

  if($username == "")
  {
    return "No Email Provided";
  }

  // We have a user name (aka email), see if it exists in the users table
  // and has been validated and not disabled.
  $criteria = array();
  $criteria[] = sprintf("email='%s'",$db->real_escape_string($username));
  $criteria[] = "validated=1";
  $criteria[] = "disabled=0";

  $ud = array("id"=>0); // set a default value for user id


  $q = "SELECT * FROM users WHERE ".implode(" and ",$criteria);

  if($r = $db->query($q))
  {
    if($r->num_rows > 0)
    {
      $ud = $r->fetch_assoc();
    }
    $r->close();
  }
  else
  {
    return "DBError:\nQuery: ".$q."\nError: ".$db->error;
  }
  
  if($ud['id'] == 0)
  {
    return "Invalid Email";
  }

  // Ok, we have a user record to work with. Build a id key string

  $keyok=0;
  $key = "";

  $length=$config['lostpw_keylength'];

  // define possible characters
  $possible = "abcdefghjklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ_";

  // Now generate a code value
  $maxattempts = 20;

  while(!$keyok && $maxattempts > 0)
  {
    $key = "";
    // add random characters to id until length is reached
    while (strlen($key) < $length)
    {
      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      $key .= $char;
    }

    // Query the access_token database to make sure the code hasn't been used.

    $q = sprintf("SELECT * FROM password_tokens WHERE token=BINARY '%s'",$db->real_escape_string($key));
    if($r = $db->query($q))
    {
      if($r->num_rows == 0) $keyok=1;
      $r->close();
    }

    $maxattempts--;
  }

  if($keyok == 0)
  {
    return "Key Generation Error";
  }

  // We have a unique key, store it in the database
  $expire = time() + ($config['lostpw_expire'] * 60);
  $sets = array();
  $sets[] = sprintf("userid=%d",intval($ud['id']));
  $sets[] = sprintf("token='%s'",$db->real_escape_string($key));
  $sets[] = sprintf("expiration=%d",intval($expire));
  $sets[] = sprintf("request_ip_address='%s'",$db->real_escape_string($_SERVER['REMOTE_ADDR']));
  $sets[] = "used=0";

  $q = "INSERT INTO password_tokens SET ".implode(",",$sets);
  $db->query($q);

  // Now we need to send the email to the address provided

  $htmldata = "";
  $htmlfilename = $config['email_template_path']."email-templates/lostpassword.html";
  if(file_exists($htmlfilename))
  {
    $htmldata = file_get_contents($htmlfilename);
  }
 
  $textdata = "";
  $textfilename = $config['email_template_path']."email-templates/lostpassword.txt";
  if(file_exists($textfilename))
  {
    $textdata = file_get_contents($textfilename);
  }

  if($htmldata == "" && $textdata == "")
  {
    return "Template Error";
  }

  // Initiate Mailer Class
  $mail = new PHPMailer;
  $mail->SMTPDebug = 0;  // Enable verbose debug output 
  $mail->Timeout = 60;

  $mail->isSMTP(); // Set mailer to use SMTP 
  $mail->Host = $config['email_server'];
  $mail->SMTPAuth = true; // Enable SMTP authentication 
  $mail->Username = $config['email_username']; 
  $mail->Password = $config['email_password']; 
  $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
  $mail->Port = $config['email_port']; // TCP port to connect to

  // Source
  $mail->setFrom($config['lostpw_email'],$config['lostpw_name']); 
  $mail->addReplyTo($config['lostpw_email'],$config['lostpw_name']);
  //$mail->AddEmbeddedImage("../email_assets/IgniteExperienceEmailHeader.png", "header", "IgniteExperienceEmailHeader.png");
  //$mail->AddEmbeddedImage("../email_assets/IgniteExperienceEmailFooter.png", "footer", "IgniteExperienceEmailFooter.png");

  $mail->Subject = $config['lostpw_subject']; 

  if($htmldata != "")
  {
    $mail->isHTML(true); // Set email format to HTML.
    $html = $htmldata;
    $html = str_replace("[TEST_URL]",$config['test_url'],$html);
    $html = str_replace("[BASE_URL]",$config['base_url'],$html);
    $html = str_replace("[RECOVERKEY]",$key,$html);
    $html = str_replace("[EXPIRE_TIME]",date("g:i A",$expire),$html);
    $mail->Body = $html;

    if($textdata != "")
    {
      $text = $textdata;
      $text = str_replace("[TEST_URL]",$config['test_url'],$text);
      $text = str_replace("[BASE_URL]",$config['base_url'],$text);
      $text = str_replace("[RECOVERKEY]",$key,$text);
      $text = str_replace("[EXPIRE_TIME]",date("g:i A",$expire),$text);
      $mail->AltBody = $text;
    }
  }
  else
  {
    $mail->isHTML(false); // Set email format to HTML.
    if($textdata != "")
    {
      $text = $textdata;
      $text = str_replace("[TEST_URL]",$config['test_url'],$text);
      $text = str_replace("[BASE_URL]",$config['base_url'],$text);
      $text = str_replace("[RECOVERKEY]",$key,$text);
      $text = str_replace("[EXPIRE_TIME]",date("g:i A",$expire),$text);
      $mail->Body = $text;
    }
  }
 
  $mailtoname = $ud['firstname']." ".$ud['lastname'];
  $mailtoemail = $ud['email'];

  // Remove any existing addresses or attachments
  $mail->clearAddresses();
  //$mail->clearAttachments();

  // Add recipient
  $mail->addAddress($mailtoemail,$mailtoname); // Add Primary recipient

  if(!$mail->send())
  {
    return "Email Send Error.";
  }
  else
  {
    return "Success!";
  }
}





/*****************************************************************************/
/* Function: changePassword                                                  */
/*                                                                           */
/* Description: This function will change the password on a user's account.  */
/*              The function requires the user's email address, new password */
/*              and the password change key from the email that was sent     */
/*              to them.                                                     */
/*                                                                           */
/* Return: This function returns Success! if the password is changed.        */
/*              otherwise it will return an error message.                   */
/*****************************************************************************/

function changePassword()
{
  global $db;
  global $config;

  $username="";
  if(isset($_POST['username'])) $username=trim($_POST['username']);
  $password="";
  if(isset($_POST['password'])) $password=trim($_POST['password']);
  $key="";
  if(isset($_POST['key'])) $key=trim($_POST['key']);
  $keyvalid=0;
  if(isset($_POST['keyvalid'])) $keyvalid=intval($_POST['keyvalid']);

  if($username == "")
  {
    return "No Email Provided";
  }

  if($password == "")
  {
    return "No Password Provided";
  }

  if($key == "")
  {
    return "No Key Provided";
  }

  if($keyvalid == 0)
  {
    return "Key Invalid";
  }

  $reqdata['id'] = 0;

  // Load the token data
  $criteria = array();
  $criteria[] = sprintf("token=BINARY '%s'",$db->real_escape_string($key));
  $criteria[] = sprintf("expiration >=%d",time());

  $q = "SELECT * FROM password_tokens WHERE ".implode(" and ",$criteria);
  if($r = $db->query($q))
  {
    if($r->num_rows > 0)
    {
      $reqdata = $r->fetch_assoc();
    }
    $r->close();
  }
  
  if($reqdata['id'] == 0)
  {
    return "Key Invalid";
  }

  // Load the user record...
  $ud=array("id"=>0);
  $q = sprintf("SELECT * FROM users WHERE id=%d and validated=1 and disabled=0",$reqdata['userid']);
  if($r = $db->query($q))
  {
    if($r->num_rows > 0)
    {
      $ud = $r->fetch_assoc();
    }
    $r->close();
  }
  
  if($ud['id'] == 0)
  {
    return "Unable to load user record";
  }

  if(strtolower($ud['email']) != strtolower($username))
  {
    return "Email address invalid";
  }

  // Ok, at this point it's time to change the password.

  if(userPassword($username,$password))
  {
    // Now we need to send the email to the address provided

    $htmldata = "";
    $htmlfilename = $config['email_template_path']."passwordchange.html";
    if(file_exists($htmlfilename))
    {
      $htmldata = file_get_contents($htmlfilename);
    }
 
    $textdata = "";
    $textfilename = $config['email_template_path']."passwordchange.txt";
    if(file_exists($textfilename))
    {
      $textdata = file_get_contents($textfilename);
    }

    if($htmldata != "" || $textdata != "")
    {
      // Initiate Mailer Class
      $mail = new PHPMailer;
      $mail->SMTPDebug = 0;  // Enable verbose debug output 
      $mail->Timeout = 60;

      $mail->isSMTP(); // Set mailer to use SMTP 
      $mail->Host = $config['email_server'];
      $mail->SMTPAuth = true; // Enable SMTP authentication 
      $mail->Username = $config['email_username']; 
      $mail->Password = $config['email_password']; 
      $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
      $mail->Port = $config['email_port']; // TCP port to connect to

      // Source
      $mail->setFrom($config['lostpw_email'],$config['lostpw_name']); 
      $mail->addReplyTo($config['lostpw_email'],$config['lostpw_name']);
      //$mail->AddEmbeddedImage("../email_assets/IgniteExperienceEmailHeader.png", "header", "IgniteExperienceEmailHeader.png");
      //$mail->AddEmbeddedImage("../email_assets/IgniteExperienceEmailFooter.png", "footer", "IgniteExperienceEmailFooter.png");

      $mail->Subject = $config['lostpw_subject_confirm']; 

      if($htmldata != "")
      {
        $mail->isHTML(true); // Set email format to HTML.
        $html = $htmldata;
        $html = str_replace("[TEST_URL]",$config['test_url'],$html);
        $html = str_replace("[BASE_URL]",$config['base_url'],$html);
        $mail->Body = $html;

        if($textdata != "")
        {
          $text = $textdata;
          $text = str_replace("[TEST_URL]",$config['test_url'],$text);
          $text = str_replace("[BASE_URL]",$config['base_url'],$text);
          $mail->AltBody = $text;
        }
      }
      else
      {
        $mail->isHTML(false); // Set email format to HTML.
        if($textdata != "")
        {
          $text = $textdata;
          $text = str_replace("[TEST_URL]",$config['test_url'],$text);
          $text = str_replace("[BASE_URL]",$config['base_url'],$text);
          $mail->Body = $text;
        }
      }
 
      $mailtoname = $ud['firstname']." ".$ud['lastname'];
      $mailtoemail = $ud['email'];

      // Remove any existing addresses or attachments
      $mail->clearAddresses();
      //$mail->clearAttachments();

      // Add recipient
      $mail->addAddress($mailtoemail,$mailtoname); // Add Primary recipient

      $mail->send();
    }
    return "Success!";
  }

  return "Password change failed";
}


/*****************************************************************************/
/* Function: updatePassword                                                  */
/*                                                                           */
/* Description: This function will update the password on a user's account.  */
/*              The function requires the new password.                      */
/*                                                                           */
/* Return: This function returns 1 if the password is changed.               */
/*              otherwise it will return an 0 for an erro and                */
/*              otherwise it will return an 2 if old password is wrong.      */
/*****************************************************************************/

function updatePassword()
{
  if ($_POST["password"] && $_POST["password"] != "" && userPassword($_SESSION['username'], $_POST["password"], $_POST["oldPassword"])) {
    return 'success';
  } else {
    return 'error';
  } 
}


function getAllSales() {
  global $config;
  global $db;

  $results = array();
  
  $q = "SELECT * FROM eShowSalesItemsIncludeAllSales WHERE userid=" . $_SESSION['userid'] . " AND PRIMARYFEE=1";

  // Temp For Dev
  // $q = "SELECT * FROM eShowSalesItemsIncludeAllSales WHERE userid=12";

  $r = $db->query($q);

  $results = array();
  while($row = mysqli_fetch_assoc($r)) {
     $results[] = $row;
  }
  return json_encode($results);
}

function getAccessByDay() {
  global $config;
  global $db;

  $salesId = $_POST['salesId'];

  $results = array();
  $q = "SELECT * FROM access_by_day WHERE sales_id='". $salesId . "'";
  $r = $db->query($q);

  $results = array();
  while($row = mysqli_fetch_assoc($r)) {
     $results[] = $row;
  }
  return json_encode($results);
}

function logAccess() {
  global $config;
  global $db;

	/* Note: Changed this to log user id rather than unique code 10/1/2020 */
  $sets = array();
  $sets[] = sprintf("userid=%d",$_SESSION['userid']);
  $sets[] = sprintf("username='%s'",$db->real_escape_string($_SESSION['username']));
  $sets[] = sprintf("ip_address='%s'",$db->real_escape_string($_SERVER['REMOTE_ADDR']));
  $sets[] = sprintf("login_time=%d",time());

  $q = "INSERT INTO loginTrack SET ".implode(",",$sets);
  $db->query($q);
  return("Success!");
}


?>

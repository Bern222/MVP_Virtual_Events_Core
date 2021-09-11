<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


require_once($root . "/core/modules/dbaccess.php");
require_once($root . "/core/modules/config.php");

// Making these defines makes them global without having to declare them as globals in functions

define("_eshowToken","0cb3dc2e22741e5fd4f2fbfbc1df9aeaa2e6831e584ccda4a13305c0cf4390071edf7f21165c734c79abc453b10d73da");
define("_eshowEndpoint","https://s4.goeshow.com/webservices/eshow/Registration.cfc");
define("_eshowIncludeSurvey",true);
define("_eshowIncludeStatus",true);
define("_eshowIncludeBooth",false);
define("_eshowIncludePrimarySales",false); // Cannot be used with  _eshowIncludeAllSales
define("_eshowIncludeAllSales",true);  //  Cannot be used with _eshowIncludePrimarySales

loadConfig();

// We need a list of users to check for deleted items.
// We ignore records marked as admin and those that don't have
// a unique code assigned.

$userlist = array();
$q = "SELECT id,admin,validated,disabled,unique_code FROM users ORDER BY id";
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

echo "Found ".count($userlist)." users<br>\n";

$pagecount = 1;
for($page=0;$page < $pagecount;$page++)
{
  echo "Requesting page ".($page+1)."<br><br>\n";

  $list = esRegistrationList($page);

  if(isset($list['SUCCESS']))
  {
    if($list['SUCCESS'])
    {
      // We should have data, set the total number of pages for the loop
      $pagecount = intval($list['TOTAL_PAGES']);

      // Loop through retrieved records...
      for($i=0;$i<count($list['ATTENDEES']);$i++)
      {
        $email = $list['ATTENDEES'][$i]['EMAIL'];

        // Check to see if it already exists
        $ud = array("id"=>0);
        $q = sprintf("SELECT * FROM users WHERE email='%s';",$db->real_escape_string($email));
        if($r = $db->query($q))
        {
          if($r->num_rows > 0)
          {
            $ud = $r->fetch_assoc();
          }
          $r->close();
        }

        $sets = array();
        if($ud['id'] == 0)
        {
          echo "Adding Attendee: ".$list['ATTENDEES'][$i]['FIRST_NAME']." ".$list['ATTENDEES'][$i]['LAST_NAME']."<br>\n";

          // New record, set all of the fields
          $sets[] = sprintf("email='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['EMAIL']));

          $pw = $list['ATTENDEES'][$i]['BADGE_ID'];
          if($config['login_encrypt_pw'] == 1)
          {
            $sets[] = sprintf("password='%s'",$db->real_escape_string(password_hash($pw,PASSWORD_DEFAULT)));
            $sets[] = "password_encrypted=1";
          }
          else
          {
            $sets[] = sprintf("password='%s'",$db->real_escape_string($pw));
            $sets[] = "password_encrypted=0";
          }
          $sets[] = sprintf("firstname='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['FIRST_NAME']));
          $sets[] = sprintf("lastname='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['LAST_NAME']));
          $sets[] = sprintf("company='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['COMPANY_NAME']));

          if($list['ATTENDEES'][$i]['STATUS'] == "Approved")
          {
            $sets[] = "validated=1";
          }
          else
          {
            switch($list['ATTENDEES'][$i]['STATUS'])
            {
              case "Pending"   : break; // Just don't validate
              case "Rejected"  : 
              case "Cancelled" : $sets[] = "disabled=1"; break;
              default          : break;
            }
          }
          $cu = $list['ATTENDEES'][$i]['FIRST_NAME'].substr($list['ATTENDEES'][$i]['LAST_NAME'],0,1);
          $sets[] = sprintf("chat_username='%s'",$db->real_escape_string($cu));
          $sets[] = sprintf("avatar='%s'",$db->real_escape_string("user1.jpg"));
          $sets[] = sprintf("unique_code='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['BADGE_ID']));

          $q = "INSERT INTO users SET ".implode(",",$sets);

          $uid = 0;
          if($db->query($q))
          {
            // Query successful
            $uid = $db->insert_id;
            $ud['id'] = $uid;
          }
          else
          {
            echo "Database Error<br>\n";
            echo "Query: ".$q."<br>\n";
            echo "Error: ".$db->error."<br>\n";
          }

          if($uid != 0)
          {
            if(_eshowIncludeSurvey)
            {
              $questions = $list['ATTENDEES'][$i]['QUESTION'];
              echo "-Adding question responses<br><br>\n";
              // Store the question array
              for($j=0;$j<count($questions);$j++)
              {
                $sets = array();
                $sets[] = sprintf("userid=%d",$uid);
                $sets[] = sprintf("CODE='%s'",$db->real_escape_string($questions[$j]['CODE']));
                $sets[] = sprintf("ANSWER='%s'",$db->real_escape_string($questions[$j]['ANSWER']));
                $sets[] = sprintf("KEY_ID='%s'",$db->real_escape_string($questions[$j]['KEY_ID']));
                $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($questions[$j]['TITLE']));
                $q = "INSERT INTO eShowQuestions SET ".implode(",",$sets);
                if(!$db->query($q))
                {
                  echo "Database Error<br>\n";
                  echo "Query: ".$q."<br>\n";
                  echo "Error: ".$db->error."<br>\n";
                }

              }
            }                  

            if(_eshowIncludePrimarySales)
            {
              $sales = $list['ATTENDEES'][$i]['SALES_ITEMS_INCLUDE_PRIMARY'];
              echo "-Adding sales responses<br><br>\n";
              // Store the info array
              for($j=0;$j<count($sales);$j++)
              {
                $sets = array();
                $sets[] = sprintf("userid=%d",$uid);
                $sets[] = sprintf("GL_CODE='%s'",$db->real_escape_string($sales[$j]['GL_CODE']));
                $sets[] = sprintf("CATEGORY_KEY='%s'",$db->real_escape_string($sales[$j]['CATEGORY_KEY']));
                $sets[] = sprintf("AMOUNT='%s'",$db->real_escape_string($sales[$j]['AMOUNT']));
                $sets[] = sprintf("QTY='%s'",$db->real_escape_string($sales[$j]['QTY']));
                $sets[] = sprintf("GUID='%s'",$db->real_escape_string($sales[$j]['GUID']));
                $sets[] = sprintf("PRIMARYFEE='%s'",$db->real_escape_string($sales[$j]['PRIMARYFEE']));
                $sets[] = sprintf("ITEM_CODE='%s'",$db->real_escape_string($sales[$j]['ITEM_CODE']));
                $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($sales[$j]['TITLE']));
                $sets[] = sprintf("PRODUCTGUID='%s'",$db->real_escape_string($sales[$j]['PRODUCTGUID']));
                $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string($sales[$j]['FEE_CODE']));
                $sets[] = sprintf("SALESDATE='%s'",$db->real_escape_string($sales[$j]['SALESDATE']));
                $q = "INSERT INTO eShowSalesItemsShowPrimary SET ".implode(",",$sets);
                if(!$db->query($q))
                {
                  echo "Database Error<br>\n";
                  echo "Query: ".$q."<br>\n";
                  echo "Error: ".$db->error."<br>\n";
                }
              }
            }  
            
            if(_eshowIncludeAllSales)
            {
              $sales = $list['ATTENDEES'][$i]['SALES_ITEMS_INCLUDE_ALL_SALES'];
              echo "-Adding sales detail responses<br><br>\n";
              // Store the info array
              for($j=0;$j<count($sales);$j++)
              {
                $sets = array();
                $sets[] = sprintf("userid=%d",$uid);
                $sets[] = sprintf("GL_CODE='%s'",$db->real_escape_string($sales[$j]['GL_CODE']));
                $sets[] = sprintf("CATEGORY_KEY='%s'",$db->real_escape_string($sales[$j]['CATEGORY_KEY']));
                $sets[] = sprintf("AMOUNT='%s'",$db->real_escape_string($sales[$j]['AMOUNT']));
                $sets[] = sprintf("QTY='%s'",$db->real_escape_string($sales[$j]['QTY']));
                $sets[] = sprintf("GUID='%s'",$db->real_escape_string($sales[$j]['GUID']));
                $sets[] = sprintf("PRIMARYFEE='%s'",$db->real_escape_string($sales[$j]['PRIMARYFEE']));
                $sets[] = sprintf("ITEM_CODE='%s'",$db->real_escape_string($sales[$j]['ITEM_CODE']));
                $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($sales[$j]['TITLE']));
                $sets[] = sprintf("PRODUCTGUID='%s'",$db->real_escape_string($sales[$j]['PRODUCTGUID']));
                $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string($sales[$j]['FEE_CODE']));
                $sets[] = sprintf("SALESDATE='%s'",$db->real_escape_string($sales[$j]['SALESDATE']));
                $q = "INSERT INTO eShowSalesItemsIncludeAllSales SET ".implode(",",$sets);
                if(!$db->query($q))
                {
                  echo "Database Error<br>\n";
                  echo "Query: ".$q."<br>\n";
                  echo "Error: ".$db->error."<br>\n";
                }
              }
            }  
          }
        }
        else
        {
          // Existing record, update status if necessary

          // Update our user found flag in $userlist
          for($u=0;$u<count($userlist);$u++)
          {
            if($ud['unique_code'] == $userlist[$u]['unique_code'])
            {
              $userlist[$u]['found'] = 1;
              break;
            }
          }

          echo "Updating Attendee: ".$list['ATTENDEES'][$i]['FIRST_NAME']." ".$list['ATTENDEES'][$i]['LAST_NAME']."<br>\n";
          $sets = array(); // Clear the array
          $changed = false;

          if($ud['unique_code'] != $list['ATTENDEES'][$i]['BADGE_ID'])
          {
            // Badge number has changed?
            $pw = $list['ATTENDEES'][$i]['BADGE_ID'];
            if($config['login_encrypt_pw'] == 1)
            {
              $sets[] = sprintf("password='%s'",$db->real_escape_string(password_hash($pw,PASSWORD_DEFAULT)));
              $sets[] = "password_encrypted=1";
            }
            else
            {
              $sets[] = sprintf("password='%s'",$db->real_escape_string($pw));
              $sets[] = "password_encrypted=0";
            }
            $sets[] = sprintf("unique_code='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['BADGE_ID']));
          }

          if($ud['firstname'] != $list['ATTENDEES'][$i]['FIRST_NAME'] ||
             $ud['lastname'] != $list['ATTENDEES'][$i]['LAST_NAME'])
          {
            // Name changed
            $sets[] = sprintf("firstname='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['FIRST_NAME']));
            $sets[] = sprintf("lastname='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['LAST_NAME']));
            $cu = $list['ATTENDEES'][$i]['FIRST_NAME'].substr($list['ATTENDEES'][$i]['LAST_NAME'],0,1);
            $sets[] = sprintf("chat_username='%s'",$db->real_escape_string($cu));
          }

          if($ud['company'] != $list['ATTENDEES'][$i]['COMPANY_NAME'])
          {
            $sets[] = sprintf("company='%s'",$db->real_escape_string($list['ATTENDEES'][$i]['COMPANY_NAME']));
          }

          switch($list['ATTENDEES'][$i]['STATUS'])
          {
            case "Approved"  :
                 if($ud['validated'] == 0) $sets[] = "validated=1";
                 if($ud['disabled'] == 1) $sets[] = "disabled=0";
                 break;
            case "Pending"   :
                 if($ud['validated'] == 1) $sets[] = "validated=0";
                 if($ud['disabled'] == 1) $sets[] = "disabled=0";
                 break;
            case "Rejected"  : 
                 if($ud['validated'] == 1) $sets[] = "validated=0";
                 if($ud['disabled'] == 0) $sets[] = "disabled=1";
                 break;
            case "Cancelled" :
                 if($ud['validated'] == 0) $sets[] = "validated=1";
                 if($ud['disabled'] == 0) $sets[] = "disabled=1";
                 break;
            default          : break;
          }
          
          if(count($sets) > 0)
          {
            $q = "UPDATE users SET ".implode(",",$sets).sprintf(" WHERE id=%d",$ud['id']);
            if($db->query($q))
            {
              echo "- Updated ".count($sets)." user fields.<br>\n";
            }
            else
            {
              echo "Database Error<br>\n";
              echo "Query: ".$q."<br>\n";
              echo "Error: ".$db->error."<br>\n";
            }
          }

          if(_eshowIncludeSurvey)
          {
            // Load existing question entries
            $qlist = array();
            $q = sprintf("SELECT * FROM eShowQuestions WHERE userid=%d",$ud['id']);
            if($r = $db->query($q))
            {
              for($j=0;$j<$r->num_rows;$j++)
              {
                $qlist[] = $r->fetch_assoc();
              }
              $r->close();
            }

            $questions = $list['ATTENDEES'][$i]['QUESTION'];
            echo "- Updating question responses<br>\n";

            $addcount=0;
            $updtcount=0;

            // Store the question array
            for($j=0;$j<count($questions);$j++)
            {
              $found = 0;
              $sets = array();
              for($k=0;$k<count($qlist) && $found == 0;$k++)
              {
                if($questions[$j]['KEY_ID'] == $qlist[$k]['KEY_ID'])
                {
                  // Found it! Check to see if anything changed
                  $found = 1;

                  if($questions[$j]['CODE'] != $qlist[$k]['CODE'])
                  {
                    $sets[] = sprintf("CODE='%s'",$db->real_escape_string($questions[$j]['CODE']));
                  }

                  if($questions[$j]['ANSWER'] != $qlist[$k]['ANSWER'])
                  {
                    $sets[] = sprintf("ANSWER='%s'",$db->real_escape_string($questions[$j]['ANSWER']));
                  }

                  if($questions[$j]['TITLE'] != $qlist[$k]['TITLE'])
                  {
                    $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($questions[$j]['TITLE']));
                  }

                  if(count($sets) > 0)
                  {
                    $q = "UPDATE eShowQuestions SET ".implode(",",$sets).sprintf(" WHERE id=%d",$qlist[$k]['id']);
                    if(!$db->query($q))
                    {
                      echo "Database Error<br>\n";
                      echo "Query: ".$q."<br>\n";
                      echo "Error: ".$db->error."<br>\n";
                    }
                    else 
                      $updtcount++;
                  }
                }
              }

              if($found == 0)
              {
                // New question?  Add it to the database
                $sets[] = sprintf("userid=%d",$ud['id']);
                $sets[] = sprintf("ANSWER='%s'",$db->real_escape_string($questions[$j]['ANSWER']));
                $sets[] = sprintf("KEY_ID='%s'",$db->real_escape_string($questions[$j]['KEY_ID']));
                $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($questions[$j]['TITLE']));
                $q = "INSERT INTO eShowQuestions SET ".implode(",",$sets);
                if(!$db->query($q))
                {
                  echo "Database Error<br>\n";
                  echo "Query: ".$q."<br>\n";
                  echo "Error: ".$db->error."<br>\n";
                }
                $addcount++;
              }
            }
            if($addcount > 0)
            {
              echo "-- Added ".$addcount." question response";
              if($addcount != 1) echo "s";
              echo "<br>\n";
            }
            if($updtcount > 0)
            {
              echo "-- Updated ".$updtcount." question response";
              if($updtcount != 1) echo "s";
              echo "<br>\n";
            }

            if($addcount == 0 && $updtcount == 0) echo "-- All questions up-to-date<br>\n";
          }

          if(_eshowIncludePrimarySales)
          {
            // Load existing question entries
            $slist = array();
            $q = sprintf("SELECT * FROM eShowSalesItemsShowPrimary WHERE userid=%d",$ud['id']);
            if($r = $db->query($q))
            {
              for($j=0;$j<$r->num_rows;$j++)
              {
                $slist[$j] = $r->fetch_assoc();
                $slist[$j]['found'] = 0;
              }
              $r->close();
            }

            $sales = $list['ATTENDEES'][$i]['SALES_ITEMS_INCLUDE_PRIMARY'];
            echo "-Updating sales responses<br>\n";

            $addcount=0;
            $updtcount=0;

            // Store the info array
            for($j=0;$j<count($sales);$j++)
            {
              $found = 0;
              $sets = array();
              for($k=0;$k<count($slist) && $found == 0;$k++)
              {
                if($sales[$j]['PRODUCTGUID'] == $slist[$k]['PRODUCTGUID'])
                {
                  // Found it! Check to see if anything changed
                  $found = 1;
                  $slist[$k]['found'] = 1;

                  if($sales[$j]['GL_KEY'] != $slist[$k]['GL_KEY'])
                  {
                    $sets[] = sprintf("GL_KEY='%s'",$db->real_escape_string($sales[$j]['GL_KEY']));
                  }

                  if($sales[$j]['CATEGORY_KEY'] != $slist[$k]['CATEGORY_KEY'])
                  {
                    $sets[] = sprintf("CATEGORY_KEY='%s'",$db->real_escape_string($sales[$j]['CATEGORY_KEY']));
                  }

                  if($sales[$j]['AMOUNT'] != $slist[$k]['AMOUNT'])
                  {
                    $sets[] = sprintf("AMOUNT='%s'",$db->real_escape_string($sales[$j]['AMOUNT']));
                  }

                  if($sales[$j]['QTY'] != $slist[$k]['QTY'])
                  {
                    $sets[] = sprintf("QTY='%s'",$db->real_escape_string($sales[$j]['QTY']));
                  }

                  if($sales[$j]['GUID'] != $slist[$k]['GUID'])
                  {
                    $sets[] = sprintf("GUID='%s'",$db->real_escape_string($sales[$j]['GUID']));
                  }

                  if($sales[$j]['PRIMARYFEE'] != $slist[$k]['PRIMARYFEE'])
                  {
                    $sets[] = sprintf("PRIMARYFEE='%s'",$db->real_escape_string($sales[$j]['PRIMARYFEE']));
                  }

                  if($sales[$j]['ITEM_CODE'] != $slist[$k]['ITEM_CODE'])
                  {
                    $sets[] = sprintf("ITEM_CODE='%s'",$db->real_escape_string($sales[$j]['ITEM_CODE']));
                  }

                  if($sales[$j]['TITLE'] != $slist[$k]['TITLE'])
                  {
                    $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($sales[$j]['TITLE']));
                  }

                  if($sales[$j]['FEE_CODE'] != $slist[$k]['FEE_CODE'])
                  {
                    $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string($sales[$j]['FEE_CODE']));
                  }

                  if($sales[$j]['SALESDATE'] != $slist[$k]['SALESDATE'])
                  {
                    $sets[] = sprintf("SALESDATE='%s'",$db->real_escape_string($sales[$j]['SALESDATE']));
                  }

                  if(count($sets) > 0)
                  {
                    $q = "UPDATE eShowSalesItemsShowPrimary SET ".implode(",",$sets).sprintf(" WHERE id=%d",$slist[$k]['id']);
                    $db->query($q);
                    $updtcount++;
                  }
                }
              }

              if($found == 0)
              {
                // Add the record
                $sets = array();
                $sets[] = sprintf("userid=%d",$ud['id']);
                $sets[] = sprintf("GL_CODE='%s'",$db->real_escape_string($sales[$j]['GL_CODE']));
                $sets[] = sprintf("CATEGORY_KEY='%s'",$db->real_escape_string($sales[$j]['CATEGORY_KEY']));
                $sets[] = sprintf("AMOUNT='%s'",$db->real_escape_string($sales[$j]['AMOUNT']));
                $sets[] = sprintf("QTY='%s'",$db->real_escape_string($sales[$j]['QTY']));
                $sets[] = sprintf("GUID='%s'",$db->real_escape_string($sales[$j]['GUID']));
                $sets[] = sprintf("PRIMARYFEE='%s'",$db->real_escape_string($sales[$j]['PRIMARYFEE']));
                $sets[] = sprintf("ITEM_CODE='%s'",$db->real_escape_string($sales[$j]['ITEM_CODE']));
                $sets[] = sprintf("TITLE='%s'",$db->real_escape_string($sales[$j]['TITLE']));
                $sets[] = sprintf("PRODUCTGUID='%s'",$db->real_escape_string($sales[$j]['PRODUCTGUID']));
                $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string($sales[$j]['FEE_CODE']));
                $sets[] = sprintf("SALESDATE='%s'",$db->real_escape_string($sales[$j]['SALESDATE']));
                $q = "INSERT INTO eShowSalesItemsShowPrimary SET ".implode(",",$sets);
                $db->query($q);
                $addcount++;
              }
            }   
/*
            $delcount = 0;
            for($k=0;$k<count($slist) && $found == 0;$k++)
            {
              if($slist[$k]['found'] == 0)
              {
                // Information not in retrieved data, delete from our database
                $q = sprintf("DELETE FROM eShowSalesItemsShowPrimary WHERE id=%d",$slist[$k]['id']);
                $db->query($q);
                $delcount++;
              }
            }

            if($delcount > 0)
            {
              echo "-- Deleted ".$delcount." sales response";
              if($delcount != 1) echo "s";
              echo "<br>\n";
            }
*/
            if($addcount > 0)
            {
              echo "-- Added ".$addcount." sales response";
              if($addcount != 1) echo "s";
              echo "<br>\n";
            }
            if($updtcount > 0)
            {
              echo "-- Updated ".$updtcount." sales response";
              if($updtcount != 1) echo "s";
              echo "<br>\n";
            }

            if($addcount == 0 && $updtcount == 0) echo "-- All sales responses up-to-date<br>\n";
          }

          if(_eshowIncludeAllSales)
          {
            // Load existing question entries
            $slist = array();
            $q = sprintf("SELECT * FROM eShowSalesItemsIncludeAllSales WHERE userid=%d",$ud['id']);
            if($r = $db->query($q))
            {
              for($j=0;$j<$r->num_rows;$j++)
              {
                $slist[$j] = $r->fetch_assoc();
                if($slist[$j]['GUID'] == "00000000-0000-0000-0000-000000000000")
                {
                  $slist[$j]['found'] = 1; // We don't want to delete these.
                }
                else
                {
                  $slist[$j]['found'] = 0;
                }
              }
              $r->close();
            }

            $sales = $list['ATTENDEES'][$i]['SALES_ITEMS_INCLUDE_ALL_SALES'];
            echo "-Updating sales detail responses<br>\n";

            $addcount=0;
            $updtcount=0;

            // Store the info array
            for($j=0;$j<count($sales);$j++)
            {
              $found = 0;
              $sets = array();
              for($k=0;$k<count($slist) && $found == 0;$k++)
              {
                if(trim($sales[$j]['GUID']) == trim($slist[$k]['GUID']))
                {
                  // Found it! Check to see if anything changed
                  $found = 1;
                  $slist[$k]['found'] = 1;

                  if(trim($sales[$j]['CATEGORY_KEY']) != $slist[$k]['CATEGORY_KEY'])
                  {
                    $sets[] = sprintf("CATEGORY_KEY='%s'",$db->real_escape_string(trim($sales[$j]['CATEGORY_KEY'])));
                  }

                  if(trim($sales[$j]['AMOUNT']) != $slist[$k]['AMOUNT'])
                  {
                    $sets[] = sprintf("AMOUNT='%s'",$db->real_escape_string(trim($sales[$j]['AMOUNT'])));
                  }

                  if(trim($sales[$j]['QTY']) != $slist[$k]['QTY'])
                  {
                    $sets[] = sprintf("QTY='%s'",$db->real_escape_string(trim($sales[$j]['QTY'])));
                  }

                  if(trim($sales[$j]['PRODUCTGUID']) != $slist[$k]['PRODUCTGUID'])
                  {
                    $sets[] = sprintf("PRODUCTGUID='%s'",$db->real_escape_string(trim($sales[$j]['PRODUCTGUID'])));
                  }

                  if(trim($sales[$j]['PRIMARYFEE']) != $slist[$k]['PRIMARYFEE'])
                  {
                    $sets[] = sprintf("PRIMARYFEE='%s'",$db->real_escape_string(trim($sales[$j]['PRIMARYFEE'])));
                  }

                  if(trim($sales[$j]['ITEM_CODE']) != $slist[$k]['ITEM_CODE'])
                  {
                    $sets[] = sprintf("ITEM_CODE='%s'",$db->real_escape_string(trim($sales[$j]['ITEM_CODE'])));
                  }

                  if(trim($sales[$j]['TITLE']) != $slist[$k]['TITLE'])
                  {
                    $sets[] = sprintf("TITLE='%s'",$db->real_escape_string(trim($sales[$j]['TITLE'])));
                  }

                  if(trim($sales[$j]['GL_CODE']) != $slist[$k]['GL_CODE'])
                  {
                    $sets[] = sprintf("GL_CODE='%s'",$db->real_escape_string(trim($sales[$j]['GL_CODE'])));
                  }

                  if(trim($sales[$j]['FEE_CODE']) != $slist[$k]['FEE_CODE'])
                  {
                    $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string(trim($sales[$j]['FEE_CODE'])));
                  }

                  if(trim($sales[$j]['SALESDATE']) != $slist[$k]['SALESDATE'])
                  {
                    $sets[] = sprintf("SALESDATE='%s'",$db->real_escape_string(trim($sales[$j]['SALESDATE'])));
                  }

                  if(count($sets) > 0)
                  {
                    $q = "UPDATE eShowSalesItemsIncludeAllSales SET ".implode(",",$sets).sprintf(" WHERE id=%d",$slist[$k]['id']);
                    $db->query($q);
                    echo "- Updated GUID: ".$sales[$j]['GUID']."<br>\n";
                    $updtcount++;
                  }
                }
              }

              if($found == 0)
              {
                // Add the record
                $sets = array();
                $sets[] = sprintf("userid=%d",$ud['id']);
                $sets[] = sprintf("GL_CODE='%s'",$db->real_escape_string(trim($sales[$j]['GL_CODE'])));
                $sets[] = sprintf("CATEGORY_KEY='%s'",$db->real_escape_string(trim($sales[$j]['CATEGORY_KEY'])));
                $sets[] = sprintf("AMOUNT='%s'",$db->real_escape_string(trim($sales[$j]['AMOUNT'])));
                $sets[] = sprintf("QTY='%s'",$db->real_escape_string(trim($sales[$j]['QTY'])));
                $sets[] = sprintf("GUID='%s'",$db->real_escape_string(trim($sales[$j]['GUID'])));
                $sets[] = sprintf("PRIMARYFEE='%s'",$db->real_escape_string(trim($sales[$j]['PRIMARYFEE'])));
                $sets[] = sprintf("ITEM_CODE='%s'",$db->real_escape_string(trim($sales[$j]['ITEM_CODE'])));
                $sets[] = sprintf("TITLE='%s'",$db->real_escape_string(trim($sales[$j]['TITLE'])));
                $sets[] = sprintf("PRODUCTGUID='%s'",$db->real_escape_string(trim($sales[$j]['PRODUCTGUID'])));
                $sets[] = sprintf("FEE_CODE='%s'",$db->real_escape_string(trim($sales[$j]['FEE_CODE'])));
                $sets[] = sprintf("SALESDATE='%s'",$db->real_escape_string(trim($sales[$j]['SALESDATE'])));
                $q = "INSERT INTO eShowSalesItemsIncludeAllSales SET ".implode(",",$sets);
                $db->query($q);
                echo "- Added GUID: ".$sales[$j]['GUID']."<br>\n";
                $addcount++;
              }
            }                  
/*
            $delcount = 0;
            for($k=0;$k<count($slist) && $found == 0;$k++)
            {
              if($slist[$k]['found'] == 0)
              {
                // Information not in retrieved data, delete from our database
                $q = sprintf("DELETE FROM eShowSalesItemsIncludeAllSales WHERE id=%d",$slist[$k]['id']);
                $db->query($q);
                echo "-- Deleted GUID: ".$slist[$k]['GUID']."<br>\n";
                $delcount++;
              }
            }
            if($delcount > 0)
            {
              echo "-- Deleted ".$delcount." sales response";
              if($delcount != 1) echo "s";
              echo "<br>\n";
            }
*/
            if($addcount > 0)
            {
              echo "-- Added ".$addcount." sales response";
              if($addcount != 1) echo "s";
              echo "<br>\n";
            }
            if($updtcount > 0)
            {
              echo "-- Updated ".$updtcount." sales response";
              if($updtcount != 1) echo "s";
              echo "<br>\n";
            }

            if($addcount == 0 && $updtcount == 0) echo "-- All sales responses up-to-date<br>\n";
          }


        }
        echo "<br>\n";       
      }
    }
    else
    {
      echo "REQUEST_FAILED<br><br>\n";
    }
  }
}

/*
$disablecount = 0;
echo "Processing disabled accounts by absence...<br>\n";
for($i=0;$i < count($userlist);$i++)
{
  if($userlist[$i]['found'] == 0 && $userlist[$i]['disabled'] == 0)
  {
    $q = sprintf("UPDATE users SET disabled=1 WHERE id=%d",$userlist[$i]['id']);
    $db->query($q);
    echo "- Disabled user #".$userlist[$i]['id']." (".$userlist[$i]['firstname']." ".$userlist[$i]['lastname'].")<br>\n";
    $disablecount++;
  }
}
echo $disablecount." account(s) disabled<br><br>\n";
*/  

function esRegistrationList($page=0)
{
  $elements = array();

  $elements[] = "method=Registration_list";
  $elements[] = "token="._eshowToken;

  if(_eshowIncludeSurvey) $elements[] = "IncludeSurvey=true";
  if(_eshowIncludeStatus) $elements[] = "IncludeStatus=true";
  if(_eshowIncludeBooth) $elements[] = "IncludeBooth=true";
  if(_eshowIncludePrimarySales) $elements[] = "IncludePrimarySales=true";
  if(_eshowIncludeAllSales) $elements[] = "IncludeAllSales=true";
  $elements[] = "reducedSizePhoto=true";

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

//echo $response;

  $data = json_decode($response,1);

  return $data;
}

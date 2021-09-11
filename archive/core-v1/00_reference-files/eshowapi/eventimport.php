<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


require_once($root . "/core/modules/dbaccess.php");
require_once($root . "/core/modules/config.php");

loadConfig();

define("_DEBUG_",false);

if(isset($_POST['importPassword']))
{
  if($_POST['importPassword'] == "WOCEvents20!")
  {
    // Good password, proceed with import
    processImport();
  }
  else
  {
    // Bad Password
  }
}

importScreen();
exit;


function importScreen()
{
  writeHeader();
?>
    <form name="form1" id="form1" action="eventimport.php" method="post">
     <h3>Import/Update Event List</h3>

     This screen allows the importing of events from an Excel spreadsheet using copy and paste.<br><br>
     To import or update these events, enter the import password and copy and paste the spreadsheet data
     (including column headings) into the space below.<br><br>
     Certain columns are required, others are optional. The order in which these columns appear is not
     important as long as the column headings are correct. For a list of all recognized column names,
     their required status, and description, scroll down to the bottom of this page.<br><br>

     <table style="width: 100%;">
      <tr>
       <td class="type" style="white-space:nowrap">Import Password:</td>
       <td class="entry">
        <input type="password" name="importPassword" name="importPassword" size="40" maxlength="40" value="">
       </td>
      </tr>
      <tr>
       <td class="type" style="white-space:nowrap">Import Data:</td>
       <td class="entry"><textarea class="full" name="data"></textarea>
       </td>
      </tr>
     </table>
     <br>
     <input type="submit" value="Import Data">
    </form>
    <br><br><br><br>
<?php

  writeFooter();
}

function processImport()
{
  global $db;

  // Parse the pasted data

  if(!isset($_POST['data']))
  {
    ErrorScreen("No Data Available");
    exit;
  }

  $data = trim($_POST['data']);
  if($data == "")
  {
    ErrorScreen("No Data Provided");
    exit;
  }

  $lines = array();

  $data = str_replace("\r","",$data);
  $lines = explode("\n",$data);
  unset($data);

if(_DEBUG_) echo "<!-- Line count: ".count($lines)." -->\n";

  $required_headings = array("PRIMARY_FEE_STATUS","SALES_ITEM_TITLE","PRICE_KEY");
  for($i=8;$i<=13;$i++)
  {
    $required_headings[] = sprintf("2/%d/2021",$i);
  }

  $headings = array();
  $headings = explode("\t",$lines[0]);

  // Header translations to fix some minor values
  for($i=0;$i<count($headings);$i++)
  {
    $headings[$i] = strtoupper(trim($headings[$i]));

    // Translate date headers
/*
    for($j=8;$j<=13;$j++)
    {
      $in = sprintf("%d-FEB",$j);
      $out = sprintf("2/%d/2021",$j);

      if($headings[$i] == strtoupper($in)) $headings[$i] = $out;
    }
*/
    // Check for required headings
    for($j=0;$j<count($required_headings);$j++)
    {
      if($required_headings[$j] == $headings[$i])
      {
        unset($required_headings[$j]);
        $required_headings = array_values($required_headings);
      }
    }
  }

  if(count($required_headings) > 0)
  {
    $message = "The following required headers are missing from the pasted data:<br><br>\n";
    $message .= "<ul>\n";
    for($i=0;$i<count($required_headings);$i++)
    {
      $message .= " <li>".$required_headings[$i]."\n";
    }
    $message .= "</ul><br>\n";
    $message .= "Please ensure these columns exist, and the column headers are included in the pasted data.<br><br>\n";
    ErrorScreen($message);
    exit;
  }

  $valid_headings = array(); // Headings we recognize
  $valid_headings[] = array("field"=>"PRIMARY_FEE_STATUS","type"=>"string");
  $valid_headings[] = array("field"=>"SALES_ITEM_TITLE","type"=>"string");
  $valid_headings[] = array("field"=>"PRICE_KEY","type"=>"string");
  for($i=8;$i<=13;$i++)
  {
    $key = sprintf("2/%d/2021",$i);
    $valid_headings[] = array("field"=>$key,"type"=>"date");
  }

  $columns = array();
  for($i=0;$i<count($headings);$i++)
  {
    $columns[$headings[$i]] = $i;

if(_DEBUG_) echo "<!-- Column: ".$headings[$i]." - ".$i." -->\n";
  }

  $importcount = 0;
  $updatecount = 0;
  $skipcount = 0;

  for($l=1;$l < count($lines);$l++)
  {
    $data = explode("\t",$lines[$l]);
    $sets = array();

    $title = "";
    $sales_id = "";
    $datelist = array();

    for($i=0;$i<count($valid_headings);$i++)
    {
      if($valid_headings[$i]['field'] == "PRIMARY_FEE_STATUS") continue; // Not using this at this point

      if($valid_headings[$i]['field'] == "SALES_ITEM_TITLE") $title = trim($data[$columns[$valid_headings[$i]['field']]]);
      if($valid_headings[$i]['field'] == "PRICE_KEY") $sales_id = trim($data[$columns[$valid_headings[$i]['field']]]);
      if($valid_headings[$i]['type'] == "date")
      {
        if(strtoupper($data[$columns[$valid_headings[$i]['field']]]) == "YES")
        {
          $datelist[] = $valid_headings[$i]['field'];
        }
        continue;
      }
    }

if(_DEBUG_) echo "<!-- parse - title: ".$title." -->\n";
if(_DEBUG_) echo "<!-- parse - sales_id: ".$sales_id." -->\n";
if(_DEBUG_) echo "<!-- parse - datelist: ".json_encode($datelist)." -->\n";


    if(trim($title) == "" || trim($sales_id) == "") continue; // blank line
    //if(count($datelist) == 0) continue; // No access dates present

    $days_of_access = json_encode($datelist);

    // Check to see if this item already exists
    $existing = array("id"=>0);
    $q = sprintf("SELECT * from access_by_day WHERE sales_id='%s'",$db->real_escape_string($sales_id));
    if($r = $db->query($q))
    {
      if($r->num_rows > 0)
      {
        $existing = $r->fetch_assoc();
      }
      $r->close();
    }
      
    $sets = array();
    if($existing['id'] == 0)
    {
      // new record
      $sets[] = sprintf("title='%s'",$db->real_escape_string($title));
      $sets[] = sprintf("sales_id='%s'",$db->real_escape_string($sales_id));
      $sets[] = sprintf("days_of_access='%s'",$days_of_access);
      $q = "INSERT INTO access_by_day SET ".implode(",",$sets);
      if(_DEBUG_)
      {
        echo "<!-- Insert Query: ".$q."-->\n";
      }
      else
      {
        $db->query($q);
      }
      $importcount++;
    }
    else
    {
      // Updating existing
      if($title != $existing['title']) $sets[] = sprintf("title='%s'",$db->real_escape_string($title));
      if($sales_id != $existing['sales_id']) $sets[] = sprintf("sales_id='%s'",$db->real_escape_string($sales_id));
      if($days_of_access != $existing['days_of_access']) $sets[] = sprintf("days_of_access='%s'",$days_of_access);

      if(count($sets) > 0)
      {
        // Build the update query
        $q = "UPDATE access_by_day SET ".implode(",",$sets).sprintf(" WHERE id=%d",$existing['id']);
        if(_DEBUG_)
        {
          echo "<!-- Update Query: ".$q."-->\n";
        }
        else
        {
          $db->query($q);
        }
        $updatecount++;
      }
      else
      {
        $skipcount++;
      }
    }
  }

  writeHeader();
?>
    <h3>Import/Update Events Results</h3>
    The import process has completed. Details Below:<br><br>
    <table>
     <tr>
      <td>Events Added:</td>
      <td><?php echo $importcount; ?></td>
     </tr>
     <tr>
      <td>Events Updated:</td>
      <td><?php echo $updatecount; ?></td>
     </tr>
     <tr>
      <td>Events Unchanged:</td>
      <td><?php echo $skipcount;?></td>
     </tr>
    </table>
<?php
  writeFooter();
  exit;
}

function writeHeader()
{
?>
<!DOCTYPE html>

<html>
 <head>
  <title>Event Import</title>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <link rel="stylesheet" href="eventimport.css"></link>
 </head>
 <body>
  <div id="wrap">
   <div id="header">
    <p id="title">Event Import</p>
   </div>
   <div id="content-wrap">
<?php
}

function writeFooter()
{
?>
   </div>
  </div>
 </body>
</html>
<?php
}

function ErrorScreen($message)
{
  writeHeader();
?>
    <?php echo $message."<br><br>\n"; ?>
    <form action="eventimport.php" method="get">
     <input type="submit" value="Continue">
    </form>
<?php

  writeFooter();
  exit;
}  



?>
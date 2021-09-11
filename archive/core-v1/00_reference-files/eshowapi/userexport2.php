<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


require_once($root . "/core/modules/dbaccess.php");
require_once($root . "/core/modules/config.php");

loadConfig();

$access_by_day = array();
$q = "SELECT * FROM access_by_day WHERE days_of_access != '[]'";
if($r = $db->query($q))
{
  for($i=0;$i<$r->num_rows;$i++)
  {
    $access_by_day[$i] = $r->fetch_assoc();
  }
  $r->close();
}

// load base user data

$userlist = array();
$q = "SELECT * FROM users WHERE disabled=0 ORDER BY id";
if($r = $db->query($q))
{
  for($i=0;$i<$r->num_rows;$i++)
  {
    $userlist[$i] = $r->fetch_assoc();
  }
  $r->close();
}

// Now iterate through user list

echo "<html>\n";
echo "<body>\n";
echo "<table>\n";
echo "<tr>\n";
echo " <td>User ID</td>\n";
echo " <td>Sales</td>\n";
echo " <td>Days</td>\n";
echo " <td>2/8/2020</td>\n";
echo " <td>2/9/2020</td>\n";
echo " <td>2/10/2020</td>\n";
echo " <td>2/11/2020</td>\n";
echo " <td>2/12/2020</td>\n";
echo " <td>2/13/2020</td>\n";
echo "</tr>\n";
for($user=0;$user<count($userlist);$user++)
{
  // Load sales info
  $sales = array();
  $q = sprintf("SELECT * from eShowSalesItemsIncludeAllSales WHERE userid=%d",$userlist[$user]['id']);
  if($r = $db->query($q))
  {
    for($i=0;$i<$r->num_rows;$i++)
    {
      $sales[$i] = $r->fetch_assoc();
    }
    $r->close();
  }

  $dates = array();
  for($i=8;$i<=13;$i++)
  {
    $key = sprintf("2/%d/2021",$i);
    $dates[$key] = 0;
  }

  for($s=0;$s<count($sales);$s++)
  {
    for($l=0;$l<count($access_by_day);$l++)
    {
      if($access_by_day[$l]['sales_id'] == $sales[$s]['GUID'])
      {
        $salesfound = true;
        $d = json_decode($access_by_day[$l]['days_of_access']);
        for($i=0;$i<count($d);$i++)
        {
          $dates[$d[$i]] = 1;
        }
        break;
      }
    }
  }
    // now count the days
    $daycount = 0;
    foreach($dates as $key => $value)
    {
      if($key == "2/11/2021" || $key == "2/12/2020" || $key == "2/13/2020")
      {
        if($value != 0) $daycount++;
      }
    }

  if($daycount > 0 && $daycount <3)
  {
echo "<tr>\n";
echo "<td>User: ".$userlist[$user]['id']."</td>";
echo "<td>".count($sales)."</td>\n";
echo "<td>".$daycount."</td>\n";
for($i=8;$i<=13;$i++)
{
  $key = sprintf("2/%d/2021",$i);
  echo "<td>".$dates[$key]."</td>\n";
}
echo "</tr>\n";
  }
}
echo "</table>\n";
echo "Done!";
echo "</html>\n";    



?>
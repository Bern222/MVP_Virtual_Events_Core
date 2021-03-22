<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';


header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

function isMobile () {
  return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
}

require_once($root . "/core/common.php");

if(validSession($_SESSION['userid'], $_SESSION['session_id']))
{
  if(userGetById($_SESSION['userid']))
  {
    if($userdata['admin'] == 0)
    {
      header("Location:../main.php");
    }
  }
  else
  {
    header("Location:../index.php");
  }

  updateSession($_SESSION['userid'], $_SESSION['session_id']);
} else {
  // echo 'here: ' . $_SESSION['userid'] . ' ' . session_id();
  header("Location:../index.php");
}

$monitor_refresh = 60000 * $config['monitor_refresh'];

?>
<!doctype html>
<html>
  <head>
    <title>Women of Color 2020 STEM Conference</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>  
    <script
        src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
        integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="css/main.css?<?php echo rand();?>" />
    <link rel="stylesheet" href="css/monitor.css?<?php echo rand();?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="monitor.js?<?php echo rand();?>"></script>

    <script>
        var currentUserId = 0;
        var currentUsername = '';
        var sponsorAdminId = 0;
        var isBoothSuperAdmin = false;
        var resumeFilename = '';
        var currentUser = {};

					monitor_refresh = <?php echo $monitor_refresh; ?>;
        <?php if ($_SESSION['userid'] && $_SESSION['userdata']) { ?>
            currentUser = <?php echo json_encode($_SESSION['userdata']); ?>;
        <?php } ?>

        <?php if ($_SESSION['userid'] && $_SESSION['chat_username']) { ?>
            currentUserId = <?php echo $_SESSION['userid']; ?>;
        <?php } ?>

        <?php if ($_SESSION['userid'] && $_SESSION['userdata'] && $_SESSION['userdata']['admin_sponsor_id']) { ?>
            sponsorAdminId = <?php echo $_SESSION['userdata']['admin_sponsor_id']; ?>;
        <?php } ?>

        <?php if ($_SESSION['userid'] && $_SESSION['userdata'] && $_SESSION['userdata']['booth_super_admin']) { ?>
            isBoothSuperAdmin = <?php echo $_SESSION['userdata']['booth_super_admin']; ?>;
        <?php } ?>

        <?php if ($_SESSION['userdata'] && $_SESSION['userdata']['resume_filename']) { ?>
            resumeFilename = '<?php echo $_SESSION['userdata']['resume_filename']; ?>';
        <?php } ?>
    </script>

    <style>
        html {
            height: 100%;
<?php
if (isMobile()) {
?>
			padding-top: 50px;
<?php
}
?>
        }
        
<?php
if (isMobile()) {
?>
		.fancybox-toolbar {
			padding-top: 50px;
		}
<?php
}
?>	
    </style>

</head>
  <body>
    <div class="header">
        <div class="main-menu">
            <div onclick="loadMain()"><img class="menu-logo" src="../images/logo.png"/></div>
            <div class="menu-item" onclick="loadMain()" style="color:#fdc15a;margin-right:50px;">Women of Color STEM Conference</div>
            <div class="menu-item" onclick="fadeIn('dashboard')">Dashboard</div>
        </div>
    </div>

		<div id="dashboard" class="fullscreen dashboard">
			<img class="full-background" src="https://d1vxcp6kmz704x.cloudfront.net/womenofcoloronline/3-ExhibitHall.jpg">
			<div id="dashboard-active-sessions" class="dashboard-active-sessions"></div>
			<div id="dashboard-top-logins" class="dashboard-top-logins"><h1>Top Logins</h1></div>
			<div id="dashboard-top-ips" class="dashboard-top-ips"><h1>Top IP Users</h1></div>
			<div id="dashboard-top-dupes" class="dashboard-top-dupes"><h1>Duplicate Sessions</h1></div>
    </div>

  </body>
</html>
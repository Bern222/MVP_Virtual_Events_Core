<?php
$root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

function isMobile () {
  return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
}
// echo $root . "/core/common.php";
require_once("../core/common.php");

header("location:site/main.php");
exit;

// if($config['login_required'] == 0)
// {
//   header("location:main.php");
//   exit;
// }

$function = "";
$key = "";
$keyvalid = 0;

if($config['user_forgotpassword'] == 1)
{
  if(isset($_REQUEST['key']))
  {
    // This is a password reset connection...
    $function = "PWReset";
    $reqdata = array("id"=>0);

    $key = $_REQUEST['key'];

    $criteria = array();
    $criteria[] = sprintf("token=BINARY '%s'",$db->real_escape_string($key));
    $criteria[] = sprintf("expiration >=%d",time());
    $criteria[] = "used=0";

    $q = "SELECT * FROM password_tokens WHERE ".implode(" and ",$criteria);
    if($r = $db->query($q))
    {
      if($r->num_rows > 0)
      {
        $reqdata = $r->fetch_assoc();
      }
      $r->close();
    }
  
    if($reqdata['id'] != 0)
    {
      $expiration = time() + (15 * 60); // Set expiration to 15 minutes to ensure enough time to change
      $keyvalid = 1;
      $sets = array();
      $sets[] = "used=1"; // Mark the record as used...
      $sets[] = sprintf("response_ip_address='%s'",$db->real_escape_string($_SERVER['REMOTE_ADDR']));
      $sets[] = sprintf("expiration=%d",$expiration);
      $q = "UPDATE password_tokens SET ".implode(",",$sets).sprintf(" WHERE id=%d",$reqdata['id']);
      $db->query($q);
    }
  }
}

$firstpage="exterior";
if($function == "PWReset")
{
  if($keyvalid == 1)
  {
    $firstpage = 'exterior2';
  }
  else
  {
    $firstpage = 'exterior3';
  }
}

?>
<!doctype html>
<html>
 <head>
  <title>Virtual Event - Login</title>
  <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>  
  <link rel="stylesheet" href="../core/css/main.css" />
  <link rel="stylesheet" href="../core/css/header.css" />

  <!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-179015658-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-179015658-1');
	</script>
  
  <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-179015658-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-179015658-2');
  </script> -->


  <!-- <script src="loginjs.php?<?php echo rand();?>"></script> -->
  <script src="js/login.js?<?php echo rand();?>"></script>


  <!-- <script src="js/ga.js"></script>   -->

  <script>
$(document).ready(function () {
  var isIE11 = !!window.MSInputMethodContext && !!document.documentMode;
  if (isIE11) {
    document.getElementById('ie11Block').style.display = "block";
  }
  
  if (!navigator.cookieEnabled) {
	setTimeout(function() {
		$.fancybox.open({
			src  : '#cookieModal',
			type : 'inline',
			animationEffect: "zoom",
			animationDuration: modalFadeTime,
			opts : {
				touch: false,
				modal: true
			}
		});
	}, 1000);
  } else {
	$('.exterior').hide();
	
	var isMobile = false;

	if( $('body').css('display')=='none') {
		isMobile = true;       
	}
	isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

	if (isMobile == true) {
		window.addEventListener('orientationchange', doOnOrientationChange);
		doOnOrientationChange();
  }
<?php
  if(isset($_REQUEST['email'])) {
?>
  document.getElementById('em').value = "<?php echo $_REQUEST['email']; ?>";
  document.getElementById('pw').value = "<?php echo $_REQUEST['badgeID']; ?>";
  doLogin();
<?php
}
?>
  // changeRoute('<?php echo $firstpage; ?>');
}
});
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
    <div><img class="menu-logo" src="../core/images/logos/logo.png"/></div>
   </div>
  </div>

  <div id="exterior" class="fullscreen exterior">
  </div>

  <div id="cookieModal" class="login-modal" style="display: none; max-width: 800px;">
   	<h1>Enable Cookies</h1>
	<div><h4>Cookies are required to view this site.  Please enable cookies and refresh the page.</h4></div>
  </div>

  <div id="loginModal" class="login-modal" style="display: none; max-width: 800px;">
   <h1>Please Log In:</h1>
   <div id="errordetail" style="display:none;color: #ff0000;font-weight:bold;">No Error</div>
   <table>
    <tr>
     <td>Email Address:</td>
     <td><input type="text" id="em" name="em" size="40" maxlength="255" value="">
    </tr>
    <tr>
     <td>Password:</td>
     <td><input type="password" id="pw" name="pw" size="40" maxlength="40" value="">
    </tr>
    <tr>
     <td colspan="2">
      <br><br>
      <center>
       <div class="button-green" onclick="doLogin()">Log In</div>
      </center>
     </td>
    </tr>
   </table>
  
<?php
  if($config['user_forgotpassword'] == 1)
  {
?>
   <div class="login-buttons">
    <div class="login-back-button" onclick="openPasswordModal();">Forgot Password</div>
  </div>

  <div id="lostpwModal" class="login-modal" style="display: none; max-width: 800px;">
   <h1>Password Recovery:</h1>
   <div id="pwerrordetail" style="display:none;color: #ff0000;font-weight:bold;">No Error</div>
   Please enter your email address in the space below<br>
   and click the Recover Password button. A reset link<br>will be sent to you via email.<br><br>
   <table>
    <tr>
     <td>Email Address:</td>
     <td><input type="text" id="pwem" name="pwem" size="40" maxlength="255" value="">
    </tr>
    <tr>
     <td colspan="2">
      <br><br>
      <center>
       <div class="button-green" onclick="doPWProcess()">Recover Password</div>
       <div class="button-green" onclick="openLoginModal2()">Login Screen</div>
      </center>
     </td>
    </tr>
   </table>
  </div>
<?php
    if($function == "PWReset" && $keyvalid == 1 && $key != "")
    {
      echo "  <input type=\"hidden\" id=\"keyvalid\" name=\"keyvalid\" value=\"".$keyvalid."\">\n";
      echo "  <input type=\"hidden\" id=\"key\" name=\"key\" value=\"".$key."\">\n";
    }
  }

?>
<div id="ie11Block" class="ie-11-block">
  <div class="ie-11-text">Internet Explorer 11 is not supported.  Please use Chrome, Firefox or Edge</div>
</div>
 </body>
</html>

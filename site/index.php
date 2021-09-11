<?php
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    require_once('../core/common.php');

    if (validSession($_SESSION['userid'], $_SESSION['session_id'])) {
        updateSession($_SESSION['userid'], $_SESSION['session_id']);
    } else {
        // header("Location:index.php");
    }
?>

<!doctype html>
<html>
  <head>
    <title></title>

    <!-- THIRD PARTY IMPORTS ------------------------------------------------------------------ -->
   <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-190916547-88"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-190916547-88');
    </script>



    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

   
    <!-- Fancy Box -->
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    
    <!-- Moment -->
    <script src="../core/libs/moment/moment.js"></script>  
    <script src="../core/libs/moment/moment-timezone.js"></script>  
    
    <!-- Font Awesome (used for icons, may revisit) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- PROJECT IMPORTS -------------------------------------------------------------------- -->
    <!-- CORE ENUM IMPORTS ------------------------------------------------------------------ -->
    <script src="../core/enums/enumButtonActions.js?<?php echo rand();?>"></script>
    <script src="../core/enums/enumModalTypes.js?<?php echo rand();?>"></script>
    <script src="../core/enums/enumContentTypes.js?<?php echo rand();?>"></script>

    <!-- CORE-CONFIG ENUM IMPORTS ------------------------------------------------------------------ -->
    <script src="../core-config/enums/enumRoutes.js?<?php echo rand();?>"></script>  
    <script src="../core-config/enums/enumModals.js?<?php echo rand();?>"></script>  
    <script src="../core-config/enums/enumEvents.js?<?php echo rand();?>"></script>  

    <!-- CORE METHOD IMPORTS ------------------------------------------------------------------ -->
    <script src="../core/js/methodsBuild.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsActions.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsEvents.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsMobile.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsRouting.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsChat.js?<?php echo rand();?>"></script>

    <!-- CORE STYLE IMPORTS ------------------------------------------------------------------ -->
    <link rel="stylesheet" href="../core/css/global.css?<?php echo rand();?>" />
    <link rel="stylesheet" href="../core/css/header.css?<?php echo rand();?>" />
    <link rel="stylesheet" href="../core/css/modal.css?<?php echo rand();?>" />
    <link rel="stylesheet" href="../core/css/route.css?<?php echo rand();?>" />

    <!-- LOCAL METHOD IMPORTS ------------------------------------------------------------------ -->
    <script src="js/methodsLocalRouting.js?<?php echo rand();?>"></script>
    <script src="js/methodsLocal.js?<?php echo rand();?>"></script>

    <!-- LOCAL STYLE IMPORTS ------------------------------------------------------------------ -->
    <link rel="stylesheet" href="css/local.css?<?php echo rand();?>" />

    <!-- LOCAL DATA IMPORTS ------------------------------------------------------------------ -->
    <script src="../core-config/data/dataContent.js?<?php echo rand();?>"></script>  
    
    <!-- CORE-CONFIG IMPORTS ------------------------------------------------------------------ -->
    <script src="../core-config/config/configMainMenu.js?<?php echo rand();?>"></script>  
    <script src="../core-config/config/configRoutes.js?<?php echo rand();?>"></script> 
    <script src="../core-config/config/configModals.js?<?php echo rand();?>"></script> 
    <script src="../core-config/config/configGlobalVariables.js?<?php echo rand();?>"></script>
    <script src="../core-config/config/configSiteSettings.js?<?php echo rand();?>"></script>  

    
    <script>
        <?php if ($_SESSION['userid'] && $_SESSION['userdata']) { ?>
            var currentUser = <?php echo json_encode($_SESSION['userdata']); ?>;
            // console.log('user:', currentUser);
        <?php } ?>

        var remoteIP = 'NO REMOTE ADDR';
        <?php if ($_SERVER['REMOTE_ADDR']) { ?>
            remoteIP = '<?php echo $_SERVER['REMOTE_ADDR']; ?>';
        <?php } ?>
    </script>

    <!-- LOCAL INIT ------------------------------------------------------------------ -->
    <script src="js/init.js?<?php echo rand();?>"></script>

</head>
  <body>

    <!--WHERE ROUTES ARE CREATED OR ADDED MANUALLY-->
    <div id="mainMenuContainer" class="header"></div>

    <!--WHERE ROUTES ARE CREATED OR ADDED MANUALLY-->
    <div id="routeContainer"></div> 

    <!--WHERE MODALS ARE CREATED OR ADDED MANUALLY-->
    <div id="modalContainer"></div> 
    
    <!--WHERE NOTIFICATIONS ARE CREATED OR ADDED MANUALLY-->
    <div class="notifications-container"></div> 



    <!--GENERAL GLOBAL ELEMENTS-->
    <div id="mainLoading" class="loading">
      <div class='uil-ring-css' style='transform:scale(0.79);'>
        <div></div>
      </div>
      <div class="loading-text"></div>
    </div>

    <div id="portraitBlock"></div>
  </body>
</html>

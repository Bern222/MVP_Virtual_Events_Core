<?php
    $root = $_SERVER['DOCUMENT_ROOT'] . '/core-sandbox';

    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    // require_once($root . '/core/common.php');

    // REFACTOR: Need to find a new location for this check

    // if (validSession($_SESSION['userid'], $_SESSION['session_id'])) {
    //     updateSession($_SESSION['userid'], $_SESSION['session_id']);
    // } else {
    //     // header("Location:index.php");
    // }
?>

<!doctype html>
<html>
  <head>
    <title>Virtual Event</title>

    <!-- THIRD PARTY IMPORTS ------------------------------------------------------------------ -->

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-187044024-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-187044024-1');
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
    

    <!-- TODO Check if needed -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->




    <!-- CORE ENUM IMPORTS ------------------------------------------------------------------ -->
    <script src="../core/enums/enumButtonActions.js?<?php echo rand();?>"></script>


    <!-- LOCAL ENUM IMPORTS ------------------------------------------------------------------ -->
    <script src="enums/enumRoutes.js?<?php echo rand();?>"></script>  
    <script src="enums/enumModals.js?<?php echo rand();?>"></script>  
    <script src="enums/enumEvents.js?<?php echo rand();?>"></script>  

    <!-- LOCAL DATA IMPORTS ------------------------------------------------------------------ -->
    <script src="data/dataExternalLinks.js?<?php echo rand();?>"></script>  
    <script src="data/dataIframes.js?<?php echo rand();?>"></script> 

    <!-- LOCAL CONFIG IMPORTS ------------------------------------------------------------------ -->
    <script src="config/configMainMenu.js?<?php echo rand();?>"></script>  
    <script src="config/configRoutes.js?<?php echo rand();?>"></script> 
    <script src="config/configGlobalVariables.js?<?php echo rand();?>"></script>
    
    <script>
        <?php if ($_SESSION['userid'] && $_SESSION['userdata']) { ?>
            currentUser = <?php echo json_encode($_SESSION['userdata']); ?>;
        <?php } ?>

    </script>

 
    <!-- CORE METHOD IMPORTS ------------------------------------------------------------------ -->
    <script src="../core/js/methodsBuild.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsActions.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsEvents.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsMobile.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsRouting.js?<?php echo rand();?>"></script>
    <script src="../core/js/methodsChat.js?<?php echo rand();?>"></script>

    <link rel="stylesheet" href="../core/css/main.css?<?php echo rand();?>" />

    
    <!-- CORE COMPONENT IMPORTS ------------------------------------------------------------- -->
    
    <script src="../core/components/notification/notification.js?<?php echo rand();?>"></script>
    <link href="../core/components/notification/notification.css?<?php echo rand();?>"" rel="stylesheet">

    <script src="../core/components/session/session.js?<?php echo rand();?>"></script>
    
    <script src="../core/components/session-search/sessionSearch.js?<?php echo rand();?>"></script>
    <link href="../core/components/session-search/sessionSearch.css?<?php echo rand();?>"" rel="stylesheet">

    <script src="../core/components/bookmark/bookmark.js?<?php echo rand();?>"></script>
    <link href="../core/components/bookmark/bookmark.css?<?php echo rand();?>" rel="stylesheet">

    <script src="../core/components/agenda/agenda.js?<?php echo rand();?>"></script>
    <link href="../core/components/agenda/agenda.css?<?php echo rand();?>" rel="stylesheet">

    <script src="../core/components/loading/loading.js?<?php echo rand();?>"></script>
    <link href="../core/components/loading/loading.css?<?php echo rand();?>" rel="stylesheet">


    <!-- LOCAL METHOD IMPORTS ------------------------------------------------------------------ -->
    <script src="js/methodsLocalRouting.js?<?php echo rand();?>"></script>

    
    <!-- LOCAL INIT ------------------------------------------------------------------ -->
    <script src="js/init.js?<?php echo rand();?>"></script>




</head>
  <body>
    <div class="header">
        <div id="mainMenuContainer" class="main-menu">
            <!-- TODO Refactor -->
            <div class="main-menu-routes"></div>
            <div class='user-profile-container'>
                <div class="user-profile">
                    <div class="menu-item"><?php echo $_SESSION['chat_username']; ?></div>
                    <div class="profile-container">
                        <div class="subnav">
                        <div class="arrow"></div>
                        <ul>
                            <li><div class="subnav-button" style="font-weight: bold;" onclick="openProfile()">Your Profile</div></li>
                            <li><div class="subnav-button" onclick="logout()">Logout</div></li>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="routeContainer" class="default-container">
            Dy


    </div>



<!-- Modals ----------------------------------------------------------- -->
    <div id="auditoriumModal" class="modal-scroll modal-shadow modal-start-hidden modal-wide see-through-text-modal">
        <div class="modal-buttons">
            <!-- TODO these should be dynamic for all modals -->
            <div class="modal-button" onclick="$.fancybox.close(); openModalIframe('https://mvpcollaborative.com'">
                <img class="modal-list-thumb" src="../core/images/icons/icon-breakout.png"/>
                <div>
                    <div class="modal-menu-title">Auditorium iFrame</div>
                    <div class="modal-menu-description">Live</div>
                </div>
            </div>
            <div class="modal-button" onclick="$.fancybox.close(); openModalVideo('https://mvpcollaborative.com'">
                <img class="modal-list-thumb" src="../core/images/icons/icon-breakout.png"/>
                <div>
                    <div class="modal-menu-title">Auditorium Video</div>
                    <div class="modal-menu-description">Running Time: 10:33</div>
                </div>
            </div>
        </div>    
    </div>

    <div id="seminarRoomsModal" class="modal-shadow modal-start-hidden modal-menu-modal see-through-text-modal">
        <div class="modal-buttons">
            <div class="modal-button" style="padding-bottom: 20px;" onclick="changeRoute('seminarRoom1');">
                <img class="modal-list-thumb" src="../core/images/icons/icon-breakout.png"/>
                <div>
                    <div class="modal-menu-title">Seminar Room 1</div>
                    <div class="modal-menu-description"></div>
                </div>
            </div>
            <div class="modal-button" style="padding-bottom: 20px;" onclick="changeRoute('seminarRoom2');">
                <img class="modal-list-thumb" src="../core/images/icons/icon-breakout.png"/>
                <div>
                    <div class="modal-menu-title">Seminar Room 2</div>
                    <div class="modal-menu-description"></div>
                </div>
            </div>
            <div class="modal-button" style="padding-bottom: 20px;" onclick="changeRoute('seminarRoom3');">
                <img class="modal-list-thumb" src="../core/images/icons/icon-breakout.png"/>
                <div>
                    <div class="modal-menu-title">Seminar Room 3</div>
                    <div class="modal-menu-description"></div>
                </div>
            </div>
            <div class="auditorium-back-button" onclick="changeRoute('lobby')">Back</div>
        </div>
    </div>

    <div id="networkingLoungeModal" class="modal-shadow modal-start-hidden modal-menu-modal see-through-text-modal">
        <div class="modal-buttons">
            <div class="modal-button" onclick="$.fancybox.close(); changeRoute('networkingLounge1');">
                <img class="modal-list-thumb" src="../core/images/icons/icon-breakout.png"/>
                <div>
                    <div class="modal-menu-title">Networking Lounge 1</div>
                    <div class="modal-menu-description"></div>
                </div>
            </div>
            <div class="modal-button" onclick="$.fancybox.close(); changeRoute('networkingLounge2');">
                <img class="modal-list-thumb" src="../core/images/icons/icon-breakout.png"/>
                <div>
                    <div class="modal-menu-title">Networking Lounge 2</div>
                    <div class="modal-menu-description"></div>
                </div>
            </div>
            <div class="modal-button" onclick="$.fancybox.close(); changeRoute('networkingLounge3');">
                <img class="modal-list-thumb" src="../core/images/icons/icon-breakout.png"/>
                <div>
                    <div class="modal-menu-title">Networking Lounge 3</div>
                    <div class="modal-menu-description"></div>
                </div>
            </div>
            <div class="auditorium-back-button" onclick="changeRoute('lobby')">Back</div>
        </div>
    </div>

    <div id="profileModal" class="profile-modal" style="display: none; max-width: 800px;">
        <h2>Your Profile</h2>
        <div class="profile-information">
            <div class="profile-titles">
                <div class="profile-title">Display Name</div>
                <div class="profile-title">Email</div>
                <div class="profile-title">First Name</div>
                <div class="profile-title">Last Name</div>
                <div class="profile-title">Company</div>
                <div class="profile-title">Title</div>
            </div>
            <div class="profile-values">
                <div class="profile-value"><?php echo $_SESSION['userdata']['chat_username'];?></div>
                <div class="profile-value"><?php echo $_SESSION['userdata']['email'];?></div>
                <div class="profile-value"><?php echo $_SESSION['userdata']['firstname'];?></div>
                <div class="profile-value"><?php echo $_SESSION['userdata']['lastname'];?></div>
                <div class="profile-value"><?php echo $_SESSION['userdata']['company'];?></div>
                <div class="profile-value"><?php echo $_SESSION['userdata']['title'];?></div>
            </div>
        </div>
        <div class="nofification-toggle-container">
            <div class="show-notifications-text">Show Session Notifications</div>
            <label class="switch">
                <input id="showNotificationCheckbox" type="checkbox" value="true" onclick="toggleShowNotifications()"/>
                <span class="slider round"></span>
            </label>      
        </div>
    </div>

    <div id="informationDeskModal" class="agenda-menu-modal" style="display: none; max-width: 800px;">
        <div>
            <h4>Add Content Here</h4>
        </div>
        <div class="exhibit-block-button" onclick="$.fancybox.close();">Close</div>
    </div>

    <div id="profanityModal" class="not-availalbe-modal see-through-text-modal" style = "display: none; width: 400px;">
        <!-- <h2>Not Available</h2> -->
        <div>
            <h4>Your message contains inappropriate language.</h4>
        </div>
        <div class="exhibit-block-button" onclick="$.fancybox.close();">Close</div>
    </div>

<!-- 

    <div id="triviaModal" class="modal-shadow modal-start-hidden modal-menu-modal see-through-text-modal">
        <h2>Trivia</h2>
        <div class="trivia-intro-container">
            <div class="trivia-intro-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque a nisl at orci cursus elementum. Pellentesque enim libero, accumsan vitae tempor ut, iaculis sed ipsum. Nunc sodales, felis ut tempor varius, odio mauris pharetra odio, at sodales diam elit a tellus. Curabitur eget lobortis diam. Nunc in dolor quis erat vestibulum venenatis. Duis tempus turpis quis nisl feugiat mollis. Nunc sit amet mattis erat, ut sagittis diam. Donec semper enim auctor fringilla auctor. Phasellus hendrerit lectus eros, eu hendrerit lorem fringilla a. Nam efficitur nulla vel sodales rhoncus. Quisque molestie fermentum sapien, eget dictum quam placerat nec. Pellentesque finibus posuere elit vel malesuada.</div>
            <div class="trivia-final-text">Thanks for completing the quiz.</div>      
            <div class="button-green trivia-continue-button" onclick="startQuiz();">Start Quiz</div>   
        </div>
        <div class="trivia-questions-container">
            <div class="trivia-questions">
            </div>
            <div class="trivia-buttons-container">
                <div class="button-green trivia-nav-button trivia-nav-previous" onclick="previousQuestion()">< Previous</div>
                <div class="button-green trivia-nav-button trivia-nav-next" onclick="nextQuestion()">Next ></div>
            </div>
        </div>
    </div> -->

    

    <!-- <div id="sessionSearchModal" class="search-modal" style="display: none; max-width: 800px;">
        <h2>Session Search</h2>
        <p>Search for a session by session name / speaker name</p>
        <div class="session-search-input-container">
            <input id="sessionSearchInput" class="session-search-input" type="text"/>
            <div class="session-search-button" onclick="searchSessions()">
                <img class="session-search-button-image" src="images/icon-magnifying-glass.png"/>
            </div>
        </div>
        <div class="session-list-container">
        </div>
    </div> -->


    
    <!-- <div id="agendaModal" class="agenda-modal" style="display: none;">
        <h2>CONFERENCE SCHEDULE AT A GLANCE</h2>
        <div class="bookmark-description">
            <div class="bookmark-circle"></div>
            <div> -  Denotes eShow Bookmarked Sessions</div>
        </div>
        <div class="agenda-tab-menu-container">
            <div class="tab all-sessions-tab tab-selected" onclick="agendaTabSelected('all-sessions-tab')">All Sessions</div>
            <div class="tab bookmarked-sessions-tab" onclick="agendaTabSelected('bookmarked-sessions-tab')">eShow Bookmarked Sessions</div>
        </div>
        <div class="agenda-session-list-container"></div>
    </div> -->

    <!-- <div id="shareModal" class="share-modal modal-shadow modal-start-hidden">
        <div class="share-container">
            <div class="share-item">
                <img class="share-image" src="images/share/twitter-image.png" onclick="openExternalLink('https://mvpcollaboritive.com')"/>
            </div>
            <div class="share-item">
                <img class="share-image" src="images/share/facebook-image.png" onclick="openExternalLink('https://mvpcollaboritive.com')"/>
            </div>
            <div class="share-item">
                <img class="share-image" src="images/share/linkedin-image.png" onclick="openExternalLink('https://mvpcollaboritive.com')"/>
            </div>
        </div>
        <div><h3>Share on social media and use hashtag #MVPCollaborative</h3></div>
    </div> -->

    


<!-- General ----------------------------------------------------------- -->

    <div id="chatwindow" style="display: none;">
        <div id="chatwindowheader">
            <div id="chatToggle" class="toggle-group">
                <div class="toggle-button" onclick="toggleChatRoom()">Chat Room</div>
                <div id="videoChatButton" class="toggle-button" onclick="toggleVideoChat()">Video Chat</div>
                <div id="adminChatButton" class="toggle-button" onclick="togglePrivateMessage('admin')">Private Message</div>
                <div id="userChatButton" class="toggle-button" onclick="togglePrivateMessage('user')">Talk to the Sponsor</div>
            </div>
            <div class="close-button" onclick="closeChatWidnow()">X</div>
        </div>
        <div class="room-dropdown">
            <label for="chatDropdown">Chat Room:</label>
            <select name="chatDropdown" id="chatDropdown" class="chat-dropdown" onchange="updateChat(this.value)">
           
            </select>
        </div>
        <iframe id="chatframe" class='chat' src="about:blank"></iframe>
        <div id="zoomContainer" class="zoom-container">
            <div class="zoom-flex">
                <!-- <div class="zoom-badge">4</div> -->
                <img class="zoom-img" src="../core/images/logos/logo-zoom.jpg" onclick="openZoomLink()"/>
                <div class="zoom-text" onclick="openZoomLink()">Click to Open Zoom</div>
                <div class="zoom-participate-text"></div>
            </div>
        </div>
        <div id="teamsContainer" class="teams-container">
            <div class="teams-flex">
                <!-- <div class="zoom-badge">4</div> -->
                <img class="teams-img" src="../core/images/logos/logo-teams.jpg" onclick="openTeamsLink()"/>
                <div class="teams-text" onclick="openTeamsLink()">Click to Open Teams</div>
                <div class="teams-participate-text"></div>
            </div>
        </div>
        <div id="webexContainer" class="webex-container">
            <div class="webex-flex">
                <!-- <div class="zoom-badge">4</div> -->
                <img class="webex-img" src="../core/images/logos/logo-webex.png" onclick="openWebexLink()"/>
                <div class="webex-text" onclick="openWebexLink()">Click to Open Webex</div>
                <div class="webex-participate-text"></div>
            </div>
        </div>
        <div id="googleMeetContainer" class="google-meet-container">
            <div class="google-meet-flex">
                <!-- <div class="zoom-badge">4</div> -->
                <img class="google-meet-img" src="../core/images/logos/logo-google-meet.jpg" onclick="openGoogleMeetLink()"/>
                <div class="google-meet-text" onclick="openGoogleMeetLink()">Click to Open Google Meet</div>
                <div class="google-meet-participate-text"></div>
            </div>
        </div>
    </div>	

    <div id="mainLoading" class="loading">
      <div class='uil-ring-css' style='transform:scale(0.79);'>
        <div></div>
      </div>
      <div class="loading-text"></div>
    </div>

    <div class="notifications-container"> 
         <!-- Dynamically Generated -->
    </div>
  </body>
</html>

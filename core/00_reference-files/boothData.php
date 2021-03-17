<?php
   // ini_set('display_errors', 1);
   // ini_set('display_startup_errors', 1);
   // error_reporting(E_ALL);
   
   
   require_once("common.php");
   // $dbhost = '127.0.0.1';
   // $dbuser = 'womenofcoloronln_dbuser';
   // $dbpass = 'S!1SL$O,We;p';
   // $dbname = 'womenofcoloronln_data';

   // $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if (!$db) {
      echo "Error: Unable to connect to MySQL." . PHP_EOL;
      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
      exit;
   }

   $method = $_POST['method'];

   switch($method){
         case "getBooths": echo json_encode(getBooths());
         break;
         case "getBooth": echo json_encode(getBooth());
         break;
       
         case "updateBooth": echo updateBooth();
         break;
         case "updateBoothVideo": echo updateBoothVideo();
         break;
        
         case "getJobPostings": echo json_encode(getJobPostings());
         break;
         case "addJobPosting": echo addJobPosting();
         break;
         case "deleteJobPosting": echo deleteJobPosting();
         break;
        
         case "getDocuments": echo json_encode(getDocuments());
         break;
         case "addDocument": echo addDocument();
         break;
         case "deleteDocument": echo deleteDocument();
         break;
         case "getChatRooms": echo json_encode(getChatRooms());
         break;
         case "getChatRoomsByRoomName": echo json_encode(getChatRoomsByRoomName());
         break;         
         case "getChatRoom": echo json_encode(getChatRoom());
         break;
         case "addChatRoom": echo addChatRoom();
         break;
         case "deleteChatRoom": echo deleteChatRoom();
         break;
         case "updateChatRoom": echo updateChatRoom();
         break;
         case "setOnlineBoothId": echo setOnlineBoothId();
         break;
         case "updateWebsiteUrl": echo updateWebsiteUrl();
         break;
         case "checkSponsorMessages": echo checkSponsorMessages();
         break;
         case "checkNewChatMessages": echo checkNewChatMessages();
         break;
         case "checkNewChatMessagesPerUser": echo checkNewChatMessagesPerUser();
         break;
   }

   function getBooths() {
      global $config;
      global $db;

      $isDevelopment = $_POST['isDevelopment'];
      
      $results = array();
      
      $q = "SELECT * FROM booths WHERE is_development = " . $isDevelopment;
      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return $results;
    }

    function getJobPostings() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];

      $results = array();
      
      $q = "SELECT * FROM job_postings WHERE booth_id = " . $boothId;
      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return $results;
    }

    function getBooth() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];

      $results = array();
      
      $q = "SELECT * FROM booths WHERE id=" . $boothId;
      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return $results;
    }

    function getDocuments() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];

      $results = array();
      
      $q = "SELECT * FROM documents WHERE booth_id = " . $boothId;
      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return $results;   
    }

    function updateBooth() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $companyName = addslashes($_POST['companyName']);
      $companyDescription = addslashes($_POST['companyDescription']);
      $resumeEmail = $_POST['resumeEmail'];
      $contactEmail = $_POST['contactEmail'];
      $zoomGreeterEmail = $_POST['zoomGreeterEmail'];
      $twitterUrl = $_POST['twitterUrl'];
      $youtubeUrl = $_POST['youtubeUrl'];
      $facebookUrl = $_POST['facebookUrl'];
      $linkedinUrl = $_POST['linkedinUrl'];
      
      $q = "UPDATE booths SET company_name = '" . $companyName . "', company_description = '" . $companyDescription . "', twitter_url = '" . $twitterUrl . "', youtube_url = '" . $youtubeUrl . "', facebook_url = '" . $facebookUrl . "', linkedin_url = '" . $linkedinUrl . "', resume_email = '" . $resumeEmail . "', zoom_greeter_email = '" . $zoomGreeterEmail . "', contact_email = '" . $contactEmail . "' WHERE id = " . $boothId;
      $r = $db->query($q);

      return $q;
      // return getBooths();
   }

   function updateBoothVideo() {
      
      // update thumbnail image file
      file_put_contents("images/booths/booth-sponsors/".$_POST['boothId']."/".$_POST['videoId'].".jpg", file_get_contents($_POST['thumbURL']));


      // update database
      
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $videoFieldName = $_POST['videoFieldName'];
      $url = $_POST['embedCode'];
      
      $q = "UPDATE booths SET " . $videoFieldName . " = '" . $url . "' WHERE id = " . $boothId;
      $r = $db->query($q);

      return $q;
      // return getBooths();
   }

   function addJobPosting() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $title = $_POST['title'];
      $url = $_POST['url'];
      
      $q = "INSERT INTO job_postings (title, url, booth_id) VALUES ('" . $title . "', '" . $url . "', " . $boothId . ")";
      $r = $db->query($q);

      return 'success';
   }

   function deleteJobPosting() {
      global $config;
      global $db;

      $postingId = $_POST['postingId'];
      
      $q = "DELETE FROM job_postings WHERE id = " . $postingId;
      $r = $db->query($q);

      return 'success';
   }



   function addDocument() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $title = $_POST['title'];
      $url = $_POST['url'];
      
      $q = "INSERT INTO documents (title, url, booth_id) VALUES ('" . $title . "', '" . $url . "', " . $boothId . ")";
      $r = $db->query($q);

      return 'success';
   }

   function deleteDocument() {
      global $config;
      global $db;

      $documentId = $_POST['documentId'];
      
      $q = "DELETE FROM documents WHERE id = " . $documentId;
      $r = $db->query($q);

      return 'success';
   }

   function getChatRoomsByRoomName() {
      global $config;
      global $db;

      $roomName = $_POST['roomName'];

      $results = array();

      $boothId = '1';
      
      $q = "SELECT * FROM chat_room WHERE room_name = '" . $roomName . "'";

      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return $results;
    }


   function getChatRoomsByBoothId() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];

      $results = array();
      
      $q = "SELECT * FROM chat_room WHERE booth_id = " . $boothId;
      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return $results;
    }

   function addChatRoom() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $name = $_POST['name'];
      
      $q = "INSERT INTO chat_room (name, booth_id) VALUES ('" . $name . "', " . $boothId . ")";
      $r = $db->query($q);

      return 'success';
   }

   function deleteChatRoom() {
      global $config;
      global $db;

      $chatRoomId = $_POST['chatRoomId'];
      
      $q = "DELETE FROM chat_room WHERE id = " . $chatRoomId;
      $r = $db->query($q);

      return 'success';
   }

   function updateChatRoom() {
      global $config;
      global $db;

      $chatRoomId = $_POST['chatRoomId'];
      $name = $_POST['name'];
      
      $q = "UPDATE chat_room SET name = '" . $name . "' WHERE id = " . $chatRoomId;
      $r = $db->query($q);

      return 'success';
   }

   function setOnlineBoothId() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $userId = $_POST['userId'];
      
      $q = "UPDATE users SET online_booth_id = '" . $boothId . "', online_booth_timestamp = NOW() WHERE id = " . $userId;
      // $q = "UPDATE users SET online_booth_id = '" . $boothId . "' WHERE id = " . $userId;

      $r = $db->query($q);

      return 'success';
   }

   function pingOnlineBoothId() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $userId = $_POST['userId'];
      
      $q = "UPDATE users SET online_booth_id = '" . $boothId . "' WHERE id = " . $userId;
      $r = $db->query($q);

      return 'success';
   }

   function updateWebsiteUrl() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $websiteUrl = $_POST['websiteUrl'];
      
      $q = "UPDATE booths SET website_url = '" . $websiteUrl . "' WHERE id = " . $boothId;
      $r = $db->query($q);

      return 'success';
   }

   function checkSponsorMessages() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $userId = $_POST['userId'];

      $results = array();
      
      $q = "SELECT * FROM chat WHERE booth_id = " . $boothId . " AND " . "reciever_userid= " . $userId . " AND chat_room_id IS NULL";
      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return json_encode($results);
    }

   function checkNewChatMessages() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $userId = $_POST['userId'];

      $results = array();
      
      $q = "SELECT * FROM chat WHERE booth_id = " . $boothId . " AND " . "reciever_userid= " . $userId . " AND viewed=false AND chat_room_id IS NULL";
      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return json_encode($results);
    }

    function checkNewChatMessagesPerUser() {
      global $config;
      global $db;

      $boothId = $_POST['boothId'];
      $userId = $_POST['userId'];
      $sponsorId = $_POST['sponsorId'];

      $results = array();
      
      $q = "SELECT * FROM chat WHERE booth_id = " . $boothId . " AND " . "reciever_userid= " . $userId . " AND " . "sender_userid= " . $sponsorId . " AND viewed=false AND chat_room_id IS NULL";
      $r = $db->query($q);

      $results = array();
      while($row = mysqli_fetch_assoc($r)) {
         $results[] = $row;
      }
      return json_encode($results);
    }

   //  function getAllSales() {
   //    global $config;
   //    global $db;

   //    $results = array();
      
   //    $q = "SELECT * FROM eShowSalesItemsIncludeAllSales WHERE userid=" . $_SESSION['userid'];

   //    // Temp For Dev
   //    // $q = "SELECT * FROM eShowSalesItemsIncludeAllSales WHERE userid=12";

   //    $r = $db->query($q);

   //    $results = array();
   //    while($row = mysqli_fetch_assoc($r)) {
   //       $results[] = $row;
   //    }
   //    return json_encode($results);
   //  }

   //  function getAccessByDay() {
   //    global $config;
   //    global $db;

   //    $salesId = $_POST['salesId'];

   //    $results = array();
   //    $q = "SELECT * FROM access_by_day WHERE sales_id='". $salesId . "'";
   //    $r = $db->query($q);

   //    $results = array();
   //    while($row = mysqli_fetch_assoc($r)) {
   //       $results[] = $row;
   //    }
   //    return json_encode($results);
   //  }

?>
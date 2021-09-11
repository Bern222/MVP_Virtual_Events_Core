<?php
  ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);

/* Getting file name */
$filename = $_FILES['file']['name'];
$boothId = $_POST['boothId'];
$uploadFilename = $_POST['uploadFilename'];
$filePath = $_POST['filePath'];
$backupSubFolder = $_POST['backupSubFolder'];

// $strippedFilename = preg_replace("/[^a-zA-Z0-9]/", "", $uploadFilename);
$strippedFilename = str_replace( "'", "", $uploadFilename);
// $strippedFilename = $uploadFilename;
/* Location */
$location = $filePath . "/booths/booth-sponsors/". $boothId . "/documents/" . $strippedFilename;

$milliseconds = round(microtime(true) * 1000);

$backupLocation = "../../booth-backup/" . $backupSubFolder . "/documents/booths/booth-sponsors/". $boothId . "/documents/" . strval($milliseconds) . '_' . $uploadFilename;

// $location = "images/booths/booth-sponsors/".$filename;
$uploadOk = 1;
$imageFileType = pathinfo($location,PATHINFO_EXTENSION);

/* Valid Extensions */
$valid_extensions = array("xls", "xlsx", "doc", "docx", "ppt", "pptx", "pdf", "zip");
/* Check file extension */
if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
   $uploadOk = 0;
}

if($uploadOk == 0){
   echo 0;
}else{
   /* Upload file */
   if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
      copy($location, $backupLocation);
      echo $location;
   }else{
      echo 0;
   }
}
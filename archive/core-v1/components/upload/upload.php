

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

/* Location */
$location = $filePath . "/booths/booth-sponsors/". $boothId . "/" . $uploadFilename;
// $location = "images/booths/booth-sponsors/".$filename;

$milliseconds = round(microtime(true) * 1000);

$backupLocation = "../../booth-backup/" . $backupSubFolder . "/images/booths/booth-sponsors/". $boothId . "/" . strval($milliseconds) . '_' . $uploadFilename;

$uploadOk = 1;
$imageFileType = pathinfo($location,PATHINFO_EXTENSION);

/* Valid Extensions */
$valid_extensions = array("jpg", "pdf", "png");
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
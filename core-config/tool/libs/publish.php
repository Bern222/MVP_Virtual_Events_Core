<?php
//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);
//    error_reporting(E_ALL);
   
//    require_once($configPath  . "../core/common.php");

    // TODO: NEED TO FIGURE OUT WHY THIS ISN'T WORKING
    $configPath = '';


    $method = $_POST['method'];

    switch($method){
            case "publishContent": publishContent();
            break;
            case "publishConfigs": publishConfigs();
            break;
            case "updateSession": echo json_encode(updateSessionTimestamp());
            break;
    }

    function publishContent() {
            global $config;
            global $db;

            $dataContent = $_POST['dataContent'];

            $resultArray = array();

            $fileDataContent = fopen($configPath  . "../../config-data/adr/data/dataContent.js", "w") or die("Unable to open file!");
            fwrite($fileDataContent, $dataContent);
            fclose($fileDataContent);
            array_push($resultArray, 'content saved');
            return 'Success';
    }

    function publishConfigs() {
        global $config;
        global $db;

        $enumEvents = $_POST['enumEvents'];
        $enumModals = $_POST['enumModals'];
        $enumRoutes = $_POST['enumRoutes'];

        $configSiteSettings = $_POST['configSiteSettings'];
        $configGlobalVariables = $_POST['configGlobalVariables'];
        $configMainMenu = $_POST['configMainMenu'];
        $configModals = $_POST['configModals'];
        $configRoutes = $_POST['configRoutes'];

        $resultArray = array();

        if ($enumEvents) {
            $fileEnumEvents = fopen($configPath  . "../../config-data/adr/enums/enumEvents.js", "w") or die("Unable to open file!");
            fwrite($fileEnumEvents, $enumEvents);
            array_push($resultArray, 'events saved');
        } else {
            array_push($resultArray, 'events not updated');
        }

        if ($enumModals) {
            $fileEnumModals = fopen($configPath  . "../../config-data/adr/enums/enumModals.js", "w") or die("Unable to open file!");
            fwrite($fileEnumModals, $enumModals);
            array_push($resultArray, 'Enum Modals saved');
        } else {
            array_push($resultArray, 'Enum Modals not updated');
        }

        if ($enumRoutes) {
            $fileEnumRoutes = fopen($configPath  . "../../config-data/adr/enums/enumRoutes.js", "w") or die("Unable to open file!");
            fwrite($fileEnumRoutes, $enumRoutes);
            array_push($resultArray, 'Enum Routes saved');
        } else {
            array_push($resultArray, 'Enum Routes not updated');
        }

        if ($configSiteSettings) {
            $fileConfigSiteSettings = fopen($configPath  . "../../config-data/adr/config/configSiteSettings.js", "w") or die("Unable to open file!");
            fwrite($fileConfigSiteSettings, $configSiteSettings);
            fclose($fileConfigSiteSettings);
            array_push($resultArray, 'Config Site Settings saved');
        } else {
            array_push($resultArray, 'Config Site Settings not updated');
        }

        if ($configGlobalVariables) {
            $fileConfigGlobalVariables = fopen($configPath  . "../../config-data/adr/config/configGlobalVariables.js", "w") or die("Unable to open file!");
            fwrite($fileConfigGlobalVariables, $configGlobalVariables);
            fclose($fileConfigGlobalVariables);
            array_push($resultArray, 'Config Global Variables saved');
        } else {
            array_push($resultArray, 'Config Routes not updated');
        }

        if ($configMainMenu) {
            $fileConfigMainMenu = fopen($configPath  . "../../config-data/adr/config/configMainMenu.js", "w") or die("Unable to open file!");
            fwrite($fileConfigMainMenu, $configMainMenu);
            fclose($fileConfigMainMenu);
            array_push($resultArray, 'Config Main Menu saved');
        } else {
            array_push($resultArray, 'Config Main Menu not updated');
        }

        if ($configModals) {
            $fileConfigModals = fopen($configPath  . "../../config-data/adr/config/configModals.js", "w") or die("Unable to open file!");
            fwrite($fileConfigModals, $configModals);
            fclose($fileConfigModals);
            array_push($resultArray, 'Config Modals saved');
        } else {
            array_push($resultArray, 'Config Modals not updated');
        }

        if ($configRoutes) {
            $fileConfigRoutes = fopen($configPath  . "../../config-data/adr/config/configRoutes.js", "w");
            fwrite($fileConfigRoutes, $configRoutes);
            fclose($fileConfigRoutes);
            array_push($resultArray, 'Config Routes saved');
        } else {
            array_push($resultArray, 'Config Routes not updated');
        }
        // TODO: not being hit
        return 'Success';
    }

    function updateSessionTimestamp() {
        return updateSession($_SESSION['userid'], $_SESSION['session_id']);
    }
?>
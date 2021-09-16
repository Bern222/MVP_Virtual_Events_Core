<html>
    <head>
        <title>MVP - Site Configurator - Checking Config Status</title>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
        <script  src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
        <!-- Fancy Box -->
        <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>  
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

        <link rel="stylesheet" href="css/main.css" />
        <link rel="stylesheet" href="css/icons.css" />

        <script>
            const fileList = [
                {
                    title: 'Core - Enum Button Actions',
                    path: '../../core/enums/enumButtonActions.js'
                },
                {
                    title: 'Core - Enum Modal Types',
                    path: '../../core/enums/enumModalTypes.js'
                },
                {
                    title: 'Core - Enum Content Types',
                    path: '../../core/enums/enumContentTypes.js'
                },
                {
                    title: 'Core - Enum Routes',
                    path: '../enums/enumRoutes.js'
                },
                {
                    title: 'Core - Enum Modals',
                    path: '../enums/enumModals.js'
                },
                {
                    title: 'Core - Enum Events',
                    path: '../enums/enumEvents.js'
                },
                {
                    title: 'Core - Data Content',
                    path: '../data/dataContent.js'
                },
                {
                    title: 'Core Config - Main Menu',
                    path: '../config/configMainMenu.js'
                },
                {
                    title: 'Core Config - Routes',
                    path: '../config/configRoutes.js'
                },
                {
                    title: 'Core Config - Modals',
                    path: '../config/configModals.js'
                },
                {
                    title: 'Core Config - Global Variables',
                    path: '../config/configGlobalVariables.js'
                }
            ];


            $(document).ready(function() {
                $('#routeLanding').show();
                $('#routeLanding').fadeTo(1000,1);
            
                var count = 0;
                var failedCount = 0;
                var statusInterval = setInterval(function () {
                    
                    var icon = '';
                    if (fileList[count]) {
                        $.ajax({
                            url: fileList[count].path,
                            type:'HEAD',
                            error: function()
                            {
                                failedCount++;
                                icon = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2"><circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/><line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/><line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/></svg>';
                                console.log('error success:', icon);
                                $('.configurator-landing-status-container').append('<div class="configurator-status-row"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2"><circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/><line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/><line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/></svg>' + fileList[count].title + '</div>');
                                count++;
                            },
                            success: function()
                            {
                                icon = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2"><circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/><polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/></svg>';
                                console.log('icon success:', icon);
                                $('.configurator-landing-status-container').append('<div class="configurator-status-row">' + icon + fileList[count].title + '</div>');
                                count++;
                            }
                        });

                    } 
                        
                    if(count >= fileList.length) {
                        clearInterval(statusInterval);
                        if(failedCount > 0) {
                            $('#errorText').show();
                            $('#errorText').fadeTo(500, 1);
                        } else {
                            console.log('SHOW');
                            $('#continueButton').show();
                            $('#continueButton').fadeTo(500, 1);
                        }
                    }
                    
                }, 200);
            });

        </script>

    </head>
    <body>
        <!-- ROUTES -->
        <div id="routeLanding" class="configurator-route configurator-route-landing">
            <!-- Page that shows status of all files and core setup -->
            <div class="configurator-landing-container">
                <div class="configurator-landing-logo">
                    <img src="images/logo_mvp_yellow.png"/>
                </div>
                <div class="configurator-landing-status-container"></div>
                <div id="continueButton" class="default-button green-button hidden" onclick="window.open('siteConfigurator.php');">Good to go!</div>
                <div id="errorText" class="configurator-error-text hidden">Something went wrong, contact tech support (probably Mike or Ben)</div>
            </div>
        </div>
    </body>
</html>
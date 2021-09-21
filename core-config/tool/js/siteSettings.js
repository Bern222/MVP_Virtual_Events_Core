var currentSiteSettings;

function loadSiteSettings() {
    console.log('Starting Page', configSiteSettings.startingRoute);
    $('#inputSiteSettingsTitle').val(configSiteSettings.title);
    $('#inputSiteSettingsStartingPage').val(configSiteSettings.startingRoute);
    $('#inputSiteSettingsRouteFadeTime').val(configSiteSettings.routeFadeTime);
    $('#inputSiteSettingsModalFadeTime').val(configSiteSettings.modalFadeTime);
    $('#inputSiteSettingsLandscapeLock').prop('checked', configSiteSettings.enableLandscapeLock);
    $('#inputSiteSettingsLandscapeMessage').val(configSiteSettings.landscapeLockMessage);
    $('#inputSiteSettingsEventLogging').prop('checked', configSiteSettings.enableEventLogging);
    $('#inputSiteSettingsForceRefresh').prop('checked', configSiteSettings.enableForceRefresh);
    $('#inputSiteSettingsForceRefreshInterval').val(configSiteSettings.forceRefreshInterval);
    $('#inputSiteSettingsCloseWindowWarning').prop('checked', configSiteSettings.enableCloseWindowWarning);
    $('#inputSiteSettingsCloseWindowMessage').val(configSiteSettings.closeWindowMessage);
    $('#inputSiteSettingsHashNavigation').prop('checked', configSiteSettings.enableHashNavigation);
    $('#inputSiteSettingsGA').val(configSiteSettings.gaKey);

    console.log('SITE SETTINGS:', configSiteSettings);
}

function saveSiteSettings() {
    currentSiteSettings = {
        title: $('#inputSiteSettingsTitle').val(),
        startingRoute: $('#inputSiteSettingsStartingPage').val(),
        routeFadeTime: $('#inputSiteSettingsRouteFadeTime').val(),
        modalFadeTime: $('#inputSiteSettingsModalFadeTime').val(),
        enableLandscapeLock: $('#inputSiteSettingsLandscapeLock').prop('checked'),
        landscapeLockMessage: $('#inputSiteSettingsLandscapeMessage').val(),
        enableEventLogging: $('#inputSiteSettingsEventLogging').prop('checked'),
        enableForceRefresh: $('#inputSiteSettingsForceRefresh').prop('checked'),
        enableCloseWindowWarning: $('#inputSiteSettingsCloseWindowWarning').prop('checked'),
        closeWindowMessage: $('#inputSiteSettingsCloseWindowMessage').val(),
        enableHashNavigation: $('#inputSiteSettingsHashNavigation').prop('checked'),
        gaKey: $('#inputSiteSettingsGA').val(),
        forceRefreshInterval: $('#inputSiteSettingsForceRefreshInterval').val(),
    
        // TODO: NEED TO FIGURE THIS OUT
        currentRouteIndex: 0,
        
        // TODO: Maybe breakout a advanced settings at the bottom
        updateSessionInterval: 1800000,
        defaultModalOpts: {
            touch: false,
            toolbar: true,
            modal: false
        }
    }; 

    console.log('Current site settings:', currentSiteSettings);
    configSiteSettings = currentSiteSettings;
    updateStatus.configSiteSettings = true;
    $('#publishButton').removeClass('disabled-button');
    $('#publishButton').addClass('green-button');
    showAlert(iconSuccess + ' Site Settings Saved');

}

function discardSiteSettings() {
    // TODO: save original site settings
}
var currentSiteSettings;

function loadSiteSettings() {
    $('#inputSiteSettingsTitle').val(configSiteSettings.title);
    $('#inputSiteSettingsStartingPage').val(configSiteSettings.startingRoute);
    $('#inputSiteSettingsRouteFadeTime').val(configSiteSettings.routeFadeTime);
    $('#inputSiteSettingsModalFadeTime').val(configSiteSettings.modalFadeTime);
    $('#inputSiteSettingsLandscapeLock').prop('checked', configSiteSettings.enableLandscapeLock);
    $('#inputSiteSettingsLandscapeMessage').val(configSiteSettings.landscapeLockMessage);
    $('#inputSiteSettingsEventLogging').prop('checked', configSiteSettings.enableEventLogging);
    $('#inputSiteSettingsForceRefresh').prop('checked', configSiteSettings.enableForceRefresh);
    $('#inputSiteSettingsForceRefreshInterval').val(configSiteSettings.forceRefreshInterval);
    $('#inputSiteSettingsCloseWindowWarning').prop(configSiteSettings.enableCloseWindowWarning);
    $('#inputSiteSettingsCloseWindowMessage').val(configSiteSettings.closeWindowMessage);
    $('#inputSiteSettingsHashNavigation').prop(configSiteSettings.enableHashNavigation);
    $('#inputSiteSettingsGA').val(configSiteSettings.gaKey);
}

function saveSiteSettings() {
    currentSiteSettings = {
        title: $('#inputSiteSettingsTitle').val(),
        startingRoute: $('#inputSiteSettingsStartingPage').val(),
        routeFadeTime: $('#inputSiteSettingsRouteFadeTime').val(),
        modalFadeTime: $('#inputSiteSettingsModalFadeTime').val(),
        enableLandscapeLock: $('#inputSiteSettingsLandscapeLock').val(),
        landscapeLockMessage: $('#inputSiteSettingsLandscapeMessage').val(),
        enableEventLogging: $('#inputSiteSettingsEventLogging').val(),
        enableForceRefresh: $('#inputSiteSettingsForceRefresh').val(),
        enableCloseWindowWarning: $('#inputSiteSettingsCloseWindowWarning').val(),
        closeWindowMessage: $('#inputSiteSettingsCloseWindowMessage').val(),
        enableHashNavigation: $('#inputSiteSettingsHashNavigation').val(),
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
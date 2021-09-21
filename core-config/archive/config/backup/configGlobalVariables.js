var welcomeVideoPlayed = false;

const enableEventLogging = true;
const enableForceRefresh = false;
const forceRefreshInterval = 60000;
const enableCloseWindowWarning = true;
const updateSessionInterval = 1800000;
const routeFadeTime = 600;
const modalFadeTime = 600;
const enableMobileLandscapeLock = true;

const defaultModalOpts = {
    touch: false,
    toolbar: true,
    modal: true
};

// Routing
const enableHashNavigation = true;
const minNavigationIndex = 3;
const maxNavigationIndex = 8;
const loopRoutes = true;

var mainMenuRouteChange = false;

// Enables removal of previous timouts for autoplay videos 
var currentAutoPlayVideoTimeout;

var currentUser =  {id: -1, email: ''};

// TODO: Revisit these
var currentRoute;  // ALSO DEFAULT ROUTE ON LOAD UNLESS SENT IN GET VAR (TODO NEED TO ADD THIS BACK)
var currentRouteIndex = 0;

var isMobile = false;
var mobileOrientation = 'landscape';
var landscapeLockMessage = 'Best viewed horizontally';
var exhibitHallStatus = '000000000';

var currentServerTime;
var eventInterval;

var spinLoaded = false;

  

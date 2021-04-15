
const enableEventLogging = true;
const enableForceRefresh = false;
const forceRefreshInterval = 60000;
const enableCloseWindowWarning = true;
const updateSessionInterval = 1800000;
const routeFadeTime = 600;
const modalFadeTime = 600;
const enableMobileLandscapeLock = true;

// Routing
const enableHashNavigation = true;
const minNavigationIndex = 3;
const maxNavigationIndex = 8;
const loopRoutes = true;

// Enables removal of previous timouts for autoplay videos 
var currentAutoPlayVideoTimeout;

var currentUser =  {id: 0};

// TODO: Revisit these
var currentRoute = enumRoutes.EXTERIOR;  // ALSO DEFAULT ROUTE ON LOAD UNLESS SENT IN GET VAR (TODO NEED TO ADD THIS BACK)
var currentRouteIndex = 0;

var isMobile = false;
var mobileOrientation = 'landscape';
var landscapeLockMessage = 'Best viewed horizontally';


  


const enableForceRefresh = false;
const forceRefreshInterval = 60000;
const enableCloseWindowWarning = true;
const updateSessionInterval = 1800000;
const routeFadeTime = 600;
const modalFadeTime = 600;
const enableMobileLandscapeLock = true;
const loopRoutes = true;
const enableHashNavigation = true;

var currentUser =  {id: 0};
var currentRoute = enumRoutes.EXTERIOR;  // ALSO DEFAULT ROUTE ON LOAD UNLESS SENT IN GET VAR (TODO NEED TO ADD THIS BACK)

var isMobile = false;
var mobileOrientation = 'landscape';
var landscapeLockMessage = 'Best viewed horizontally';

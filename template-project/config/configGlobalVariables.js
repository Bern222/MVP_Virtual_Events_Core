
const enableForceRefresh = false;
const forceRefreshInterval = 60000;
const enableCloseWindowWarning = true;
const updateSessionInterval = 1800000;

var currentUser =  {id: 0};
var currentRoute = enumRoutes.EXTERIOR;  // ALSO DEFAULT ROUTE ON LOAD UNLESS SENT IN GET VAR (TODO NEED TO ADD THIS BACK)
var isMobile = false;
var mobileOrientation = 'landscape';
var routeFadeTime = 600;
var modalFadeTime = 600;
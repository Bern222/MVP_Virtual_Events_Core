Hunter - Simple PHP Javascript Obfuscator

******* Requirement *******
require_once 'HunterObfuscator.php'; //Include the class

******* Usage *******
##Simple usage to obfuscate JS code:

$jsCode = "alert('Hello world!');"; //Simple JS code
$hunter = new HunterObfuscator($jsCode); //Initialize with JS code in parameter
$obsfucated = $hunter->Obfuscate(); //Do obfuscate and get the obfuscated code
echo "<script>" . $obsfucated . "</script>";

##Simple usage to obfuscate HTML code:

$htmlCode = "<h1>Title</h1><p>Hello world!</p>"; //Simple HTML code
$hunter = new HunterObfuscator($htmlCode, true); //Initialize with HTML code in first parameter and set second one to TRUE
$obsfucated = $hunter->Obfuscate(); //Do obfuscate and get the obfuscated code
echo "<script>" . $obsfucated . "</script>";

Note: If your HTML code contains any JS codes please remove any comments in that js code to prevent issues.


##Set expiration time:
$hunter->setExpiration('+10 day'); //Expires after 10 days
$hunter->setExpiration('Next Friday'); //Expires next Friday
$hunter->setExpiration('tomorrow'); //Expires tomorrow
$hunter->setExpiration('+5 hours'); //Expires after 5 hours
$hunter->setExpiration('+1 week 3 days 7 hours 5 seconds'); //Expires after +1 week 3 days 7 hours and 5 seconds

##Domain name lock:
$hunter->addDomainName('google.com'); //the generated code will work only on google.com
Note: you can add multiple domains by adding one by one.

See the included demo for more.

Happy Coding :)


******* CHANGE LOG *******

v1.0 2017-07-27
#Initial release

v1.1 2017-11-20
#Some improvments
#Added expiration time
#Added domain name lock
#Added ability to obfuscate HTML code
#New demo script
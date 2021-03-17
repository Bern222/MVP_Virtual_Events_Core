var fadeTime = 1000;
var lobbyVideoPlayed = false;
var currentState = 'exterior';
var responseText = '';

$(document).ready(function () {
	$('.exterior').hide();
	
	var isMobile = false;

	if( $('body').css('display')=='none') {
		isMobile = true;       
	}
	isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

	if (isMobile == true) {
		window.addEventListener('orientationchange', doOnOrientationChange);
		doOnOrientationChange();
	}
	fadeIn('exterior');
});

function doOnOrientationChange() {
	switch(window.orientation) {  
	case -90: case 90:
		//alert('landscape');
		break; 
	default:
		//alert('portrait');
		//alert('Best viewed horizontally');
		break; 
	}
}

function onVideoEnd() {

}

function fadeIn(room) {
	fadeoutAll(currentState);
	currentState = room;
	$('.' + room).show();
	// $('.' + room).css('display', 'block');
	
	switch(room) {
		case 'exterior':
			// gaEvent(ga.category.EXTERIOR, ga.action.PAGE_CHANGE);
			$('.exterior').fadeTo(fadeTime, 1);
       		openLoginModal();
			break;
	}
}

function fadeoutAll(room) {
	$('.' + room).fadeTo(fadeTime, 0);
	setTimeout(removeDisplay(room), fadeTime + 100);
}

function removeDisplay(room) {
	$('.' + room).hide();
}

function openLoginModal() {
	setTimeout(function() {
		$.fancybox.open({
			src  : '#loginModal',
			type : 'inline',
			animationEffect: "zoom",
			animationDuration: modalFadeTime,
       buttons: "",
			opts : {
         smallBtn:false,
         modal: true,
				touch: false
				
			}
		});
	}, 3000);
}

function closeLoginModal() {
	$.fancybox.close();
}

function doLogin()
{
  var username = document.getElementById('em').value;
  var password = document.getElementById('pw').value;

  if(username == "" || password == "")
  {
    document.getElementById('errordetail').innerHTML = "You must enter both an email address and a password to log in.<br><br>";
    document.getElementById('errordetail').style = "display: block;";
    alert("No Data");
    return false;
  }

  request = $.post("modules/loginproc.php", { method: "processLogin", username: username, password: password});

  request.done(function (response, textStatus, jqXHR) {

	var found = false;
	responseText = response;
	
    if(response == "Success!")
    {
		responseText = '';
		request = $.post("modules/loginproc.php", { method: "getAllSales"});
  		request.done(function (responseAllSales, textStatusAllSales, jqXHRAllSales) {
			var allSales = JSON.parse(responseAllSales);
			
			// TODO: removed for development

			//if (allSales && allSales.length > 0) {

				//Check to see if date is 10/11/2020 or after...
				console.log('DATE CHECK:', new Date(), new Date("10/11/2020").setHours(0,0,0,0), new Date() >= new Date("10/11/2020").setHours(0,0,0,0));
				if(new Date() >= new Date("10/11/2020").setHours(0,0,0,0)) {
					found = true;
					responseText = '';
					document.getElementById('errordetail').style = "display: none;";
					logit = $.post("modules/loginproc.php", { method: "logAccess", unique_code: password});
					logit.done(function (responseByDay, textStatusByDay, jqXHRByDay) {
						window.location.replace('main.php');
					});
					return;
				}

				for (var i=0;i<allSales.length;i++) {
					request = $.post("modules/loginproc.php", { method: "getAccessByDay", salesId: allSales[i].GUID});
					request.done(function (responseByDay, textStatusByDay, jqXHRByDay) {
						var access = JSON.parse(responseByDay);

						if (access && access.length > 0) {
							var days = JSON.parse(access[0].days_of_access);
							
							for (var k=0;k<days.length;k++) {

								if (new Date().setHours(0,0,0,0) == new Date(days[k]).setHours(0,0,0,0)) {
									found = true;
									responseText = '';
									document.getElementById('errordetail').style = "display: none;";
									logit = $.post("modules/loginproc.php", { method: "logAccess", uniqueCode: password});
									logit.done(function (responseByDay, textStatusByDay, jqXHRByDay) {
										window.location.replace('main.php');
									});
									return;
								}
							}
							if (i >= allSales.length && !found){
								responseText = "Based on your registration in eShow, you do not have access to the portal today.";
								setWarningMessage();
							}
						} else {
							responseText = "Based on your registration in eShow, you do not have access to the portal today.";
						}
					});
				}
				
			// } else {
			// 	responseText = "Based on your registration in eShow, you do not have access to the portal today.";
			// 	setWarningMessage();
			// }
		});

    //   document.getElementById('errordetail').style = "display: none;";
    //   window.location.replace('main.php');
    //   return true;
    } else {
		setWarningMessage();
	}

    return false;
  });
}

function setWarningMessage() {
	if(responseText && responseText != '') {
		document.getElementById('errordetail').innerHTML = responseText + "<br><br>";
		document.getElementById('errordetail').style = "display: block; font-weight:bold; color: #ff0000;";
	}
}

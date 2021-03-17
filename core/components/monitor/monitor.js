var fadeTime = 1000;
var lobbyVideoPlayed = false;
var currentState = 'dashboard';
var monitor_refresh = 60000;

$(document).ready(function () {
	$('.dashboard').hide();

	var isMobile = false;

	if( $('body').css('display')=='none') {
		isMobile = true;       
	}
	isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

	if (isMobile == true) {
		window.addEventListener('orientationchange', doOnOrientationChange);
		doOnOrientationChange();
	}

	getActiveSessions();
	getTopLogins();
	getTopIPs();
	getDupes();

	$('#dashboard').on( "load", fadeIn('dashboard'));
});

function loadMain()
{
  window.location.replace('../main.php');
}

function checkMySession()
{
	request = $.post("monitorproc.php", {method: "checkMySession"});

	request.done(function (response, textStatus, jqXHR) {
     var reply = JSON.parse(response);

     if(reply && reply.length > 0)
     {
			if(reply.status == "Invalid!")
			{
				// Session has expired or been terminated
				window.location.replace('../index.php');
       		return;
			}
		}
	});
}

function getActiveSessions()
{
	var content = "";

	request = $.post("monitorproc.php", {method: "activeSessions"});

	request.done(function (response, textStatus, jqXHR) {
		var reply = JSON.parse(response);

     if(reply && reply.length != 0)
		{
			//Process returns here.
			if(reply.status == 'success') {
				content = "<h1>Active Sessions</h1>\n";
				content = content + "<table>\n";
				content = content + " <tr>\n";
				content = content + "  <td class=\"left\">Timeframe:</td>\n";
				content = content + "  <td class=\"center\">Start</td>\n";
				content = content + "  <td class=\"center\">Last Seen</td>\n";
				content = content + " </tr>\n";
				var ages = JSON.parse(reply.ages);
				if(ages && ages.length != 0)
				{
					for(var i=0;i<ages.length;i++)
					{
						content = content + " <tr>\n";
						content = content + "  <td class=\"left\"><nobr>Aged "+ages[i].age+" hours:</nobr></td>\n";
						content = content + "  <td class=\"center\">"+ages[i].startcount+"</td>\n";
						content = content + "  <td class=\"center\">"+ages[i].lastcount+"</td>\n";
						content = content + " </tr>\n";
					}
				}
				content = content + " <tr>\n";
				content = content + "  <td class=\"left\">Total Sessions:</td>\n";
				content = content + "  <td class=\"center\">"+reply.sessionCount+"</td>\n";
				content = content + "  <td>&nbsp;</td>\n";
				content = content + " </tr>\n";
				content = content + "</table><br><br>\n";
			} else {
				content = "				<center><h1>Error</h1></center>\
				There was an error retrieving session information<br><br>\
				" + reply.error+"<br><br>";
			}
			document.getElementById("dashboard-active-sessions").innerHTML = content;
		}
	});
}

function getTopLogins()
{
	var content = "";

	request = $.post("monitorproc.php", {method: "topLogins"});

	request.done(function (response, textStatus, jqXHR) {
		var reply = JSON.parse(response);

     if(reply && reply.length != 0)
		{
			//Process returns here.
			if(reply.status == 'success') {
				var data = JSON.parse(reply.data);

				content = "<h1>Today's Top 20 Logins</h1>\n";
				content = content + "<table>\n";
				content = content + " <tr>\n";
				content = content + "  <td>#</td>\n";
				content = content + "  <td>User Name<td>\n";
				content = content + "  <td>Logins<td>\n";
				content = content + " </tr>\n";

				for(var i=0;i < data.length && i < 20; i++)
				{
					content = content + " <tr>\n";
					content = content + "  <td>"+(i+1)+"</td>\n";
					content = content + "  <td><nobr>"+data[i].username+"</nobr><td>\n";
					content = content + "  <td>"+data[i].logincount+"<td>\n";
					content = content + " </tr>\n";
				}
				content = content + "</table><br><br>\n";
			} else {
				content = "				<center><h1>Error</h1></center>\
				There was an error retrieving Login information<br><br>\
				" + reply.error+"<br><br>";
			}
			document.getElementById("dashboard-top-logins").innerHTML = content;
		}
	});
}


function getTopIPs()
{
	var content = "";

	request = $.post("monitorproc.php", {method: "topIPs"});

	request.done(function (response, textStatus, jqXHR) {
		var reply = JSON.parse(response);

     if(reply && reply.length != 0)
		{
			//Process returns here.
			if(reply.status == 'success') {
				var data = JSON.parse(reply.data);

				content = "<h1>Today's Top 20 IP Users</h1>\n";
				content = content + "<table>\n";
				content = content + " <tr>\n";
				content = content + "  <td>#</td>\n";
				content = content + "  <td>User Name<td>\n";
				content = content + "  <td>IPs<td>\n";
				content = content + " </tr>\n";

				for(var i=0;i < data.length && i < 20; i++)
				{
					content = content + " <tr>\n";
					content = content + "  <td>"+(i+1)+"</td>\n";
					content = content + "  <td><nobr>"+data[i].username+"</nobr><td>\n";
					content = content + "  <td>"+data[i].iplist.length+"<td>\n";
					content = content + " </tr>\n";
				}
				content = content + "</table><br><br>\n";
			} else {
				content = "				<center><h1>Error</h1></center>\
				There was an error retrieving Login information<br><br>\
				" + reply.error+"<br><br>";
			}
			document.getElementById("dashboard-top-ips").innerHTML = content;
		}
	});
}

function getDupes()
{
	var content = "";

	request = $.post("monitorproc.php", {method: "dupeSessions"});

	request.done(function (response, textStatus, jqXHR) {
		var reply = JSON.parse(response);

     if(reply && reply.length != 0)
		{
			//Process returns here.
			if(reply.status == 'success') {
				if(reply.dupeCount == 0)
				{
					content = "<h1>Duplicate Sessions</h1>\n";
					content = content + reply.comment + "<br><br>\n";
				}
				else
				{	
					var data = JSON.parse(reply.duplicates);

					content = "<h1>Duplicate Sessions</h1>\n";
					content = content + "<table>\n";
					content = content + " <tr>\n";
					content = content + "  <td class=\"col1\">#</td>\n";
					content = content + "  <td class=\"col2\">Started<td>\n";
					content = content + "  <td class=\"col3\">Last<td>\n";
					content = content + " </tr>\n";
					content = content + "</table>\n";
					for(var i=0;i < data.length; i++)
					{
						content = content + "<table class=\"name\">\n";
						content = content + " <tr>\n";
						content = content + "  <td class=\"left\"><nobr><b>" + data[i].username + "</b></nobr><td>\n";
						content = content + " </tr>\n";
						content = content + "</table>\n";
						var list = JSON.parse(data[i].list);
						for(var j=0;j<list.length;j++)
						{
							content = content + "<table>\n";
							content = content + " <tr>\n";
							content = content + "  <td class=\"col1\">"+(j+1)+"</td>\n";
							content = content + "  <td class=\"col2\">"+list[j].since_start+"<td>\n";
							content = content + "  <td class=\"col3\">"+list[j].since_lastseen+"<td>\n";
							content = content + " </tr>\n";
							content = content + "</table>\n";
						}
					}
				}
				content = content + "<br><br>\n";
			} else {
				content = "				<center><h1>Duplicate Sessions</h1></center>\
				There was an error retrieving Login information<br><br>\
				" + reply.error+"<br><br>";
			}
			document.getElementById("dashboard-top-dupes").innerHTML = content;
		}
	});
}



















function doOnOrientationChange() {
	switch(window.orientation) {  
	case -90: case 90:
		//alert('landscape');
		break; 
	default:
		//alert('portrait');
		//alert('Best viewed horizontally');
		alert('Best viewed horizontally.  Please rotate your device.');
		break; 
	}
}

function logout() {
	request = $.post("modules/loginproc.php", { method: "processLogout" });

	request.done(function (response, textStatus, jqXHR) {
		window.location.replace('index.php');
	});
}

function fadeIn(room) {
	fadeoutAll(currentState);
	currentState = room;
	$('.' + room).show();
	
	switch(room) {
		case 'dashboard':
			$('.dashboard').fadeTo(fadeTime, 1);
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

var x = setInterval(function() {
	getActiveSessions();
	checkMySession();
	getTopLogins();
	getTopIPs();
	getDupes();
	if(monitor_refresh == 0) monitor_refresh = 60000;
}, monitor_refresh);


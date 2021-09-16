function getClickAction(elementObj) {
	var clickAction = '';
	if(elementObj.actionParams) {
		switch(elementObj.action) {
			case enumButtonActions.OPEN_ROUTE:
				clickAction = 'changeRoute(\'' + elementObj.actionParams + '\')';
			break;
			case enumButtonActions.OPEN_FILE:
				// TODO: add addition condition on different file types
				clickAction = 'openExternalLink(\'' + elementObj.actionParams + '\')';
			break;
			case enumButtonActions.OPEN_EXTERNAL_LINK:
				var linkName = '_blank';
				console.log('HERE:', elementObj);
				if (elementObj.actionParams) {
					if (elementObj.actionParams.name) {
						linkName = elementObj.actionParams.name;
					}
					
					if (elementObj.actionParams && dataContent[elementObj.actionParams]) {
						clickAction = 'window.open(\'' + dataContent[elementObj.actionParams].path + '\', \'' + linkName + '\')';
					}
				}
			break;
			case enumButtonActions.OPEN_MODAL_HTML:
				clickAction = 'openModalHtml(\'' + elementObj.actionParams.modalId + '\', \'' + elementObj.actionParams.html + '\')';
			break;
			case enumButtonActions.OPEN_MODAL_INLINE:
				clickAction = 'openModalInline(\'' + elementObj.actionParams + '\')';
			break;
			case enumButtonActions.OPEN_MODAL_VIDEO:
				clickAction = 'openModalVideo(\'' + dataContent[elementObj.actionParams] + '\')';
			break;
			case enumButtonActions.OPEN_MODAL_IFRAME:
				clickAction = 'openModalIframe(\'' + dataContent[elementObj.actionParams] + '\')';
			break;
			case enumButtonActions.LOGOUT:
				clickAction = 'login()';
			break;
		}
	}

	

	return clickAction;
}


// ACTION METHODS -------------------------------------
function openExternalLink(url, linkName = '_blank') {
    window.open(url, linkName);
	logEvent(currentRoute, enumButtonActions.OPEN_EXTERNAL_LINK + ' - ' + url);
}

function openModalInline(modalId, customDelayTime = 0) {
	logEvent(currentRoute, enumButtonActions.OPEN_MODAL_INLINE + ' - ' + modalId);

	// TODO FIGURE OUT WHAT HAPPENED HERE
	// defaultModalOpts.afterClose = function () {
    //     onModalClose(modalId);
    // };

    setTimeout(function() {
		$.fancybox.open({
			src  : '#' + modalId,
			type : 'inline',
			animationDuration: configSiteSettings.modalFadeTime,
			opts : configSiteSettings.defaultModalOpts
		});
	}, customDelayTime);
}

function openModalHtml(modalId, htmlContent, customDelayTime = 0) {
	logEvent(currentRoute, enumButtonActions.OPEN_MODAL_HTML + ' - ' + modalId);
	$('#' + modalId + ' .modal-html-content').html(htmlContent);
	openModalInline(modalId, customDelayTime)
}

function openModalVideo(videoUrl, customDelayTime = 1) {
	clearTimeout(currentAutoPlayVideoTimeout);
	logEvent(currentRoute, enumButtonActions.OPEN_MODAL_VIDEO + ' - ' + videoUrl);
	currentAutoPlayVideoTimeout = setTimeout(function() {
        $.fancybox.open({
            src  : videoUrl,
            type : 'video',
            opts : {
                animationDuration: configSiteSettings.modalFadeTime,
                afterShow : function( instance, current ) {
                    var video = $('.fancybox-video')[0];

                    video.onended = function(e) {
						videoOnEndedCallback(videoUrl);
                    };
                },
                beforeClose: function( instance, current ) {
                    videoBeforeCloseCallback(videoUrl);
                }

            }
        });
    }, customDelayTime);
}

function openModalIframe(iframeUrl) {
	logEvent(currentRoute, enumButtonActions.OPEN_MODAL_IFRAME + ' - ' + iframeUrl);
    var openAsNewTab = /MSIE|Trident|Edge|iPhone|iPad|iPod|Android/i.test(navigator.userAgent);  //  Note added IE to this as it doesn't open PDF's in iframe well in fancy box
    if(openAsNewTab) {
        // Open in new tab
        window.open(iframeUrl, "_blank"); 
    }
    else {
        // Or open in iframe
        setTimeout(function() {
            $.fancybox.open({
                src  : iframeUrl,
                type : 'iframe',
                animationDuration: configSiteSettings.modalFadeTime
            });
        }, 100);
    }
}

function openChatRoom(routeId, zoomUrl = '') {
    gaEvent(routeId, enumEvents.action.OPEN_CHAT_ROOM);

    // Add for Private Chat
    $("#userChatButton").hide();    
    openChat(routeId);
    
}

function openProfile() {
	if(currentUser && currentUser.show_notifications) {
		$("#showNotificationCheckbox").prop('checked', true);
	} else {
		$("#showNotificationCheckbox").prop('checked', false);
	}

	setTimeout(function() {
		$.fancybox.open({
			src  : '#profileModal',
			type : 'inline',
			animationDuration: configSiteSettings.modalFadeTime,
			opts : {
				beforeClose : function( instance, current ) {
					// toggleUpdatePassword();
					$('#pwem').val("");
					$('#pwemrepeat').val("")
				},
				touch: false
			}
		});
	}, 100);
}

function openShare() {
	setTimeout(function() {
		$.fancybox.open({
			src  : '#shareModal',
			type : 'inline',
			animationDuration: configSiteSettings.modalFadeTime,
			opts : {
				touch: false
			}
		});
	}, 100);
}

function playVideo(routeId) {
	$('#' + id)[0].play();
}

function logout() {
	request = $.post("modules/loginproc.php", { method: "processLogout" });

	request.done(function (response, textStatus, jqXHR) {
		window.location.replace('index.php');
	});
}


function flyIntoLobby() {
	$('.exterior-fly-in-video-player-container').fadeTo(1, 1);

	$('#exteriorFlyInVideoPlayer').get(0).load();
	$('#exteriorFlyInVideoPlayer').get(0).play();
	setTimeout(function() { 
		fadeIn('lobby');
		$('.exterior-fly-in-video-player-container').fadeTo(1, 0);
	},4000);
}

function getClickAction(buttonObj) {
	var clickAction = '';
	switch(buttonObj.action) {
		case enumButtonActions.OPEN_ROUTE:
			clickAction = 'changeRoute(\'' + buttonObj.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_FILE:
			// TODO: add addition condition on different file types
			clickAction = 'window.open(\'' + buttonObj.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_EXTERNAL_LINK:
			var linkName = '_blank';
			if (buttonObj.actionParams) {
				if (buttonObj.actionParams.name) {
					linkName = buttonObj.actionParams.name;
				}
				clickAction = 'window.open(\'' + buttonObj.actionParams.url + '\', \'' + linkName + '\')';
			}
		break;
		case enumButtonActions.OPEN_MODAL_HTML:
			clickAction = 'openModalHtml(\'' + buttonObj.actionParams.modalId + '\', \'' + buttonObj.actionParams.html + '\')';
		break;
		case enumButtonActions.OPEN_MODAL_INLINE:
			clickAction = 'openModalInline(\'' + buttonObj.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_MODAL_VIDEO:
			clickAction = 'openModalVideo(\'' + buttonObj.actionParams + '\')';
		break;
		case enumButtonActions.OPEN_MODAL_IFRAME:
			clickAction = 'openModalIframe(\'' + buttonObj.actionParams + '\')';
		break;
		case enumButtonActions.LOGOUT:
			clickAction = 'login()';
		break;
	}
	return clickAction;
}


// ACTION METHODS -------------------------------------
function openExternalLink(url) {
    window.open(url);
}

function openModalInline(modalId, customDelayTime = 0) {
    setTimeout(function() {
		$.fancybox.open({
			src  : '#' + modalId,
			type : 'inline',
			animationEffect: "zoom",
			animationDuration: modalFadeTime,
			opts : {
				touch: false
				
			}
		});
	}, customDelayTime);
}

function openModalHtml(modalId, htmlContent, customDelayTime = 0) {
	$('#' + modalId + ' .modal-html-content').html(htmlContent);
	openModalInline(modalId, customDelayTime)
}

function openModalVideo(videoUrl, customDelayTime = 1) {
	clearTimeout(currentAutoPlayVideoTimeout);
	currentAutoPlayVideoTimeout = setTimeout(function() {
        $.fancybox.open({
            src  : videoUrl,
            type : 'video',
            opts : {
                animationEffect: "zoom",
                animationDuration: modalFadeTime,
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
                animationEffect: "zoom",
                animationDuration: modalFadeTime
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
			animationEffect: "zoom",
			animationDuration: modalFadeTime,
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
			animationEffect: "zoom",
			animationDuration: modalFadeTime,
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

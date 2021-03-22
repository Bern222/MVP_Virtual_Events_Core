function openPDF(url) {
    window.open(url);
}

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

function openModalVideo(videoUrl, onEndedCallback, beforeCloseCallback) {
    setTimeout(function() {
        $.fancybox.open({
            src  : videoUrl,
            type : 'video',
            opts : {
                animationEffect: "zoom",
                animationDuration: modalFadeTime,
                afterShow : function( instance, current ) {
                    console.info( 'video afterShow' );
                    var video = document.getElementsByTagName('video')[0];

                    video.onended = function(e) {
                        console.info( 'Video onended' );
                        $.fancybox.close();
                        setTimeout(function() {
                            // TODO revisit to include args
                            if (onEndedCallback) {
                                onEndedCallback();
                            }
                        },100);
                    };
                },
                beforeClose: function( instance, current ) {
                    if (beforeCloseCallback) {
                        setTimeout(function() {
                            console.info( 'Video beforeClose' );
                            // code if needed
                        },100);
                    }
                }

            }
        });
    },100);
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

function logout() {
	request = $.post("modules/loginproc.php", { method: "processLogout" });

	request.done(function (response, textStatus, jqXHR) {
		window.location.replace('index.php');
	});
}

// TODO: Revisit Bypass for testing
// function KeyPress(e) {
// 	var evtobj = window.event? event : e
// 	// check for CTRL + b
// 	if (evtobj.keyCode == 66 && evtobj.ctrlKey) { 
// 		bypassTimeRestrictions = true;
// 		if (isNetworkingBlock) {
// 			isNetworkingBlock = false;
// 			$.fancybox.close();
// 			openSelectionModal('networkingModal');
// 		}
// 		if(isExhibitHallBlock) {
// 			isExhibitHallBlock = false;
// 			$.fancybox.close();
// 			fadeIn('exhibit-hall');
// 		}
// 	}
// }
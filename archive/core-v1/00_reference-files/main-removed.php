<!-- Main Rooms ----------------------------------------------------------- -->
<div id="exterior" class="page-landscape-fullscreen exterior">
        <div class="relative-container">
            <!-- <div id="exteriorVideoContainer" class="exterior-video-container">
                <div class="exterior-fly-in-video-player-container" onclick="flyIntoLobby();" style="position: absolute;width: 100%;top:0px;max-height: 100%;background-position: top;background-repeat: no-repeat; background-size: contain;">
                    <div id="videoBkgFlyToContainer" class="full-background" >
                        <video id="exteriorFlyInVideoPlayer" class="full-background" playsinline>
                            <source src="https://d1vxcp6kmz704x.cloudfront.net/beyadigital/backgrounds-video/fly-in-v2.mp4" type="video/mp4" />
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                <div class="full-background" onclick="flyIntoLobby();">
                    <video id="exteriorVideoPlayer" class="full-background" loop playsinline>
                        <source src="https://d1vxcp6kmz704x.cloudfront.net/beyadigital/backgrounds-video/exterior-v2.mp4" type="video/mp4" />
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="welcome-overlay" onclick="startExteriorVideo()">
                    <div>
                        <div class="welcome-text">Welcome</div><br>
                        <div class="welcome-click-text">Click to Enter</div>
                    </div>
                </div>
            </div> -->
        
            <img id="exteriorImageBackground" class="full-background"/>
        </div>
    </div>

    <div id="lobby" class="page-landscape-fullscreen lobby">
		<div class="relative-container">
			<!-- <div class="full-background">
				<video id="lobbyVideoBackground" class="full-background" autoplay loop muted playsinline>
				  <source src="https://d1vxcp6kmz704x.cloudfront.net/beyadigital/backgrounds-video/lobby_v4.mp4" type="video/mp4" />
				  Your browser does not support the video tag.
				</video>
            </div> -->
            <img id="lobbyImageBackground" class="full-background"/>
        </div>
    </div>

    <div id="auditorium" class="page-landscape-fullscreen">
        <div class="relative-container">
            <img id="auditoriumImageBackground" class="full-background"/>
            <!-- TODO: Need to make dynamic -->
            <div class="black-button back-button" onclick="changeRoute('lobby');">Back</div>
        </div>
    </div>

    <div id="exhibitHall" class="page-landscape-fullscreen">
        <div class="relative-container">
            <img id="exhibitHallImageBackground" class="full-background"/>
            <!-- TODO: create container for multiple buttons -->
            <div class="black-button back-button" onclick="changeRoute('lobby');">Back</div>
        </div>
    </div>

    <div id="seminarRooms" class="page-landscape-fullscreen">
        <div class="relative-container">
            <img id="seminarRoomsImageBackground" class="full-background"/>
            <!-- TODO: create container for multiple buttons -->
            <div class="black-button back-button" onclick="changeRoute('lobby');">Back</div>
            <div class="black-button first-under-back-button" onclick="openModalInline('seminarsModal', 1000)">Event Menu</div>
        </div>
    </div>


    <div id="networkingLounge" class="page-landscape-fullscreen">
        <div class="relative-container">
            <img id="seminarRoomsImageBackground" class="full-background"/>
            <div class="black-button back-button" onclick="changeRoute('lobby');">Back</div>
            <div class="black-button first-under-back-button" onclick="openSelectionModal('networkingLoungeModal');">Lounges</div>
        </div>
    </div>

    <div id="informationDesk" class="page-landscape-fullscreen">
        <div class="relative-container">
            <img id="informationDeskImageBackground" class="full-background"/>
            <div class="black-button back-button" onclick="changeRoute('lobby');">Back</div>
		</div> 
    </div>

<!-- Exhibit Hall Booths ----------------------------------------------------------- -->

    <div id="exhibit1" class="page-landscape-fullscreen exhibit1">
        <div class="relative-container">
            <img class="full-background" src="../core/images/backgrounds/exhibit-1.jpg"/>
            <div class="black-button back-button" onclick="changeRoute('exhibit1');">Back</div>
        </div>
    </div>

    <div id="exhibit2" class="page-landscape-fullscreen exhibit2">
        <div class="relative-container">
            <img class="full-background" src="../core/images/backgrounds/exhibit-2.jpg"/>
            <div class="black-button back-button" onclick="changeRoute('exhibit2');">Back</div>
        </div>
    </div>

    <div id="exhibit3" class="page-landscape-fullscreen exhibit3">
        <div class="relative-container">
            <img class="full-background" src="../core/images/backgrounds/exhibit-3.jpg"/>
            <div class="black-button back-button" onclick="changeRoute('exhibit3');">Back</div>
        </div>
    </div>

<!-- Seminar Rooms ----------------------------------------------------------- -->

    <div id="seminar1" class="page-landscape-fullscreen seminar1">
        <div class="relative-container">
            <img class="full-background" src="../core/images/backgrounds/seminar-1.jpg"/>
            <div class="black-button back-button" onclick="changeRoute('seminarRooms');">Back</div>
        </div>
    </div>

    <div id="seminar2" class="page-landscape-fullscreen seminar2">
        <div class="relative-container">
            <img class="full-background" src="../core/images/backgrounds/seminar-2.jpg"/>
            <div class="black-button back-button" onclick="changeRoute('seminarRooms');">Back</div>
        </div>
    </div>

    <div id="seminar3" class="page-landscape-fullscreen seminar3">
        <div class="relative-container">
            <img class="full-background" src="../core/images/backgrounds/seminar-3.jpg"/>
            <div class="black-button back-button" onclick="changeRoute('seminarRooms');">Back</div>
        </div>
    </div>

<!-- Networking Lounges ----------------------------------------------------------- -->
    <div id="networkingLounge1" class="page-landscape-fullscreen booze-allen-networking-lounge">
        <div class="relative-container">
            <div class="black-button back-button" onclick="changeRoute('networkingLounge');">Back</div>
            <div class="black-button first-under-back-button" onclick="openChatRoom('networkingLounge1')">Chat Room</div>
        </div>
    </div>

    <div id="networkingLounge1" class="page-landscape-fullscreen booze-allen-networking-lounge">
        <div class="relative-container">
            <div class="black-button back-button" onclick="changeRoute('networkingLounge');">Back</div>
            <div class="black-button first-under-back-button" onclick="openChatRoom('networkingLounge2')">Chat Room</div>
        </div>
    </div>

    <div id="networkingLounge1" class="page-landscape-fullscreen booze-allen-networking-lounge">
        <div class="relative-container">
            <div class="black-button back-button" onclick="changeRoute('networkingLounge');">Back</div>
            <div class="black-button first-under-back-button" onclick="openChatRoom('networkingLounge3')">Chat Room</div>
        </div>
    </div>
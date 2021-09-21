function createCustomAgenda() {

    var agendaHtml = `
        <div class="agenda-header agenda-box">
            <h1>2021 ADR Conference...TITLE</h1>
            <div id="agendaDate8" class="agenda-date-button agenda-description agenda-date-button-active" onclick="toggleAgenda('8')">Saturday, October 8th, 2021</div>
            <div id="agendaDate9" class="agenda-date-button agenda-description" onclick="toggleAgenda('9')">Saturday, October 9th, 2021</div>
        </div>
        <div id="agendaContainer8"  class="agenda-sessions-container-8">
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
        </div>
        <div id="agendaContainer9" class="agenda-sessions-container">
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
            <div class="agenda-row agenda-box" onclick="openSession()">
                <div class="agenda-time-logo">
                    <h3>8:30 a.m. - 9:00 a.m.<h3>
                    <img src="../core-config/config-data/adr/content/images/zoom.jpg"/>
                </div>
                <div class="agenda-information">
                    <h3>Session Title - ....</h3>
                    <br>
                    <div class="agenda-description">Session Info ...</div>
                    <div class="agenda-description">Session Speakers ...</div>
                    <br>
                    <div class="agenda-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris </div>
                    <div class="agenda-link"></div>
                </div>
            </div>
        </div>
    `;

    // Agenda Modal Id - b6865f4d-c3ee-41c0-8ccb-0e428e0e2c27

    $('#b6865f4d-c3ee-41c0-8ccb-0e428e0e2c27').empty();
    $('#b6865f4d-c3ee-41c0-8ccb-0e428e0e2c27').html(agendaHtml);
}

function toggleAgenda(type) {
    hideAgendas();
    switch(type) {
        case '8':
            $('#agendaContainer8').show();
            $('#agendaContainer8').fadeTo(500,1);
            $('#agendaDate8').addClass('agenda-date-button-active');
            $('#agendaDate9').removeClass('agenda-date-button-active');
        break;
        case '9':
            $('#agendaContainer9').show();
            $('#agendaContainer9').fadeTo(500,1);
            $('#agendaDate8').removeClass('agenda-date-button-active');
            $('#agendaDate9').addClass('agenda-date-button-active');
        break;
    }
}

function hideAgendas() {
    $('#agendaContainer8').fadeTo(0,0);
    $('#agendaContainer8').hide();
    $('#agendaContainer9').fadeTo(0,0);
    $('#agendaContainer9').hide();
}
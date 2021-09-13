<html>
    <head>
        <title>MVP - Site Configurator</title>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
        <script  src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
        <!-- Fancy Box -->
        <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>  
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

        <!-- Moment -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone.min.js"></script>  

        <!-- Table Sorter -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js" integrity="sha512-dj/9K5GRIEZu+Igm9tC16XPOTz0RdPk9FGxfZxShWf65JJNU2TjbElGjuOo3EhwAJRPhJxwEJ5b+/Ouo+VqZdQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.default.min.css" integrity="sha512-wghhOJkjQX0Lh3NSWvNKeZ0ZpNn+SPVXX1Qyc9OCaogADktxrBiBdKGDoqVUOyhStvMBmJQ8ZdMHiR3wuEq8+w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Font Awesome (used for icons, may revisit) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


        <!-- PROJECT IMPORTS -------------------------------------------------------------------- -->

        <!-- CORE ENUM IMPORTS ------------------------------------------------------------------ -->
        <script src="../../core/enums/enumButtonActions.js?<?php echo rand();?>"></script>
        <script src="../../core/enums/enumModalTypes.js?<?php echo rand();?>"></script>
        <script src="../../core/enums/enumContentTypes.js?<?php echo rand();?>"></script>


        <!-- CONFIG ENUM IMPORTS ------------------------------------------------------------------ -->
        <script src="../enums/enumRoutes.js?<?php echo rand();?>"></script>  
        <script src="../enums/enumModals.js?<?php echo rand();?>"></script>  
        <script src="../enums/enumEvents.js?<?php echo rand();?>"></script>  

        <!-- CORE DATA IMPORTS ------------------------------------------------------------------ -->
        <script src="../data/dataContent.js?<?php echo rand();?>"></script>

        <!-- CORE CONFIG IMPORTS ------------------------------------------------------------------ -->
        <script src="../config/configSiteSettings.js?<?php echo rand();?>"></script>  
        <script src="../config/configMainMenu.js?<?php echo rand();?>"></script>  
        <script src="../config/configRoutes.js?<?php echo rand();?>"></script> 
        <script src="../config/configModals.js?<?php echo rand();?>"></script> 
        <script src="../config/configGlobalVariables.js?<?php echo rand();?>"></script>


        <!-- CONFIGURATOR STYLE/SCRIPT -->
        <link rel="stylesheet" href="css/element.css">
        <link rel="stylesheet" href="css/input.css">
        <link rel="stylesheet" href="css/list.css">
        <link rel="stylesheet" href="css/menu.css">
        <link rel="stylesheet" href="css/icons.css" />
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/form.css">

        <script src="js/enums.js" crossorigin="anonymous"></script>
        <script src="js/util.js" crossorigin="anonymous"></script>
        <script src="js/element.js" crossorigin="anonymous"></script>
        <script src="js/main.js" crossorigin="anonymous"></script>
        <script src="js/siteSettings.js" crossorigin="anonymous"></script>
        <script src="js/configuratorMainMenu.js" crossorigin="anonymous"></script>
        <script src="js/configuratorContent.js" crossorigin="anonymous"></script>

        <script src="js/configuratorRoute.js" crossorigin="anonymous"></script>
        <script src="js/configuratorModal.js" crossorigin="anonymous"></script>
        <script src="js/actionSelect.js" crossorigin="anonymous"></script>

        <script src="js/init.js" crossorigin="anonymous"></script>

    </head>
    <body>
        <!-- MENUS -->
        <nav role="navigation">
            <div id="menuToggle">
               
                <input type="checkbox" />
                
                <span></span>
                <span></span>
                <span></span>
              
                <ul id="menu">
                    <a href="#" onclick="changeRoute('routeSiteSettings')"><li>Site Settings</li></a>
                    <a href="#" onclick="changeRoute('routeContentLibrary')"><li>Content Library</li></a>
                    <a href="#" onclick="changeRoute('routeMainMenu')"><li>Main Menu</li></a>
                    <a href="#" onclick="changeRoute('routeRoutes')"><li>Pages</li></a>
                    <a href="#" onclick="changeRoute('routeModals')"><li>Modals</li></a>
                    <a href="#"><li>Info</li></a>
                    <a href="#" onclick="publishConfigs()"><li><div id="publishButton" class="default-button green-button disabled-button" style="opacity: 1;">Publish Changes</div></li></a>
                </ul>
            </div>
        </nav>

        <div class="save-menu" onclick="saveSite()">Publish Changes</div>


        <!-- ROUTES -->
        <div id="routeLanding" class="configurator-route configurator-route-landing">
            <!-- Page that shows status of all files and core setup -->
            <div class="configurator-landing-container">
                <div class="configurator-landing-logo">
                    <img src="images/logo_mvp_yellow.png"/>
                    <div class="configurator-landing-status-container"></div>
                </div>
            </div>
        </div>

        <div id="routeSiteSettings" class="configurator-route configurator-route-site-settings">
            <div class="center-container">
                <div class="list-header">
                    <h1 class="configurator-list-header">Site Settings</h1>
                    <!-- <div class="plus-button" onclick="openModal('addMainMenuItem')"></div> -->
                </div>
                <div class="divider"></div>
                <div class="form-container">
                    <table class="input-table-row">
                        <tr>
                            <td class="input-header">Site Title</td>
                            <td>
                                <input id="inputSiteSettingsTitle" class="default-input-text" type="text"/>
                                <span class="small-header">(Text in browser tab)</span>
                            </td>
                        </tr>
                        <tr>
                            
                            <td class="input-header">Starting Page</td>
                            <td>
                                <select id="inputSiteSettingsStartingPage" class="select-default select-route">
                                    <!-- All select-routes are dynamically filled at load -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="input-header">Page Default Fade Time</td>
                            <td>
                                <input id="inputSiteSettingsRouteFadeTime" class="default-input-text" type="text"/>
                                <span class="small-header">(Milliseconds)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="input-header">Modal Default Fade Time</td>
                            <td>
                                <input id="inputSiteSettingsModalFadeTime" class="default-input-text" type="text"/>
                                <span class="small-header">(Milliseconds)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="input-header">Enable Landscape Lock</td>
                            <td>
                                <label class="switch">
                                    <input id="inputSiteSettingsLandscapeLock" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="input-header">Landscape Lock Message</td>
                            <td><textarea id="inputSiteSettingsLandscapeMessage" class="default-input-text" rows="5"></textarea></td>
                        </tr>
                        <tr>
                            <td class="input-header">Enable Event Logging</td>
                            <td>
                                <label class="switch">
                                    <input id="inputSiteSettingsEventLogging" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="input-header">Enable Force Refresh</td>
                            <td>
                                <label class="switch">
                                    <input id="inputSiteSettingsForceRefresh" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="input-header">Force Refresh Interval</td>
                            <td><input id="inputSiteSettingsForceRefreshInterval" class="default-input-text" type="text"/></td>
                        </tr>
                        <tr>
                            <td class="input-header">Enable Close Window Warning</td>
                            <td>
                                <label class="switch">
                                    <input id="inputSiteSettingsCloseWindowWarning" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="input-header">Close Window Warning Message    </td>
                            <td>
                                <textarea id="inputSiteSettingsCloseWindowMessage" class="default-input-text" rows="5"></textarea> 
                                <span class="small-header">(Not available on most browsers)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="input-header">Enable Hash Navigation</td>
                            <td>
                                <label  class="switch">
                                    <input id="inputSiteSettingsHashNavigation" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="input-header">Google Analytics</td>
                            <td><input id="inputSiteSettingsGA" class="default-input-text" type="text"/></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="configurator-route-menu-header">
                                    <div id="buttonSaveMainMenuItem" class="default-button green-button" onclick="saveSiteSettings()">Save</div>
                                    <!-- <div id="buttonCancelMainMenuItem" class="default-button red-button" onclick="cancelMainMenuItemChanges()">Cancel</div> -->
                                </div>
                            </td>

                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div id="routeContentLibrary" class="configurator-route configurator-route-content-library">
            <div class="center-container">
                <div class="list-header">
                    <h1 class="configurator-list-header">Content Library</h1>
                    <div class="plus-button" onclick="openModal('addContent')"></div>
                </div>
                <div class="divider"></div>
                <div class="configurator-content-library-container">
                    <table id="contentLibraryTable" class="tablesorter content-library-table">
                        <thead>
                            <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Location</th>
                            </tr>
                        </thead>
                        <tbody id="contentLibraryTableBody" class="content-library-table-body">
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>

        <div id="routeMainMenu" class="configurator-route configurator-menu-list">
            <div class="center-container">
                <div class="list-header">
                    <h1 class="configurator-list-header">Main Menu</h1>
                    <div class="plus-button" onclick="openModal('addMainMenuItem')"></div>
                </div>
                <div class="divider"></div>
                <div class="configurator-list-main-menu-container">
                    
                </div>
            </div>
        </div>

        <div id="routeRoutes" class="configurator-route configurator-menu-list">
            <div class="center-container">
                <div class="list-header">
                    <h1 class="configurator-list-header">Pages</h1>
                    <div class="plus-button" onclick="openModal('addRoute')"></div>
                </div>
                <div class="divider"></div>
                <div class="configurator-list-route-container">
                    
                </div>
            </div>
        </div>

        <div id="routeModals" class="configurator-route configurator-menu-list">
            <div class="center-container">
                <div class="list-header">
                    <h1 class="configurator-list-header">Modals</h1>
                    <div class="plus-button" onclick="openModal('addModalModal')"></div>
                </div>
                <div class="divider"></div>
                <div class="configurator-list-modal-container">
                    
                </div>
            </div>
        </div>

        <div id="routeRouteConfigurator" class="configurator-route configurator-route-route">
            <div class="configurator-container">
                
                <div id="configuratorStage" class="configurator-stage">
                    <img id="backgroundImage" class="configurator-full-background"/>
                    <div id="elementContainer" class="configurator-element-container"></div>
                </div>


                <div class="configurator-route-menu">
                    <div class="configurator-route-menu-header">
                        <div id="buttonSaveRoute" class="default-button disabled-button" onclick="saveRoute()">Save Page</div>
                        <div id="buttonDiscardRoute" class="default-button red-button" onclick="discardRouteChanges()">Discard Changes</div>
                    </div>

                    <div class="configurator-section">
                        <div class="list-header">
                            <h2 class="configurator-list-header">Page Properties</h2>
                        </div>
                    
                        <div class="divider"></div>


                        <div class="small-header">Page Name</div>
                        <input id="inputRouteName" class="input-header-default" onkeypress="clsAlphaNoOnly(event)" onpaste="return false;"></input>
                        
                        <!-- <div class="divider"></div> -->
                        
                        <div class="align-center">
                            <div class="small-header right-space">Background</div>
                            <div class="plus-button" onclick="openModal(enumsConfigurator.SELECT_CONTENT, enumContentTypes.BACKGROUND)"></div>
                            <!-- <div id="backgroundTitleButton" class="default-button green-button" onclick="openSelectContentModal(enumContentTypes.BACKGROUND)">Select</div> -->
                        </div>
                        <div id="configuratorBackgroundTitle" class="indent"></div>
                        <div id="configuratorBackgroundPath" class="indent"></div>
                        
                        <div class="configurator-route-config">

                        </div>

                    </div>

                    <div class="configurator-section">
                        <div class="list-header">
                            <h2 class="configurator-list-header">Item Properties</h2>
                            <div class="plus-button" onclick="addElement()"></div>
                        </div>
                    
                        <div class="divider"></div>
                        
                        <div id="configruatorNoElementText" class="no-content-text">No Item Selected</div>
                        <div id="configruatorElementConfigContainer" class="configurator-element-config">
                            <div class="small-header">Item Name</div>
                            <input id="inputElementName" class="input-header-default" onkeypress="clsAlphaNoOnly(event)" onpaste="return false;"></input>
                            <div class="small-header">Icon</div>
                            <select id="inputElementIcon" class="select-default" onchange="updateCurrentRouteConfiguration(enumsConfigurator.ELEMENT_ICON, this.value)">
                                <option value="none">None</option>
                                <option value="icon-arrow">Arrow</option>
                                <option value="icon-dot">Dot</option>
                            </select>
                            <div class="rotate-slider">
                                <div class="small-header">Rotate Item</div>
                                <div id="rotateSlider"></div>
                            </div>
                            <div class="small-header">Action</div>
                            <select id="selectElementAction" class="select-default select-element-action" onchange="updateActionParamInput(currentElement, this.value, 'routes')"></select>
                            <div class="small-header">Action Params</div>
                            <div class="action-params-container"></div>
                            <div id="cssText" class="css-text"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



<!-- MODALS ------------------------------------------------------------------------->
        <div id="mainMenuItemModal" class="configurator-default-modal configurator-main-menu-item-modal">
            <div class="list-header">
                <h1 id="mainMenuItemModalHeader" class="configurator-list-header">Edit Menu Item</h1>
            </div>
            <div class="divider"></div>
            <table class="input-table-row">
                <tr>
                    <td class="input-header">Display Text:</td>
                    <td><input id="inputMainMenuDisplayText" class="default-input-text" type="text"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="small-header">Action</div>
                        <select id="selectMainMenuAction" class="select-default select-element-action" onchange="updateActionParamInput(currentMainMenuItem, this.value, 'mainMenu')"></select>
                        <div class="small-header">Action Params</div>
                        <div class="action-params-container"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="configurator-route-menu-header">
                            <div id="buttonSaveMainMenuItem" class="default-button green-button" onclick="saveMainMenuItem()">Save</div>
                            <div id="buttonCancelMainMenuItem" class="default-button red-button" onclick="cancelMainMenuItemChanges()">Cancel</div>
                        </div>
                    </td>

                </tr>
            </table>
        </div>

        <div id="selectContentModal" class="configurator-default-modal configurator-select-content-modal">
            <div class="list-header">
                <h1 class="configurator-list-header">Select Content</h1>
                <!-- <div class="plus-button" onclick="openModal('addContent')"></div> -->
            </div>
            <div class="divider"></div>
            <div class="configurator-select-content-container">
                <table id="contentLibraryTable" class="tablesorter content-library-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody class="content-library-table-body">
                    </tbody>
                </table> 
            </div>
            <div class="center">
                <div class="default-button gray-button" onclick="$.fancybox.close();">Cancel</div>
                <!-- <div id="buttonSaveContent" class="default-button green-button" onclick="()">Select</div> -->
            </div>
        </div>

        <div id="addContentModal" class="configurator-default-modal">
            <div class="list-header">
                <h1 id="mainMenuItemModalHeader" class="configurator-list-header">Add Content Item</h1>
            </div>
            <div class="divider"></div>
            <table class="input-table-row">
                <tr>
                    <td class="input-header">Name:</td>
                    <td><input id="inputAddContentTitle" class="default-input-text" type="text"/></td>
                </tr>
                <tr>
                    <td class="input-header">Type:</td>
                    <td><select id="inputAddContentType" class="input-content-types" onchange="updateContentInputContainer(this.value)"></select></td>
                </tr>
                <tr id="uploadContainer">
                    <td colspan="2">
                        <div style="margin: 20px 0px; color: black;">
                            <input type="file" id="file" name="file" />
                            <input type="button" class="button" value="Upload"
                                    id="but_upload">
                        </div>
                    </td>
                </tr>
                <tr id="pathInputContainer">
                    <td class="input-header">Path:</td>
                    <td><input id="inputAddContentPath" class="default-input-text" type="text"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="center">
                            <div class="center"><div class="default-button green-button" onclick="saveContent('add')">Save</div></div>
                            <div class="center"><div class="default-button red-button" onclick="$.fancybox.close();">Cancel</div></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div id="editContentModal" class="configurator-default-modal">
            <div class="list-header">
                <h1 id="mainMenuItemModalHeader" class="configurator-list-header">Edit Content Item</h1>
            </div>
            <div class="divider"></div>
            <input id="inputContentKey" type="hidden"/>
            <table class="input-table-row">
                <tr>
                    <td class="input-header">Name:</td>
                    <td><input id="inputEditContentTitle" class="default-input-text" type="text"/></td>
                </tr>
                <tr>
                    <td class="input-header">Type:</td>
                    <td><select id="inputEditContentType" class="input-content-types"></select></td>
                </tr>
                <tr id="inputContentPathContianer">
                    <td class="input-header">Path:</td>
                    <td><input id="inputEditContentPath" class="default-input-text" type="text"/></td>
                </tr>

            </table>
            <div class="center">
                <div class="default-button red-button" onclick="$.fancybox.close();">Cancel</div>
                <div id="buttonSaveContent" class="default-button green-button" onclick="saveContent('edit')">Save</div>
            </div>
        </div>

        <div id="deleteContentModal" class="configurator-default-modal">
            <h3>Are you sure you want to delete this content?</h3>
            <div class="center">
                <div class="center"><div class="default-button" onclick="$.fancybox.close();">Cancel</div></div>
                <div class="center"><div class="default-button red-button" onclick="deleteContent()">Delete</div></div>
            </div>
        </div>

        <div id="addRouteModal" class="configurator-default-modal">
            <h1>Add Page</h1>
            <div class="input-table">
                <div class="input-table-row">Page Name: <input id="inputAddRouteName" type="text" onkeypress="clsAlphaNoOnly(event)" onpaste="return false;"/></div>
                <div class="center"></div>
                <div class="center">
                    <div class="default-button red-button" onclick="$.fancybox.close();">Cancel</div>
                    <div class="default-button green-button" onclick="addRoute()">Save</div>
                </div>
            </div>
        </div>

        <div id="addModalModal" class="configurator-default-modal">
            <h1>Add Modal</h1>
            <div class="input-table">
                <div class="input-table-row">Modal Name: <input id="inputAddModalName" type="text" onkeypress="clsAlphaNoOnly(event)" onpaste="return false;"/></div>
                <div class="input-table-row">Modal Type: 
                    <select id="inputModalType" class="select-default input-modal-types">
                        <!-- All select-routes are dynamically filled at load -->
                    </select>
                </div>
                <div class="center"></div>
                <div class="center">
                    <div class="default-button red-button" onclick="$.fancybox.close();">Cancel</div>
                    <div class="default-button green-button" onclick="addModal()">Save</div>
                </div>
            </div>
        </div>

        <div id="editModalModal" class="configurator-default-modal">
            <h1>Edit Modal</h1>
            <div class="input-table">
                <div class="input-table-row">Modal Name: <input id="inputEditModalName" type="text" onkeypress="clsAlphaNoOnly(event)" onpaste="return false;"/></div>
                <div class="input-table-row">Modal Type: 
                    <select id="inputEditModalType" class="select-default input-modal-types">
                        <!-- All select-routes are dynamically filled at load -->
                    </select>
                </div>
                <div id="editModalItemContainer" class="center">


                </div>
                <div class="center">
                    <div class="default-button red-button" onclick="$.fancybox.close();">Cancel</div>
                    <div class="default-button green-button" onclick="saveModal()">Save</div>
                </div>
            </div>
        </div>

        <div id="alertModal" class="configurator-default-modal">
            <h3 id="alertModalText"></h3>
            <!-- <div id="alertSuccessSVG">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                    <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                    <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                </svg>
            </div> -->
            <div class="center"><div class="default-button" onclick="$.fancybox.close();">Close</div></div>
        </div>
    </body>
</html>
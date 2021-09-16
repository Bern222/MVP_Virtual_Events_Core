var currentModalIndex;
var currentModalConfig;

function loadModalConfiguration() {
    for(var i=0;i<configModals.length;i++) {
        if (configModals[i].id == currentConfigurationModalId) {
            currentModalIndex = i;
            currentModalConfig = configModals[i];
        }
    }

    console.log('CURRENT MODAL CONFIG:', currentModalConfig);

    // $('#configruatorNoElementText').show();
    // $('#configruatorElementConfigContainer').hide();
    // $('#backgroundImage').attr('src', '');

    // refreshDisplayConfiguratorRoute('route');
    // refreshDisplayConfiguratorRoute('background');
    // refreshElements();

}

function openConfigureModal(index) {
    currentModalIndex = index;
    currentModalConfig = configModals.menuItems[index];
    console.log('OPEN MODAL:', currentModalConfig );
    openModal(enumsConfigurator.EDIT_MODAL, index);
}


function addModal() {
    var modalTitle = $('#inputAddModalName').val();
    var modalId = uuidv4();

    console.log('CHECK:', formatEnumKey(modalTitle), enumModals[formatEnumKey(modalTitle)], enumModals)
    if (!enumModals[formatEnumKey(modalTitle)]) {
        enumModals[formatEnumKey(modalTitle)] = modalId;

        configModals.push({
            id: modalId,
            title: modalTitle
        });

        updateStatus.enumModals = true;
        updateStatus.configModals = true;
        $('#publishButton').removeClass('disabled-button');
        $('#publishButton').addClass('green-button');

        console.log('ADD MODAL:', configRoutes, enumRoutes);

        updateListMenu(enumsConfigurator.ROUTE_MODALS);
        showAlert(iconSuccess + ' Modal Created');
    } else {
        showAlert(iconFail + '"' + modalTitle + '" Already Exists');
    }
}


var customFieldsArray = [];

function addAuthenticationCustomField() {
    var count = $("#authenticationCustomContainer select").length;
    console.log('CUSTOM FIELDS COUNT:', count);
    $('.dynamic-list-container').append(`
        <div class="custom-field-row">
            <select class="select-default" onchange="updateCustomFields(this.value, ` + count + `)">
                <option value="none">None</option>
                <option value="usernamePassword">Username / Password</option>
                <option value="email">Email</option>
                <option value="passcode">Shared Passcode</option>
                <option value="emailPasscode">Email / Shared Passcode</option>
                <option value="custom">Custom Fields</option>
            </select>
            <div class="remove-button" onclick="event.stopPropagation(); openModal('deleteCustomField', ` + count + `)">X</div>
        </div>
    `);
}

function removeAuthenticationCustomField(index) {

}

function updateAuthentication(type) {
    $('#passcodeContainer').hide();
    $('#customContainer').hide();
    switch(type) {
        case enumAuthenticationTypes.USERNAME_PASSWORD:
            
        break;
        case enumAuthenticationTypes.EMAIL:
            
        break;
        case enumAuthenticationTypes.PASSCODE:
            $('#passcodeContainer').show();
        break;
        case enumAuthenticationTypes.EMAIL_PASSCODE:
            $('#passcodeContainer').show();
        break;
        case enumAuthenticationTypes.CUSTOM:
            $('#customContainer').show();
        break;
    }
}

function updateCustomFields(value, index) {

}
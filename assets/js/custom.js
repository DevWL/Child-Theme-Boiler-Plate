/**
 * This script enter curent window url to a contact form as reference.
 */
window.addEventListener("load", function(event) {
    let inputField = document.getElementById('et_pb_contact_url_0');
    if(!!inputField){
        let location = window.location.href;
        inputField.value = location;
        inputField.setAttribute('value', location);
        // inputField.setAttribute('type', 'hidden');
        // inputField.style.display = 'none';
        console.log(inputField, location);
    }

});
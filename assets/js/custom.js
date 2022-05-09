/**
 * This script enter curent window url to a contact form as reference.
 * 
 */
window.addEventListener("load", function(event) {
    /* Target form input field */
    let inputField = document.getElementById('et_pb_contact_url_0');

    /* Only run below if element is found */
    if(!!inputField){
        let location = window.location.href;
        inputField.value = location;
        inputField.setAttribute('value', location);

        /* The form input field is hidden from Divi module under advance css, but below two lines can also be also used to hide it */
        // inputField.setAttribute('type', 'hidden');
        // inputField.style.display = 'none';
        /* Testing line */
        // console.log(inputField, location);
    }
});
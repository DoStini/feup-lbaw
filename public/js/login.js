'use stric';

/**
 *
 * @param {HTMLElement} button
 */
function togglePassword(button) {
    let password = document.getElementById(button.value);

    if(password.type === "password" ) {
        password.type = "text";
    } else {
        password.type = "password";
    }
}

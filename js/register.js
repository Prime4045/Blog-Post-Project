
// Check for error and success messages in the URL
const urlParams = new URLSearchParams(window.location.search);
const errorMessage = document.getElementById('errorMessage');
const successMessage = document.getElementById('successMessage');

// User verification
if (urlParams.has('error')) {
    const errorType = urlParams.get('error');
    if (errorType === 'username_taken') {
        errorMessage.textContent = 'Username is already taken.';
    } else if (errorType === 'email_taken') {
        errorMessage.textContent = 'Email is already registered.';
    }
    errorMessage.classList.remove('hidden'); // Show the error message
} else if (urlParams.has('success') && urlParams.get('success') === 'true') {
    successMessage.classList.remove('hidden'); // Show success message
}

// Basic form validation
function validateForm() {
    let valid = true;

    // Username validation
    const username = document.getElementById('username').value;
    const startsWithNumber = /^[0-9]/.test(username); // Check if it starts with a number

    if (username.length < 5 || startsWithNumber) {
        document.getElementById('usernameError').style.display = 'block';
        valid = false;
    } else {
        document.getElementById('usernameError').style.display = 'none';
    }

    // Email validation
    const email = document.getElementById('email').value;
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.match(emailPattern)) {
        document.getElementById('emailError').style.display = 'block';
        valid = false;
    } else {
        document.getElementById('emailError').style.display = 'none';
    }

    // Password validation
    const password = document.getElementById('password').value;
    if (password.length < 6) {
        document.getElementById('passwordError').style.display = 'block';
        valid = false;
    } else {
        document.getElementById('passwordError').style.display = 'none';
    }

    return valid;
}
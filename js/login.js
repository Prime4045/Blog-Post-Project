
// Handle login success/error messages based on URL parameters
const urlParams = new URLSearchParams(window.location.search);

if (urlParams.has('success') && urlParams.get('success') === 'true') {
    // Redirect to user.php after a brief pause
    setTimeout(() => {
        window.location.href = '../html/user.php';
    }, 2000); // Redirect after 2 seconds
} else if (urlParams.has('error')) {
    const errorMessage = document.getElementById('loginMessage');

    if (urlParams.get('error') === 'incorrect_password') {
        errorMessage.textContent = 'Incorrect password. Please try again.';
        errorMessage.style.color = 'red'; // Make the error message red
    } else if (urlParams.get('error') === 'user_not_registered') {
        errorMessage.textContent = 'User not registered. Please sign up.';
        errorMessage.style.color = 'red';
    }

    // Display the message
    errorMessage.style.display = 'block';
}


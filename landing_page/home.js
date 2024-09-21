// Get the sign-up section element
let signupSection = document.getElementById('signup-section');
// Get the button that triggers the sign-up section
let signupButton = document.getElementById('sign-up-btn');
// Get the close button inside the sign-up section
let closeSignupBtn = document.querySelector('.signup-section .close-btn1');

// Get the login modal and button elements
let loginModal = document.getElementById('login-modal');
let loginBtn = document.getElementById('login-btn');
let closeLoginBtn = document.querySelector('.login-body .close-btn');

// Show the sign-up section when the sign-up button is clicked
signupButton.onclick = function() {
    signupSection.classList.add('show'); // Show the sign-up section
    document.getElementById('generalFields').classList.add('hidden');
    document.getElementById('studentFields').classList.add('hidden');

    // Add a small delay to ensure the section is visible before scrolling
    setTimeout(function() {
        signupSection.scrollIntoView({ behavior: 'smooth' });
    }, 100); // Delay for 100ms
};

// Close the sign-up section when the close button is clicked
closeSignupBtn.onclick = function() {
    signupSection.classList.remove('show'); // Hide the sign-up section
};

// Show the login modal when the login button is clicked
loginBtn.onclick = function() {
    loginModal.style.display = 'block'; // Show the login modal
};

// Close the login modal when the close button is clicked
closeLoginBtn.onclick = function() {
    loginModal.style.display = 'none'; // Hide the login modal
};

// Close the login modal when clicking outside the modal
window.onclick = function(event) {
    if (event.target === loginModal) {
        loginModal.style.display = 'none';
    }
};

// Handle showing and hiding fields based on the selected role
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;

    // Hide all fields first
    document.getElementById('studentFields').classList.add('hidden');
    document.getElementById('houseWardenFields').classList.add('hidden');
    document.getElementById('hallSecretaryFields').classList.add('hidden');
    document.getElementById('generalFields').classList.remove('hidden');

    // Show the relevant fields
    if (role === 'student') {
        document.getElementById('studentFields').classList.remove('hidden');
    } else if (role === 'houseWarden') {
        document.getElementById('houseWardenFields').classList.remove('hidden');
    } else if (role === 'hallSec') {
        document.getElementById('hallSecretaryFields').classList.remove('hidden');
    }
});

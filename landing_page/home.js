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

// document.getElementById('role').addEventListener('change', function() {
//     const role = this.value;

//     // Hide all role-specific field sections and remove 'required' from their inputs
//     document.getElementById('studentFields').classList.add('hidden');
//     document.getElementById('houseWardenFields').classList.add('hidden');
//     document.getElementById('hallSecretaryFields').classList.add('hidden');
//     document.querySelectorAll('#studentFields input, #houseWardenFields input, #hallSecretaryFields input').forEach(input => input.required = false);

//     // Ensure the general fields are always visible and required
//     document.getElementById('generalFields').classList.remove('hidden');
//     document.querySelectorAll('#generalFields input').forEach(input => input.required = true);

//     // Show the relevant role-specific fields and set them to required
//     if (role === 'student') {
//         document.getElementById('studentFields').classList.remove('hidden');
//         document.querySelectorAll('#studentFields input').forEach(input => input.required = true);

//     } else if (role === 'house warden') {
//         document.getElementById('houseWardenFields').classList.remove('hidden');
//         document.querySelectorAll('#houseWardenFields input').forEach(input => input.required = true);

//     } else if (role === 'hall secretary') {
//         document.getElementById('hallSecretaryFields').classList.remove('hidden');
//         document.querySelectorAll('#hallSecretaryFields input').forEach(input => input.required = true);
//     }
// });

document.getElementById('role').addEventListener('change', function() {
    const role = this.value;

    // Hide all role-specific field sections and remove 'required' from their inputs
    document.getElementById('studentFields').classList.add('hidden');
    document.getElementById('houseWardenFields').classList.add('hidden');
    document.getElementById('hallSecretaryFields').classList.add('hidden');

    // Remove 'required' from all inputs within these sections
    document.querySelectorAll('#studentFields input, #houseWardenFields input, #hallSecretaryFields input').forEach(input => {
        input.removeAttribute('required');
        input.required = false; // For older browsers
    });

    // Ensure the general fields are always visible and required
    document.getElementById('generalFields').classList.remove('hidden');
    document.querySelectorAll('#generalFields input').forEach(input => {
        input.setAttribute('required', 'required');
    });

    // Show the relevant role-specific fields and set them to required
    if (role === 'student') {
        const studentFields = document.getElementById('studentFields');
        studentFields.classList.remove('hidden');  // Show student fields
        document.querySelectorAll('#studentFields input').forEach(input => {
            input.setAttribute('required', 'required');
        });
    } else if (role === 'house warden') {
        const houseWardenFields = document.getElementById('houseWardenFields');
        houseWardenFields.classList.remove('hidden');  // Show house warden fields
        document.querySelectorAll('#houseWardenFields input').forEach(input => {
            input.setAttribute('required', 'required');
        });
    } else if (role === 'hall secretary') {
        const hallSecretaryFields = document.getElementById('hallSecretaryFields');
        hallSecretaryFields.classList.remove('hidden');  // Show hall secretary fields
        document.querySelectorAll('#hallSecretaryFields input').forEach(input => {
            input.setAttribute('required', 'required');
        });
    }
});

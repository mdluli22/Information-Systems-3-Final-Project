// Get the sign-up section element
var signupSection = document.getElementById('signup-section');

// Get the button that triggers the sign-up section
var signupButton = document.getElementById('sign-up-btn');

// Get the close button inside the sign-up section
var closeBtn = document.querySelector('.signup-section .close-btn1');

// When the user clicks the button, show the sign-up section
signupButton.onclick = function() {
    signupSection.classList.add('show'); // Show the sign-up section
    
    // Add a small delay to ensure the section is visible before scrolling
    setTimeout(function() {
        signupSection.scrollIntoView({ behavior: 'smooth' });
    }, 100); // Delay for 100ms
}

// When the user clicks on the close button, hide the section
closeBtn.onclick = function() {
    signupSection.classList.remove('show'); // Hide the sign-up section
}

// Get the login modal and button elements
var loginModal = document.getElementById('login-modal');
var loginBtn = document.getElementById('login-btn');
var closeBtn = document.querySelector('.login-body .close-btn');

// When the user clicks the login button, show the modal
loginBtn.onclick = function() {
    loginModal.style.display = 'block';
}

// When the user clicks on the close button, hide the modal
closeBtn.onclick = function() {
    loginModal.style.display = 'none';
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target === loginModal) {
        loginModal.style.display = 'none';
    }
}

document.addEventListener('scroll', function() {
    const articles = document.querySelectorAll('.page-on-home article');
    const windowHeight = window.innerHeight;

    articles.forEach(article => {
        const articleTop = article.getBoundingClientRect().top;

        if (articleTop < windowHeight) {
            article.classList.add('visible');
        }
    });
});

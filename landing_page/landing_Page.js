// Get the modals
let loginModal = document.getElementById("login-modal");
let signupModal = document.getElementById("signup-modal");

// Get the buttons that open the modals
let loginBtn = document.getElementById("login-btn");
let signupBtn = document.getElementById("signup-btn");

// Get the <span> elements that close the modals
let closeBtns = document.getElementsByClassName("close-btn");
let closeBtns2 = document.getElementsByClassName("close-btn1");

// When the user clicks the button, open the corresponding modal
loginBtn.onclick = function() {
    loginModal.style.display = "block";
}

signupBtn.onclick = function() {
    signupModal.style.display = "block";
}

// When the user clicks on a <span> (x), close the modal
for (let i = 0; i < closeBtns.length; i++) {
    closeBtns[i].onclick = function() {
        loginModal.style.display = "none";
        signupModal.style.display = "none";
    }
}

for (let x = 0; x < closeBtns2.length ; x++) {
    closeBtns2[x].onclick = function() {
        loginModal.style.display = "none";
        signupModal.style.display = "none";
    }
    
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == loginModal) {
        loginModal.style.display = "none";
    }
    if (event.target == signupModal) {
        signupModal.style.display = "none";
    }
}
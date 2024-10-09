
document.querySelectorAll('.house-link').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.house-link').forEach(el => el.classList.remove('active'));
        this.classList.add('active');
    });
});

// function remove_feedback() {
//     document.getElementById('success-message').style.display = 'none';
// }

// setTimeout(function() {
//     document.getElementById('success-message').style.display = 'none';
// }, 5000); // Hide after 5 seconds


// Function to remove feedback messages
function remove_requisitioned_feedback() {
    const requisitionMessage = document.getElementById('requisition-message');
    if (requisitionMessage) {
        requisitionMessage.style.display = 'none';
    }
}

function remove_close_feedback() {
    const closeMessage = document.getElementById('close-message');
    if (closeMessage) {
        closeMessage.style.display = 'none';
    }
}

// Automatically hide both messages after 10 seconds
setTimeout(remove_requisitioned_feedback, 10000);
setTimeout(remove_close_feedback, 10000);

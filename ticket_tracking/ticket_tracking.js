/* 
document.querySelectorAll('.sidebar-links').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.sidebar-links').forEach(el => el.classList.remove('active'));
        this.classList.add('active');
    });
}); */

// Select all elements with the class 'sidebar-links'
const sidebarLinks = document.querySelectorAll('.sidebar-links');

// Iterate over each 'sidebar-links' element
sidebarLinks.forEach(link => {
    // Add a click event listener to each link
    link.addEventListener('click', function() {
        // Remove the 'active' class from all 'sidebar-links' elements
        sidebarLinks.forEach(el => el.classList.remove('active'));

        // Add the 'active' class to the clicked link
        this.classList.add('active');
    });
});

/* function showDetails(ticketId) {
    // Logic to display details for the ticket
    console.log("Show details for ticket ID:", ticketId);
    // Example: toggle visibility of a details section
    var detailsSection = document.getElementById('details-' + ticketId);
    if (detailsSection.style.display === 'none') {
        detailsSection.style.display = 'block';
    } else {
        detailsSection.style.display = 'none';
    }
} */
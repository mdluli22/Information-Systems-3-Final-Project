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
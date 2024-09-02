function openTab(tabId, hallName) {
    // Hide all tab contents
    const contents = document.querySelectorAll('.stats-overview');
    contents.forEach(content => {
        content.classList.remove('active');
    });

    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.tab-link');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });

    // Show the selected tab content
    document.getElementById(tabId).classList.add('active');

    // Add active class to the clicked tab
    event.currentTarget.classList.add('active');


}


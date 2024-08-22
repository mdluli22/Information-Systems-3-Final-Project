
document.querySelectorAll('.sidebar-links').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.sidebar-links').forEach(el => el.classList.remove('active'));
        this.classList.add('active');
    });
});
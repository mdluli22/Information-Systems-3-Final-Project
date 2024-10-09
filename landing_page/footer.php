<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
</head>
<body>
<footer class="footer">
    <div class="footer-links">
        <a href="../footer_links/links.html#integrity-and-constraints">Integrity & Compliance</a>
        <a href="../footer_links/links.html#legal">Legal</a>
        <a href="../footer_links/links.html#manage-cookies">Manage Cookies</a>
        <a href="../footer_links/links.html#privacy-policy">Privacy Policy</a>
    </div>
    <p>&copy; <time datetime="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></time> ResQue </p>
</footer>
<script src="home.js"></script>
<style>
    
/* Footer */
.footer {
    background-color: #12222E; /* Same color as the header */
    color: white;
    padding: 2rem 0; /* Padding for space inside the footer */
    text-align: center; /* Center the content */
    font-family: 'Inter', sans-serif;
    width: 100%;
    position: relative;
    margin-top: auto; /* Pushes the footer to the bottom if content is short */
}

/* Footer Links */
.footer-links {
    display: flex;
    justify-content: center; /* Center the footer links */
    gap: 2rem; /* Space between the links */
    margin-bottom: 1rem; /* Space below the links */
    
}

.footer-links a {
    text-decoration: none;
    color: #ffffff;
    font-weight: 500;
    transition: color 0.3s ease-in-out;
}

.footer-links a:hover {
    color: #B45C3D; /* Same hover effect as other links */
}

/* Footer text (copyright) */
.footer p {
    margin-top: 1rem;
    font-size: 0.875rem;
    color: #ddd; /* Lighter text for the copyright */
}

/* Responsive Footer Adjustments */
@media (max-width: 768px) {
    .footer-links {
        flex-direction: column; /* Stack the links vertically on smaller screens */
        gap: 1rem;
    }
}
</style>
</body>
</html>
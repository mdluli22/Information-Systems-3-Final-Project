<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <style>
        /* Your existing CSS styles */
        .nav-links li a.active {
            background-color: #B45C3D; /* Highlight color */
            color: white;
        }
    </style>
</head>
<body>
<header>
    <img src="pictures/fake logo(1).png" alt="Syntax On Air Logo" class="logo">

    <!-- Navigation bar -->
    <nav>
        <ul class="nav-links">
            <li><a href="../landing_page/faq.php" class="home-links">FAQ</a></li>
            <li><a href="../landing_page/aboutus.php" class="home-links">About Us</a></li>
            <li><a href="../landing_page/help.php" class="home-links">Help</a></li>
            <li><a href="https://www.ru.ac.za/safety/resources/">Resources</a></li>
        </ul>
        <div class="user-actions">
            <a href="#" class="login-btn" id="login-btn">Login</a>
            <a href="#" class="signup-btn" id="sign-up-btn">Sign up</a>
        </div>
    </nav>

    <!-- Burger menu for mobile -->
    <input type="checkbox" id="burger-toggle" />
    <label for="burger-toggle" class="burger">
        <div></div>
        <div></div>
        <div></div>
    </label>

    <div class="mobile-menu">
        <a href="../landing_page/home.php">Home</a>
        <a href="../landing_page/aboutus.php">About Us</a>
        <a href="help.html">Help</a>
    </div>
</header>
    <style>
/* Everything regarding the header section of the page */
header {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #12222E;
    box-shadow: 0 0.25rem 0.375rem rgba(0, 0, 0, 0.1);
    height: 5.5rem;
    z-index: 1000; /* Ensure it stays on top of other content */
}

/* Styling of the logo */
header .logo {
    height: 100px; /* Adjust the height as needed */
    max-width: 100%; /* Ensure logo is responsive */
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 1.875rem;
    justify-content: center;
    flex: 2;
}

.nav-links li a {
    padding: 0.625rem 1.25rem;
    text-decoration: none;
    color: white;
    font-weight: 500;
    transition: background-color 0.3s ease, color 0.3s ease;
    border-radius: 1.25rem; /* Set border-radius initially to avoid layout shift */
}

.nav-links li a:hover {
    background-color: #B45C3D;
    color: white;
    cursor: pointer;
}

/* User actions: login and sign-up buttons */
/* .user-actions {
    display: flex;
    align-items: center;
    gap: 0.9375rem;
    justify-content: flex-end;
} */

/* the buttons on the top right  */
.login-btn,
.signup-btn {
    padding: 0.625rem 1.25rem;
    border: none;
    background-color: #B45C3D;
    color: white;
    border-radius: 1.25rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.login-btn:hover,
.signup-btn:hover {
    background-color: #5a6268;
}

/* Burger Menu */
.burger {
    display: none;
    flex-direction: column;
    cursor: pointer;
    margin-right: 1.25rem;
}

.burger div {
    width: 25px;
    height: 3px;
    background-color: #B45C3D;
    margin: 4px;
}

/* Hide Checkbox */
#burger-toggle {
    display: none;
}

/* Mobile Menu */
.mobile-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    width: 100%;
    background-color: white;
    flex-direction: column;
    padding: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.mobile-menu a {
    padding: 1rem 0;
    color: #B45C3D;
    text-align: center;
    text-decoration: none;
}

/* Show Mobile Menu When Checkbox is Checked */
#burger-toggle:checked ~ .mobile-menu {
    display: flex;
}

#burger-toggle:checked ~ nav ul {
    display: none;
}

/* Media Query for Responsive Navigation */
@media (max-width: 1200px) {
    .nav-links {
        display: none;
    }

    .burger {
        display: flex;
    }
}
.nav-links li a.active {
            background-color: #B45C3D; /* Highlight color */
            color: white;
        }
    </style>
<script>
    // JavaScript to highlight the active link based on the current URL
    const navLinks = document.querySelectorAll('.nav-links li a');
    const currentUrl = window.location.href;

    navLinks.forEach(link => {
        // Check if the current URL contains the link's href
        if (currentUrl.includes(link.href)) {
            link.classList.add('active');
        }

        // Add click event listener to highlight the clicked link
        link.addEventListener('click', function(event) {
            // Remove 'active' class from all links
            navLinks.forEach(link => link.classList.remove('active'));

            // Add 'active' class to the clicked link
            this.classList.add('active');
        });
    });
</script>
</body>
</html>

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | ResQue</title>
    <link rel="shortcut icon" href="pictures/3-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="home.css">
</head>

<body>
    <div class="container">
        <header>
            <img src="pictures/fake logo(1).png" alt="Syntax On Air Logo" class="logo">

            <!-- navigation bar -->
            <nav>
                <ul class="nav-links">
                    <li><a href="../landing_page/faq.php">FAQ</a></li>
                    <li><a href="../landing_page/aboutus.php">About Us</a></li>
                    <li><a href="#">Help</a></li>
                    <li><a href="#">Resources</a></li>
                </ul>
                <div class="user-actions">
                    <a href="#" class="login-btn" id="login-btn">Login</a>
                    <a href="#" class="signup-btn" id="sign-up-btn">Sign up</a>
                </div>
            </nav>

            <!-- for when switching to a smaller screen -->
            <!-- for burger menu for mobile -->
            <input type="checkbox" id="burger-toggle" />
            <label for="burger-toggle" class="burger">
                <div></div>
                <div></div>
                <div></div>
            </label>

            <div class="mobile-menu">
                <a href="index.html">Home</a>
                <a href="about.html">About Us</a>
                <a href="review.html">Reviews</a>
                <a href="help.html">Help</a>
            </div>
        </header>

        <!-- the head of the page -->
        <section class="hero">
            <div class="hero-content">
                <div>
                <h2>Saving Your Day, One Fix at a Time</h2>
                <p>Easily report your maintenance issues and have them resolved without any hassle.</p>
                </div>
            </div>
        </section>

    <section>
        <!-- Login Modal Structure -->
        <div class="login-body">
            <div id="login-modal" class="modal">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                        <div class="login-form">
                            <h1>Welcome Back 👋</h1>
                            <p>Enter your login credentials. Please ensure that login credentials are typed correctly.</p>
                            <form action="login.php" method="post">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" placeholder="Username" required maxlength="8">
                                
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="Password" required>
                                
                                <a href="#" class="forgot-password">Forgot Password?</a>
                                
                                <input type="submit" id="login-submit-btn" name="submit" value="Sign in">
                            </form>
                            <p>Don't have an account? <a href="../landing_page/landing_Page.html">Sign up</a></p>
                        </div>
                </div>
            </div>
        </div>

        <!-- Sign-up section -->
        <div id="signup-section" class="signup-section">
            <span class="close-btn1">&times;</span>
            <div class="container">
                <h1>Welcome 👋</h1>
                <div><p>Please enter your credentials. Ensure that the credentials are typed correctly.</p></div>

                <form action="signup.php" method="POST">
                    <label for="role">Role</label>
                    <select name="role" id="role" required>
                        <option value="">Select Role</option>
                        <option value="student">Student</option>
                        <option value="house warden">House Warden</option>
                        <option value="hall secretary">Hall Secretary</option>
                    </select>

                    <!-- genetal field -->
                    <div id="generalFields" class="hidden">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" placeholder="John" name="fname" required>

                        <label for="lname">Surname</label>
                        <input type="text" id="lname" placeholder="Smith" name="lname" required>

                        <label for="username">Username</label>
                        <input type="text" id="username" placeholder="g12s3456" name="username" maxlength="8" required>
                        
                        <label for="password">Password</label>
                        <input type="password" id="password" placeholder="*******" name="password" required>

                        <label for="email">Email</label>
                        <input type="email" id="email" placeholder="g12s3456@campus.ru.ac.za" name="email" required>
                    </div>

                    <!-- the student area -->
                    <div id="studentFields">

                        <label for="hall">Hall Name</label>
                        <select name="studentHall" id="studentHall">
                            <option value="">Select Hall Name</option>
                            <option value="Mandela Hall">Nelson Mandela Hall</option>
                            <option value="Hobson Hall">Hobson Hall</option>
                            <option value="Drostdy Hall">Drostdy Hall</option>
                            <option value="Founders Hall">Founders Hall</option>
                        </select>

                        <label for="resName">Residence Name</label>
                        <input type="text" id="resName" placeholder="Enter resident name" name="resName">

                        <label for="roomNumber">Room Number</label>
                        <input type="text" id="roomNumber" placeholder="Enter room number" name="roomNumber" pattern="\d{1,3}">

                    </div>
                
                    <!-- House Warden Fields -->
                    <div id="houseWardenFields" class="hidden">

                        <label for="hall">Hall Name</label>
                        <select name="hw-hall" id="hw-hall">
                            <option value="">Select Hall Name</option>
                            <option value="Mandela Hall">Nelson Mandela Hall</option>
                            <option value="Hobson Hall">Hobson Hall</option>
                            <option value="Drostdy Hall">Drostdy Hall</option>
                            <option value="Founders Hall">Founders Hall</option>
                        </select>

                        <label for="hw-resName">Residence Name</label>
                        <input type="text" id="hw-resName" placeholder="Enter resident name" name="hw_resName">
                    </div>

                    <!-- Hall Secretary Fields -->
                    <div id="hallSecretaryFields" class="hidden">

                        <label for="hallSecretaryHall">Hall Name</label>
                        <select name="hallSecretaryHall" id="hallSecretaryHall">
                            <option value="">Select Hall Name</option>
                            <option value="Mandela Hall">Nelson Mandela Hall</option>
                            <option value="Hobson Hall">Hobson Hall</option>
                            <option value="Drostdy Hall">Drostdy Hall</option>
                            <option value="Founders Hall">Founders Hall</option>
                        </select>
                    </div>
                    <input type="submit" id="signup-submit-btn" name="submit" value="Signup">
                </form>

                <p>Already have an account? <a href="login_page.html">Login</a></p>
            </div>
        </div>
    </section>
    </div>

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
</body>

</html>
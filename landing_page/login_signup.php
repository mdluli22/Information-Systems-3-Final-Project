<?php
// Start the session only if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    // session_start();
}
?>
<section>
    <!-- Login Modal Structure -->
    <div class="login-body">
        <div id="login-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <div class="login-form">
                    <h1>Welcome Back 👋</h1>
                    <p>Please enter your login credentials. Please ensure that login credentials are typed correctly.</p>
                    <form action="login.php" method="post">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Username" required maxlength="8">
                        
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        
                        <a href="#" class="forgot-password">Forgot Password?</a>
                        
                        <input type="submit" id="login-submit-btn" name="submit" value="Sign in">
                    </form>
                    <p>Don't have an account? <a href="../landing_page/home.php">Sign up</a></p>
                </div>
                <!-- Hidden element to store error message -->
                <div id="error-message" style="display: none;">
                    <?php
                    // Display the error message if it exists
                    if (isset($_SESSION['error'])) {
                        echo $_SESSION['error'];
                        // Unset the error session variable after displaying
                        unset($_SESSION['error']);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
        <body>
            <!-- Hidden element to store error message -->
            <div id="error-message" style="display: none;"><?php echo $error; ?></div>
        </body>

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
                        <option value="maintenance_staff">Maintenance</option>
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

                        <label for="resNameStudent">Residence Name</label>
                        <select name="resNameStudent" id="resNameStudent">
                            <option value="">Select Resident Name</option>
                            <option value="Botha House">Botha House</option>
                            <option value="College House">College House</option>
                            <option value="Cory House">Cory House</option>
                            <option value="Matthews House">Matthews House</option>
                            <option value="Allan Gray House">Allan Gray House</option>
                            <option value="Celeste House">Celeste House</option>
                            <option value="Graham House">Graham House</option>
                            <option value="Prince Alfred House">Prince Alfred House</option>
                            <option value="Okdane House">Okdane House</option>
                            <option value="Dingemans House">Dingemans House</option>
                            <option value="Hobson House">Hobson House</option>
                            <option value="Starling House">Starling House</option>
                            <option value="Livingstone House">Livingstone House</option>
                            <option value="Milner House">Milner House</option>
                            <option value="Adelaide Tambo House">Adelaide Tambo House</option>
                            <option value="Helen Joseph House">Helen Joseph House</option>
                            <option value="Guy Butler House">Guy Butler House</option>
                            <option value="Stanley Kidd House">Stanley Kidd House</option>
                        </select>
                        
                        <label for="roomNumber">Room Number</label>
                        <input type="number" id="roomNumber" placeholder="Enter room number" name="roomNumber" pattern="\d{1,3}">

                    </div>
                
                    <!-- House Warden Fields -->
                    <div id="houseWardenFields" class="hidden">

                        <label for="hw-hall">Hall Name</label>
                        <select name="hw-hall" id="hw-hall">
                            <option value="">Select Hall Name</option>
                            <option value="Mandela Hall">Nelson Mandela Hall</option>
                            <option value="Hobson Hall">Hobson Hall</option>
                            <option value="Drostdy Hall">Drostdy Hall</option>
                            <option value="Founders Hall">Founders Hall</option>
                        </select>

                        <label for="hw_resName">Residence Name</label>
                        <select name="hw_resName" id="hw_resName">
                            <option value="">Select Resident Name</option>
                            <option value="Botha House">Botha House</option>
                            <option value="College House">College House</option>
                            <option value="Cory House">Cory House</option>
                            <option value="Matthews House">Matthews House</option>
                            <option value="Allan Gray House">Allan Gray House</option>
                            <option value="Celeste House">Celeste House</option>
                            <option value="Graham House">Graham House</option>
                            <option value="Prince Alfred House">Prince Alfred House</option>
                            <option value="Okdane House">Okdane House</option>
                            <option value="Dingemans House">Dingemans House</option>
                            <option value="Hobson House">Hobson House</option>
                            <option value="Starling House">Starling House</option>
                            <option value="Livingstone House">Livingstone House</option>
                            <option value="Milner House">Milner House</option>
                            <option value="Adelaide Tambo House">Adelaide Tambo House</option>
                            <option value="Helen Joseph House">Helen Joseph House</option>
                            <option value="Guy Butler House">Guy Butler House</option>
                            <option value="Stanley Kidd House">Stanley Kidd House</option>
                        </select>
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

                    <div id="maintenanceFields" class="hidden">
                        <!-- Add this option to the "Role" dropdown in the sign-up form -->
                    </div>
                    <input type="submit" id="signup-submit-btn" name="submit" value="Signup">
                </form>

                <p>Already have an account? <a href="../landing_page/home.php">Login</a></p>
            </div>
        </div>
    </section>
    </div>

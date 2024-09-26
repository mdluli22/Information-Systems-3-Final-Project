<section>
    <!-- Login Modal Structure -->
    <div class="login-body">
        <div id="login-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <div class="login-form">
                    <h1>Welcome Back </h1>
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
            <h1>Welcome </h1>
            <div>
                <p>Please enter your credentials. Ensure that the credentials are typed correctly.</p>
            </div>

            <form action="signup.php" method="post">
                <label for="fname">First Name</label>
                <input type="text" id="fname" placeholder="John" name="fname" required>

                <label for="lname">Surname</label>
                <input type="text" id="lname" placeholder="Smith" name="lname" required>

                <label for="email">Email</label>
                <input type="email" id="email" placeholder="g12s3456@campus.ru.ac.za" name="email" required>

                <label for="resName">Resident Name</label>
                <input type="text" id="resName" placeholder="res name" name="resName" required>

                <label for="hall">Hall Name</label>
                <select name="hall" id="hall" required>
                    <option value="">Please enter fault category</option>
                    <option value="Miriam Makeba Hall">Miriam Makeba Hall</option>
                    <option value="Mandela Hall">Mandela Hall</option>
                    <option value="Solomon Kalushi Mahlangu">Solomon Kalushi Mahlangu</option>
                    <option value="Lillian Ngoyi Hall">Lillian Ngoyi Hall</option>
                    <option value="Courtenay-Latimer Hall">Courtenay-Latimer Hall</option>
                    <option value="Kimberly Hall">Kimberly Hall</option>
                    <option value="Allan Webb Hall">Allan Webb Hall</option>
                    <option value="St Mary Hall">St Mary Hall</option>
                    <option value="Hobson Hall">Hobson Hall</option>
                    <option value="Desmond Tutu">Desmond Tutu</option>
                    <option value="Drostdy Hall">Drostdy Hall</option>
                    <option value="Founders Hall">Founders Hall</option>
                    <option value="Hugh Masekela Hall">Hugh Masekela Hall</option>
                </select>

                <label for="roomNumber">Room Number</label>
                <input type="text" id="roomNumber" placeholder="123" name="roomNumber" pattern="\d{1,3}" required>

                <label for="username">Username</label>
                <input type="text" id="username" placeholder="g12s3456" name="username" required maxlength="8">

                <label for="password">Password</label>
                <input type="password" id="password" placeholder="*******" name="password" required>

                <input type="submit" id="signup-submit-btn" name="submit" value="Sign up">
            </form>
            <p>Already have an account? <a href="login_page.html">Login in</a></p>
        </div>
    </div>
</section>


<script>
    // Get the sign-up section element
    var signupSection = document.getElementById('signup-section');

    // Get the button that triggers the sign-up section
    var signupButton = document.getElementById('sign-up-btn');

    // Get the close button inside the sign-up section
    var closeBtn = document.querySelector('.signup-section .close-btn1');

    // When the user clicks the button, show the sign-up section
    signupButton.onclick = function() {
        signupSection.classList.add('show'); // Show the sign-up section

        // Add a small delay to ensure the section is visible before scrolling
        setTimeout(function() {
            signupSection.scrollIntoView({
                behavior: 'smooth'
            });
        }, 100); // Delay for 100ms
    }

    // When the user clicks on the close button, hide the section
    closeBtn.onclick = function() {
        signupSection.classList.remove('show'); // Hide the sign-up section
    }

    // Get the login modal and button elements
    var loginModal = document.getElementById('login-modal');
    var loginBtn = document.getElementById('login-btn');
    var closeBtn = document.querySelector('.login-body .close-btn');

    // When the user clicks the login button, show the modal
    loginBtn.onclick = function() {
        loginModal.style.display = 'block';
    }

    // When the user clicks on the close button, hide the modal
    closeBtn.onclick = function() {
        loginModal.style.display = 'none';
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target === loginModal) {
            loginModal.style.display = 'none';
        }
    }

    document.addEventListener('scroll', function() {
        const articles = document.querySelectorAll('.page-on-home article');
        const windowHeight = window.innerHeight;

        articles.forEach(article => {
            const articleTop = article.getBoundingClientRect().top;

            if (articleTop < windowHeight) {
                article.classList.add('visible');
            }
        });
    });

    document.getElementById('role').addEventListener('change', function() {
        let role = this.value;

        // Show general fields for all roles once a role is selected
        document.getElementById('generalFields').classList.remove('hidden');

        // Hide all role-specific fields initially
        document.getElementById('studentFields').classList.add('hidden');

        // Show fields based on the selected role
        if (role === 'student') {
            document.getElementById('studentFields').classList.remove('hidden');
        }
    });

    // Initially, only show the role dropdown when the sign-up button is clicked
    signupButton.onclick = function() {
        signupSection.classList.add('show'); // Show the sign-up section
        document.getElementById('generalFields').classList.add('hidden');
        document.getElementById('studentFields').classList.add('hidden');
        // Add any other fields you want to hide initially

        // Add a small delay to ensure the section is visible before scrolling
        setTimeout(function() {
            signupSection.scrollIntoView({
                behavior: 'smooth'
            });
        }, 100); // Delay for 100ms
    };
</script>
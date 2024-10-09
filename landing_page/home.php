<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | ResQue</title>
    <link rel="shortcut icon" href="pictures/fake logo(1).png" type="image/x-icon">
    <link rel="stylesheet" href="../landing_page/home.css">
    <link rel="stylesheet" href="../landing_page/login_signup.css">
</head>

<body>
    <!-- Modal for messages -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <p id="messageContent" 
            data-message="<?php echo isset($_SESSION['success']) ? $_SESSION['success'] : ''; ?>"
            data-message-type="<?php echo isset($_SESSION['success']) ? 'success' : ''; ?>"></p>
            <button class="close-btn-pop" onclick="closeModal()">Close</button>
        </div>
    </div>


    <div class="container">
        <?php
        require_once('../landing_page/header.php');
        ?>
        <!-- the head of the page -->
        <section class="hero">
            <div class="hero-content">
                <div>
                    <h2>Saving Your Day, <br>
                    One Fix at a Time</h2>
                    <p>Easily report your maintenance issues and have them resolved without any hassle.</p>
                </div>
            </div>
        </section>
        <br>

        <!-- Card Section -->
        <section class="card-section">

            <div class="card about">
                <h4 class="card-title" id="headings">About ResQue</h4>
                    <img src="../landing_page/pictures/aboutresque.JPG (1).jpg" alt="about the team"><br>
                    <h4 id="text">We are a website that ensures maintenance is made faster
                        and much more efficent. <br>
                        Providing real time communication. <br>
                        Faster maintenance faults resolved<br>
                        Reliable residence staff,maintenance, and friendly residence mates too.
                    </h4>
            </div>

            <div class="card what-wedo">
                <h4 class="card-title" id="headings">What does ResQue do?</h4>
                    <img src="../landing_page/pictures/resquedoes.jpg" alt="maintenance"><br>
                    <h4 id="text">Maintain residence faults digitally<br>
                        Ensure a room is a home away from home<br>
                        Offer real time communication through the website <br>
                        Every student and staff member is a customer to us
                    </h4>
            </div>

            <div class="card contact">
                <h4 class="card-title" id="headings">How to contact us?</h4>
                    <img src="../landing_page/pictures/contactus.JPG" alt="contact us">
                    <h4 id="text"> Email:                                        systemsurgeons@gmail.com <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;informationsystems@ru.ac.za <br><br>
                        Physical Location:                 Hamilton Building
                        Prince Alfred Street
                        6139, Makhanda (Grahamstown) <br><br>
                        Telephone:&nbsp;&nbsp;<a href="tel:+27420202020">+27 42 020 2020</a> <br>
                        Office hours: 08:00 am - 16:30pm <br>
                       Office: Rhodes University Hamilton third floor office 22
                    </h4>
            </div>
        </section>

        <!-- the login and sign up area -->
        <?php
        require_once('../landing_page/login_signup.php');
        ?>
        <script src="../landing_page/home.js"></script>

        <?php
        require_once('../landing_page/footer.php');
        ?>
    </div>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const messageContent = document.getElementById('messageContent');
        if (messageContent) {
            const message = messageContent.getAttribute('data-message');
            const messageType = messageContent.getAttribute('data-message-type');

            if (message) {
                // Set the message content
                messageContent.textContent = message;
                
                // Add the appropriate class to the modal content
                const modalContent = document.querySelector('.modal-content');
                if (messageType === 'success') {
                    modalContent.classList.add('success');
                }
                // Display the modal
                document.getElementById('messageModal').style.display = 'block';

                // Automatically close the modal after 5 seconds (5000 milliseconds)
                setTimeout(function() {
                    closeModal();
                }, 5000);
            }
        }
    });

    // Function to close the modal
    function closeModal() {
        document.getElementById('messageModal').style.display = 'none';
    }
</script>
    <?php
    // Clear session messages
    unset($_SESSION['success'], $_SESSION['error']);
    ?>
</body>

</html>
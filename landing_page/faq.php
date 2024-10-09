<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="faq.css">
    <link rel="shortcut icon" href="pictures/fake logo(1).png" type="image/x-icon">
    <title>FAQ | ResQue</title>
    <!-- <link rel="stylesheet" href="home.css"> -->
    <link rel="stylesheet" href="login_signup.css">
</head>
<body>

    <!-- the header background image -->
    <section class="hero">
        <?php
            require_once('header.php');
        ?>

    </section>

    <!-- back to home page -->
    <a id="back-home" href="../landing_page/home.php">Back Home</a>
    
    <h4>Frequently Asked Questions</h4>

    <section class="faq">
        <h2>General Questions</h2>

        <div class="faq-grid">
            <div class="faq-item">
                <h3>How to Sign-up</h3>
                <p>Registering for a ResQue account is even easier with a choice of various ways to register, you can use our home page,
                    navigate to the home page, press sign-up, enter credentials, and proceed on. </p>
            </div>

            <div class="faq-item">
                <h3>How to login</h3>
                <p>Logging in for a ResQue account is even easier with a choice of various ways to login, you can use our home page,
                    navigate to the home page, press login, enter credentials, and proceed on.</p>
            </div>


            <div class="faq-item">
                <h3>Set Up Account</h3>
                <p>Registering for a Stuffsus account is even easier with a choice of various ways to register, you can use Google, Facebook, etc.</p>
            </div>

            <div class="faq-item">
                <h3>Why is my website down?</h3>
                <p>This might occur due to several reasons. The site is undergoing routine update, there are secutriy vulnerabilites being addressed, or a new feature is being implemented.</p>
            </div>

            <div class="faq-item">
                <h3>How can I contact your customer support team?</h3>
                <p>Good day valued customer, if you'd like to enquire about anything please send an email to systemsurgeonsit@gmail.com.</p>
            </div>

            <div class="faq-item">
                <h3>how do i reset my password?</h3>
                <p>If you forgot your password, please press on "forgot password" on the login/sign up page.</p>
            </div>

            <div class="faq-item">
                <h3>Do you offer technical support?</h3>
                <p>Yes we do, please do not hesitate to drop us an email on anything else at systemsurgeonsit@gmail.com.</p>
            </div>

            <div class="faq-item">
                <h3>Any alternative way to access the site, while undergoing maintenance?</h3>
                <p>unfortunately there isn't another way, hwoever please be aware of any maintenance underway, we will tell you in due time if anything big.</p>
            </div>

            <div class="faq-item">
                <h3>How will I be notified of any changes on my ticket?</h3>
                <p>On your sidebar there will be a number, which indicates the changes to your ticket, or any comment added.</p>
            </div>

            <div class="faq-item">
                <h3>How do I create a ticket as a student?</h3>
                <p>Please refer to the help section, and the videos uploaded on any naviagtion issues surrounding our website.</p>
            </div>


        </div>

        <br>
        <br>

        </div>
        <br><br>

        <h2>Student Support Questions</h2>
        <div class="faq-grid">
            <div class="faq-item">
                <h3>How to Manage Stress</h3>
                <p>Ensure you contact on RU psychologists on campus, to deal with the matter if urgent.</p>
            </div>
            <div class="faq-item">
                <h3>How does the system work?</h3>
                <p>Please refer to the help button on the home page, as there are vidoes to assist you, on how to navigate the system .</p>
            </div>
            <div class="faq-item">
                <h3>How do I contact the admin office, my email says invalid</h3>
                <p>Please send an email to systemsurgeonsit@gmail.com, and a admin officer will be in contact with you soon.</p>
            </div>

            <div class="faq-item">
                <h3>Are there any fees associated with my credentials when I login</h3>
                <p>Good day fellow student, there aren't any fees associated with your credentials being used to login or sign up</p>
            </div>

            <div class="faq-item">
                <h3>How do I contact the admin office, my email says invalid</h3>
                <p>Please send an email to systemsurgeonsit@gmail.com, and a admin officer will be in contact with you soon.</p>
            </div>

            <div class="faq-item">
                <h3>How do I schedule an appointment for counselling or mental health sercvices</h3>
                <p>Please refer to the dropdown on 'Resources' as the emergency contact for them are there.</p>
            </div>

            <div class="faq-item">
                <h3>Is there a crisis hotline?</h3>
                <p>Please refer to the back of your student card for these details, and take note of them</p>
            </div>

            <div class="faq-item">
                <h3>Do you offer IT career traning for students?</h3>
                <p>Please refer to the IT department (Hamilton Building) to hear what courses they offer, and any short courses.</p>
            </div>
        </div>
    </section>
    </div>

    <!-- the login and sign up area -->
        <?php
            require_once('login_signup.php');
        ?>
        <script src="../landing_page/home.js"></script>

        <?php
        require_once('footer.php');
        ?>
</body>
</html>
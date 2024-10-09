<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About us</title>
    <link rel="shortcut icon" href="pictures/fake logo(1).png" type="image/x-icon">
    <link rel="stylesheet" href="aboutus.css">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="login_signup.css">
    <!-- Link to the FontAwesome library for icons -->
    <script src="https://kit.fontawesome.com/ddbf4d6190.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php
        require_once('header.php');
    ?>
    <!-- back to home page -->
    <a id="back-home" href="../landing_page/home.php">Back Home</a>
    <section class="topHeadings">
        <img src="../landing_page/pictures/System Surgeons logo.png" alt="Team Logo" class="team-logo">
        <h5>Meet Our Team, <b>System Surgeons</b></h5>
        <!-- <p>Ready to Save Your Day, One Fix at a Time.
            It takes a group of passionate operators to run the system
        </p> -->
    </section>
    

    <!-- thoko -->
    <div class="team-container">
        <div class="team-member" id="member-1">
            <img src="../landing_page/pictures/thokozile.JPG" alt="Thokozile Tshabalala">
            <h3>Thokozile Tshabalala</h3>
            <!-- <p>Position: Project Manager -->
                <br>
                Current Education: Business Science Final Year
                <br>at Rhodes University
                <br>in Information system
            </p>
        </div>


        <!-- derrick  -->
        <div class="team-member" id="member-2">
            <img src="../landing_page/pictures/derrick.JPG" alt="Derrick Abogaye">
            <h3>Derrick Aboagye</h3>
            <!-- <p>Position: Software Engineer -->
                <br>
                Current Education: Bachelor of Science Final Year
                <br>at Rhodes University
                <br>in Computer Science
            </p>
        </div>


        <!-- yuki -->
        <div class="team-member highlighted" id="member-3">
            <img src="../landing_page/pictures/yeukai.JPG" alt="Yeukai Runyowa">
            <h3>Yeukai Runyowa</h3>
            <!-- <p>Position: Software Quality Expert -->
                <br>
                Current Education: Bachelor of Science Final Year
                <br>at Rhodes University
                <br>in Computer Science
            </p>
        </div>


        <!-- amo -->
        <div class="team-member" id="member-4">
            <img src="../landing_page/pictures/amogelang.JPG" alt="Amogelang Mphela">
            <h3>Amogelang Mphela</h3>
            <!-- <p>Position: UI/UX Expert -->
                <br>
                Current Education: Bachelor of Science Final Year
                <br>at Rhodes University
                <br>in Computer Science
                <!-- <br>and User Experience -->
            </p>
        </div>


        <!-- akhona -->
        <div class="team-member" id="member-5">
            <img src="../landing_page/pictures/akhona.JPG" alt="Akhona Mdluli">
            <h3>Akhona Mdluli</h3>
            <!-- <p>Position: AI prompt expert -->
                <br>
                Current Education: Bachelor of Science Final Year
                <br>at Rhodes Univeristy
                <br>in Computer Science
                <!-- <br>and Git expert -->
            </p>
        </div>
    </div>


    <div class="buttons">
        <button onclick="prevMember()"><i class="fa-solid fa-arrow-left"></i></button>
        <button onclick="nextMember()"><i class="fa-solid fa-arrow-right"></i></button>
    </div>

    <script>
        let currentMember = 3; // Starting with Yeukai Runyowa highlighted

        function highlightMember(memberId) {
            // Remove highlight from the current member
            document.querySelector('.highlighted').classList.remove('highlighted');

            // Highlight the new member
            document.getElementById(`member-${memberId}`).classList.add('highlighted');
        }

        function prevMember() {
            currentMember = (currentMember - 1 < 1) ? 5 : currentMember - 1;
            highlightMember(currentMember);
        }

        function nextMember() {
            currentMember = (currentMember + 1 > 5) ? 1 : currentMember + 1;
            highlightMember(currentMember);
        }
    </script>

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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help | ResQue</title>
    <link rel="shortcut icon" href="pictures/fake logo(1).png" type="image/x-icon">
    <link rel="stylesheet" href="help.css">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="login_signup.css">
</head>

<body>
    <!-- the back button -->
    <a id="back-link" href="../landing_page/home.php">Back Home</a>

    <?php
    require_once('header.php');
    ?>
    <!-- the head of the page -->
    <section class="hero">
        <div class="hero-content">
            <div>
                <h5>How to navigate around</h5>
                <p>Please refer to the information below, based on your role.</p>
            </div>
        </div>
    </section>

    <!-- the login and sign up area -->
    <?php
    require_once('login_signup.php');
    ?>
    <script src="home.js"></script>

    <section class="faq">
        <div class="faq-grid">

            <!-- registered and unregistered users -->
            <div class="faq-item">
                <h3>A New User</h3>
                <img src="../landing_page/pictures/new user.png" alt="new user">
                <p>As a new user, to initiate the registration process, please click
                    on the “Sign Up” button, which is underlined in red. Subsequently,
                    complete the registration form as instructed. Upon pressing “Sign Up,”
                    the credentials you have provided will be used for your future logins.

            </div>

            <div class="faq-item">
                <h3>Login</h3>
                <img src="../landing_page/pictures/Hall Secretary login.png" alt="login">
                <p>Once you have successfully registered, proceed to the login pop-up.
                    Enter your username and password, which you created during the registration process.
                    Then, click on “Sign In.” Depending on your assigned role, you will be directed to
                    your dashboard where you can begin logging faults.
            </div>

            <div class="faq-item">
                <h3>Unregistered user</h3>
                <img src="../landing_page/pictures/unregistered user.png" alt="unregistered">
                <p>If you are not currently registered, please consult the navigation bar
                    for further information regarding the team, the system,
                    frequently asked questions (FAQ's), the registration process, policies
                    and essential university resources.
                </p>
            </div>

            <!-- hall secretary dashboard -->
            <div class="faq-item">
                <h3>Hall Secretary dashboard</h3>
                <img src="../landing_page/pictures/Hall Secretary All Tickets.png" alt="hs dashboard">
                <p>If you are a hall secretary, please refer to the sidebar within your dashboard
                    for navigation assistance. On the sidebar, you can view all tickets that
                    have been confirmed by the House Warden.
                </p>
            </div>

            <div class="faq-item">
                <h3>Hall Secretary Requisitions</h3>
                <img src="../landing_page/pictures/Hall Secretary requisitioned tickets.png" alt="hs Requisitions">
                <p>As indicated by the red lines, you will locate the requisitions for tickets,
                    as outlined in the Warden's communication on their page.
                    Upon viewing the details of each ticket, you will have access to all
                    comments, the image of the fault, and the opportunity to add your own
                    comments to the fault report for the attention of the maintenance team, students, and House Warden </p>
            </div>

            <div class="faq-item">
                <h3>Hall Secretary logout</h3>
                <img src="../landing_page/pictures/Hall Secretary logout.png" alt="hs logout">
                <p>To log out effectively, please locate the logout symbol in the bottom left
                    corner of the page. This action will ensure the security of your data and prevent
                    unauthorized access to the dashboard in case it is left unattended. </p>
            </div>


            <!-- student dashboard -->
            <div class="faq-item">
                <h3>Student Ticket Creation</h3>
                <img src="../landing_page/pictures/sutdent ticket creation.png" alt="student TC">
                <p>To create a new ticket, you should refer to the sidebar and select the
                    'Log Fault' option. This will display a form where you can submit details about the fault.
                    'FAULT CATEGORY' field requires you to specify the general nature of the fault. 'DESCRIPTION' field,
                    provide a detailed explanation of the fault. 'PRIORITY' field allows you to indicate the urgency of the issue.
                    Once completed, click 'SUBMIT'. If you wish to cancel the form details, click 'CANCEL'.
                </p>
            </div>

            <div class="faq-item">
                <h3>Student Ticket Tracking</h3>
                <img src="../landing_page/pictures/student ticket tracking.png" alt="ticket tracking">
                <p>To access ticket status, please navigate to the sidebar and select
                    the 'All Tickets' tab. Within this section, you will find a list of both
                    your personal tickets (purple underline) and those associated with your residence (red underline).
                    By clicking on the 'View Details' button, you will be able to review
                    the ticket description, ticket number, any uploaded images, and a detailed
                    history of comments, including the commenter's name and the timestamp.</p>
            </div>
            <!-- House Warden Dashbaord -->
            <div class="faq-item">
                <h3>House Warden Opened Tickets</h3>
                <img src="../landing_page/pictures/warden opened ticket.png" alt="warden opened">
                <p>Wardens can view open tickets, marked by a red circle.
                    Tickets have three options: Approve (with confirmation), Reject, or Comment.
                    Approved tickets initiate maintenance requests.
                    Comments provide context for decisions.
                    Therefore, commentary serves as a valuable record of your decision-making process and can be
                    referenced for future reference or analysis.</p>
            </div>

            <div class="faq-item">
                <h3>House Warden Confirmed Tickets</h3>
                <img src="../landing_page/pictures/warden confirmed.png" alt="confirmed">
                <p>Upon requisition by the hall secretary,
                    the VIEW DETAILS option becomes available.
                    This function allows you to access additional information
                    pertaining to the ticket and, if necessary, add further comments.</p>
            </div>


            <div class="faq-item">
                <h3>House Warden Resolved Tickets</h3>
                <img src="../landing_page/pictures/warden resolved.png" alt="resolved tickets">
                <p>For tickets that have been successfully addressed by the maintenance team,
                    you can access the Resolved Tickets section located on the
                    sidebar. Within this area, you can review the details of resolved
                    requests, including any comments made by the hall secretary, student,
                    or maintenance personnel. These comments can be identified by the
                    associated user ID.</p>
            </div>

            <!-- maintenance dashboard -->
            <div class="faq-item">
                <h3>Maintenance Requisitioned Tickets</h3>
                <img src="../landing_page/pictures/maintenance requisitioned.png" alt="requisitioned">
                <p>The Resolve Ticket button signifies that maintenance has fixed the issue.
                    It updates the status for all parties involved.</p>
            </div>

            <div class="faq-item">
                <h3>Maintenance Resolved Tickets</h3>
                <img src="../landing_page/pictures/maintenance resolved.png" alt="requisitioned">
                <p>Once a ticket is resolved, you can view details,
                    comments, and images. You can also add more comments.</p>
            </div>


            <div class="faq-item">
                <h3>Maintenance Statistics</h3>
                <img src="../landing_page/pictures/maintenance stats.png" alt="requisitioned">
                <p>on this page, it will be possible to gather a summary of 
                    tickets for every semester, organized by subcategory. 
                    Furthermore, the average response time for each category will be computed,
                     along with the tally of closed tickets and their corresponding requisition counts.</p>
            </div>
        </div>
    </section>
    <?php
    require_once('footer.php');
    ?>
</body>

</html>
# ResQue

The project involves developing a Residence Maintenance System for Rhodes University to help students and staff manage maintenance issues in university residences. The system allows students, house wardens, hall secretaries, and maintenance staff to report, track, and resolve faults in the residences.
Key Features:

    User Roles:
        Students: Can create accounts, report maintenance issues, view other reported issues, and track progress in their residence.
        House Wardens: Receive fault reports from students, confirm or close issues, and add comments. They also notify Hall Secretaries of confirmed faults.
        Hall Secretaries: Manage confirmed faults, communicate with maintenance staff, and close tickets once repairs are completed.
        Maintenance Staff: Receive requisitioned requests, perform repairs, and update the system.

    System Functions:
        Registration for different user roles (students, house wardens, hall secretaries, maintenance staff).
        Reporting and tracking maintenance faults.
        Status updates on tickets (open, confirmed, requisitioned, resolved, closed).
        Viewing fault history and comments by different users.
        Notifications for relevant users based on ticket status changes.

    Business Processes:
        Students and house wardens create tickets.
        House wardens confirm or close reports.
        Hall secretaries assign tasks to maintenance staff.
        Maintenance staff resolves issues, and hall secretaries close the tickets.

    Reports:
        Fault statistics by semester and residence.
        Fault progress tracking.
        Turnaround time stats.
        Maintenance category stats.
        Historical stats for complaint categories.

    Business Rules:
        A student can create multiple tickets, but each ticket is linked to only one student and one residence.
        Tickets can have multiple comments and photos.
        Each ticket has a single status and belongs to one fault category.

The goal is to streamline the maintenance process, allowing seamless communication between students, house wardens, hall secretaries, and maintenance staff, with clear roles and responsibilities for each.

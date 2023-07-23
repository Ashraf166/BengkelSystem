<!DOCTYPE html>
<html>
<head>
    <!-- Include CSS and JavaScript files -->
    <style>
        /* CSS code for the header */
        body {
            margin: 0;
            padding: 0;
        }

        header {
            background-color: seagreen; /* Background color of the header */
            color: #fff; /* Text color for header elements */
            position: fixed; /* Fix the header at the top of the page */
            top: 0; /* Position the header at the top */
            left: 0; /* Position the header at the left */
            right: 0; /* Position the header at the right */
            z-index: 1000; /* Ensure the header stays on top of other elements */
            padding: 10px 20px; /* Add padding to create spacing between header elements */
        }

        /* CSS code for the navigation menu */
        nav {
            display: flex; /* Use flexbox to create a horizontal menu */
            justify-content: center; /* Center the menu items horizontally */
        }

        nav ul {
            list-style: none; /* Remove the default list style from the menu */
            padding: 0;
            margin: 0;
            display: flex; /* Make the list items align horizontally */
        }

        nav li {
            margin: 0 10px; /* Add spacing between menu items */
        }

        nav a {
            text-decoration: none; /* Remove underline from anchor links */
            color: white; /* Text color for the links */
            font-weight: bold;
        }

        nav a:hover {
            color: black; /* Text color for the links on hover */
        }
    </style>
</head>
<body>
    <header>
        <!-- Logo, navigation menu, and other header elements -->
        <nav>
            <ul>
                <li><a href="logout.php">HOME</a></li>
                <li><a href="search.php">BOOK</a></li>
                <li><a href="admin_dash.php">ADMIN DASHBOARD</a></li>
                <li><a href="workshop_manage.php">WORKSHOP REGISTER</a></li>
                <li><a href="view_workshop.php">WORKSHOP UPDATE</a></li>

            </ul>
        </nav>
    </header>

</body>
</html>

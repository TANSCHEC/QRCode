<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TNSCHE Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Fixed menu button */
        .openbtn {
            font-size: 14px;
            cursor: pointer;
            background-color: #999 ;
            color: white;
            padding: 10px 10px;
            border: none;
            border-radius: 5px;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
        }

        /* Sidebar styles */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            left: -250px;
            top: 0;
            background-color: #2c3e50;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
            text-align: center;
        }

        .sidebar ul li a {
            display: inline-block;
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            width: 80%;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #2980b9;
        }

        .main-content {
            padding: 20px;
            transition: margin-left 0.3s;
        }

        /* Shift content when sidebar is open (desktop only) */
        @media screen and (min-width: 769px) {
            .main-content.shifted {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>

<!-- Menu Toggle Button -->
<button class="openbtn" onclick="toggleSidebar()">☰ Menu</button>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <ul>
        <li><a href="dashboard2.php">Dashboard Home</a></li>
        <li><a href="institutions.php">Institutions</a></li>
        <li><a href="positive_feedback.php" style="background-color: #28a745;">Positive Feedback</a></li>
        <li><a href="negative_feedback.php" style="background-color: #dc3545;">Negative Feedback</a></li>
    </ul>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main");
    sidebar.classList.toggle("active");
    main.classList.toggle("shifted");
}
</script>

</body>
</html>

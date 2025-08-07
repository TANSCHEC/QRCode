<?php
session_start();
//$_GET['logstats'];
	
	
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true ) {
    header("Location: login.php");
    exit();
}
require 'connection.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TNSCHE Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  </head>
<body>
<?php include 'header.php' ?>




<!-- Main Container -->
<div id="main" class="main-container">
  <div id="content">
    <h3>Welcome </h3>
  </div>
</div>


</body>
</html>

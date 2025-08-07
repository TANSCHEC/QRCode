<?php
session_start();
include 'inc/db_connect.php';

// Ensure user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    die("Access denied. Please login as a user.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);

    $sql = "INSERT INTO TANS.COMPLAINTS (USER_ID, CATEGORY, DESCRIPTION, STATUS, DATE_RAISED)
            VALUES (:uid, :cat, :desc, 'New', SYSDATE)";
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":uid", $user_id);
    oci_bind_by_name($stmt, ":cat", $category);
    oci_bind_by_name($stmt, ":desc", $description);

    if (oci_execute($stmt)) {
        echo "<p style='color:green;'>Complaint registered successfully!</p>";
    } else {
        $e = oci_error($stmt);
        echo "<p style='color:red;'>Error: " . htmlentities($e['message']) . "</p>";
    }
}
?>

<h2>Register a Complaint</h2>
<form method="POST" action="complaint_reg.php">
    <label>Category:</label><br>
    <input type="text" name="category" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="5" cols="40" required></textarea><br><br>

    <button type="submit">Submit Complaint</button>
</form>

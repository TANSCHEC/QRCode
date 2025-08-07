<?php
session_start();
include 'connection.php'; // your Oracle OCI connection

$error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';
     password_verify($cmp_pass,$password );
    $query = "SELECT * FROM TANS.USERS WHERE email = :email";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":email", $username);
    oci_bind_by_name($stmt, ":password", $password); // In production, use password hashing

    if (oci_execute($stmt)) {
        $user = oci_fetch_assoc($stmt);
         if ($user && password_verify($password, $user['PASSWORD'])) {
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $user['NAME'];
            $_SESSION["role"] = $user['ROLE'];
            $_SESSION["userid"] = $user['USER_ID'];

            header("Location: dashboard_layout.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $e = oci_error($stmt);
        $error = "Database error: " . $e['message'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
        }
        .login-container {
            width: 300px;
            padding: 20px;
            margin: 100px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4285F4;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            font-size: small;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Email:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>

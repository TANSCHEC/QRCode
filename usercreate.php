<?php
include 'connection.php'; // OCI DB connection

// Handle Insert/Update/Delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    @$name = trim($_POST['name']) ?? '';
    @$email = strtolower(trim($_POST['email'])) ;
    @$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    @$role = strtoupper(trim($_POST['role']));

    if ($action == 'insert') {
$id_query = "SELECT NVL(MAX(USER_ID), 0) + 1 AS NEW_ID FROM TANS.USERS";
$id_stmt = oci_parse($conn, $id_query);
oci_execute($id_stmt);
$id_row = oci_fetch_assoc($id_stmt);
$new_user_id = $id_row['NEW_ID'];	

        $query = "INSERT INTO TANS.USERS (USER_ID, NAME, EMAIL, PASSWORD, ROLE)
                  VALUES ($new_user_id, :name, :email, :password, :role)";
    } elseif ($action == 'update' && is_numeric($user_id)) {
        $query = "UPDATE TANS.USERS SET NAME = :name, EMAIL = :email, ROLE = :role 
                  WHERE USER_ID = :user_id";
    } elseif ($action == 'delete' && is_numeric($user_id)) {
        $query = "DELETE FROM TANS.USERS WHERE USER_ID = :user_id";
    }

    if (!empty($query)) {
        $stmt = oci_parse($conn, $query);
        if ($action == 'delete') {
            oci_bind_by_name($stmt, ":user_id", $user_id);
        } else {
            oci_bind_by_name($stmt, ":name", $name);
            oci_bind_by_name($stmt, ":email", $email);
            if ($action == 'insert') {
                oci_bind_by_name($stmt, ":password", $password);
            }
            oci_bind_by_name($stmt, ":role", $role);
            if ($action == 'update') {
                oci_bind_by_name($stmt, ":user_id", $user_id);
            }
        }
        oci_execute($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h4>User Management</h4>

    <form method="post" class="row g-3 mb-4">
        <input type="hidden" name="user_id" id="user_id">
        <input type="hidden" name="action" id="action" value="insert">

        <div class="col-md-3">
            <input type="text" name="name" id="name" placeholder="Name" class="form-control" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="email" id="email" placeholder="Email" class="form-control" required>
        </div>
        <div class="col-md-2">
        
        
<select name="role" id="role"  class="form-control" " required>
  <option value="">-- Select Role --</option>
  <option value="ADMIN">ADMIN</option>
  <option value="EDITOR">EDITOR</option>
  <option value="VIEWER">VIEWER</option>
  <option value="SUPERADMIN">SUPERADMIN</option>
</select>
        
        </div>
        <div class="col-md-2">
            <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Save</button>
        </div>
    </form>

    <table class="table table-bordered table-hover table-sm">
        <thead class="table-dark text-center">
            <tr>
                <th>SLNO</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM TANS.USERS ORDER BY USER_ID";
            $stid = oci_parse($conn, $query);
            oci_execute($stid);
            $slno = 1;
            while ($row = oci_fetch_assoc($stid)) :
            ?>
                <tr>
                    <td align="center"><?= $slno++ ?></td>
                    <td><?= htmlspecialchars($row['NAME']) ?></td>
                    <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                    <td><?= htmlspecialchars($row['ROLE']) ?></td>
                    <td align="center">
                        <button class="btn btn-sm btn-warning" onclick="editUser(
                            <?= $row['USER_ID'] ?>, 
                            '<?= htmlspecialchars($row['NAME']) ?>', 
                            '<?= htmlspecialchars($row['EMAIL']) ?>', 
                            '<?= htmlspecialchars($row['ROLE']) ?>'
                        )">Edit</button>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?= $row['USER_ID'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function editUser(id, name, email, role) {
            document.getElementById('user_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('role').value = role;
            document.getElementById('action').value = 'update';
            document.getElementById('password').required = false; // Not needed for update
        }
    </script>
</body>
</html>

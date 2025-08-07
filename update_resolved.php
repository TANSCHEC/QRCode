<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticket = $_POST['ticket'];

  include 'connection.php';

    $sql = "UPDATE TANS.MASTER_ASSET_DATA SET DATE_RESOLVED = SYSDATE WHERE TICKET = :ticket";
    $stid = oci_parse($conn, $sql);
    oci_bind_by_name($stid, ':ticket', $ticket);
    $result = oci_execute($stid, OCI_NO_AUTO_COMMIT);

    if ($result) {
        oci_commit($conn);
        echo "success";
    } else {
        echo "error";
    }

    oci_free_statement($stid);
    oci_close($conn);
}
?>


<style>
<style>
/* Form container */
 body {
      font-family: 'Noto Sans Tamil', sans-serif;
    }

form {
  background: #ffffff;
  max-width: 800px;
  margin: 30px auto;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Select & input fields */
select,
input[type="text"] {
  width: calc(100% - 20px);
  padding: 12px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  box-sizing: border-box;
}

input[placeholder="Question"] {
  width: 100%;
}

input:focus,
select:focus {
  border-color: #2980b9;
  outline: none;
  box-shadow: 0 0 4px rgba(41, 128, 185, 0.4);
}

/* Form buttons */
button,
input[type="submit"] {
  background-color: #2980b9;
  color: white;
  border: none;
  padding: 12px 24px;
  margin: 10px 5px 0 0;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s ease;
}

button:hover,
input[type="submit"]:hover {
  background-color: #1c5980;
}

/* Dynamic blocks */
.block {
  background: #f8f9fa;
  padding: 15px;
  border: 1px solid #dcdcdc;
  border-radius: 10px;
  margin-bottom: 15px;
}

/* Success and error messages */
p {
  text-align: center;
  font-size: 1rem;
  margin-top: 15px;
}

p[style*="color:green"] {
  color: #28a745 !important;
  font-weight: 600;
}

p[style*="color:red"] {
  color: #dc3545 !important;
  font-weight: 600;
}

/* Responsive adjustments */
@media (max-width: 600px) {
  input[type="text"],
  select {
    width: 100%;
  }

  input[placeholder="Question"] {
    width: 100%;
  }

  .block {
    padding: 10px;
  }
}

</style>
</style>

<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil&display=swap" rel="stylesheet">
<?php include 'header.php'; ?>
<?php
include 'connection.php';

if (!$conn) {
    $m = oci_error();
    trigger_error(htmlentities($m['message']), E_USER_ERROR);
}

if (isset($_POST['save'])) {
    $cname = strtoupper($_POST['cname']);
    $block = strtoupper($_POST['block']);

    // Insert new category
    $res = oci_parse($conn, "SELECT TANS.ASSET_CAT.NEXTVAL NEXTVAL FROM dual");
    oci_execute($res);
    $row = oci_fetch_array($res, OCI_ASSOC);
    $cat_id = $row ? $row['NEXTVAL'] : null;

    $stmt = oci_parse($conn, "INSERT INTO TANS.ASSET_CATEGORY (CAT_ID, CDATE, NAME, BLOCK) VALUES (:catid, SYSDATE, :cname, :block)");
    oci_bind_by_name($stmt, ':catid', $cat_id);
    oci_bind_by_name($stmt, ':cname', $cname);
    oci_bind_by_name($stmt, ':block', $block);
    oci_execute($stmt);

    // Insert parameters and questions
    foreach ($_POST['pname'] as $i => $pname) {
        if (trim($pname) === '') continue;
        $param = strtoupper($pname);
        $question = strtoupper($_POST['eng_question'][$i]);
        $question1 = $_POST['tamil_question'][$i];

        $res = oci_parse($conn, "SELECT TANS.ASSET_PARA.NEXTVAL NEXTVAL FROM dual");
        oci_execute($res);
        $row1 = oci_fetch_array($res, OCI_ASSOC);
        $param_id = $row1 ? $row1['NEXTVAL'] : null;

        $stmt = oci_parse($conn, "INSERT INTO TANS.ASSET_PARAMETER (PARAM_ID, CDATE, NAME, CAT_ID)
                                  VALUES (:pid, SYSDATE, :pname, :catid)");
        oci_bind_by_name($stmt, ':pid', $param_id);
        oci_bind_by_name($stmt, ':pname', $param);
        oci_bind_by_name($stmt, ':catid', $cat_id);
        oci_execute($stmt);

        //$res = oci_parse($conn, "SELECT TANS.ASSET_CAT.NEXTVAL NEXTVAL FROM dual");
        //oci_execute($res);
        //$row2 = oci_fetch_array($res, OCI_ASSOC);
        //$apq_id = $row2 ? $row2['NEXTVAL'] : null;

        $stmt = oci_parse($conn, "INSERT INTO ASSET_PARAMETER_QUESTION (APQ_ID, CDATE, ENG_QUESTION, TAMIL_QUESTION, PARAM_ID)
                                  VALUES (:qid, SYSDATE, :qname, :qname1, :pid)");

        oci_bind_by_name($stmt, ':qid', $cat_id);
        oci_bind_by_name($stmt, ':qname', $question);
        oci_bind_by_name($stmt, ':qname1', $question1);
        oci_bind_by_name($stmt, ':pid', $param_id);
        oci_execute($stmt);
    }

    oci_commit($conn);
    echo "<p style='color:green'>Category added successfully.</p>";
}
?>

<html lang="ta">
<head><meta charset="UTF-8"></head>
<body>

<!-- Add New Category Form Only -->
<!-- Add this in your <body> tag where the form is -->
<form method="post">
    <input style="text-transform:uppercase" placeholder="Category" type="text" name="cname" required />
    <input style="text-transform:uppercase" placeholder="Block/Building" type="text" name="block" required /><br><br>

    <!-- Parameter & Question Input Table -->
    <table id="param_question_blocks" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding:10px; border-bottom: 2px solid #ccc;">Parameter Name</th>
                <th style="padding:10px; border-bottom: 2px solid #ccc;">English Question</th>
                <th style="padding:10px; border-bottom: 2px solid #ccc;">Tamil Question</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type='text' name='pname[]' style='width:100%; text-transform:uppercase;' required></td>
                <td><input type='text' name='eng_question[]' style='width:100%; text-transform:uppercase;' required></td>
                
                
                <td><input type='text' name='tamil_question[]' lang='ta' style='width:100%;' required></td>
            </tr>
        </tbody>
    </table>

    <br>
    <button type="button" onClick="addParamWithQuestion()">Add Another Parameter + Question</button><br><br>
    <input type="submit" name="save" value="Save" />
</form>

<!-- Add this <script> at the end of the <body> -->
<script>
function addParamWithQuestion() {
    var table = document.getElementById('param_question_blocks').getElementsByTagName('tbody')[0];
    var row = table.insertRow();

    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);

    cell1.innerHTML = "<input type='text' name='pname[]' style='width:100%; text-transform:uppercase;' required>";
    cell2.innerHTML = "<input type='text' name='eng_question[]' style='width:100%; text-transform:uppercase;' required>";
    cell3.innerHTML = "<input type='text' name='tamil_question[]' lang='ta' style='width:100%;' required>";
}
</script>


</body>
</html>

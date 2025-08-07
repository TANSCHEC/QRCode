 
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
<?php include "header.php"; ?>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil&display=swap" rel="stylesheet">
<?php

include 'connection.php';
//$conn = oci_connect("tans", "tans", "tans",'AL32UTF8');

if (!$conn) {
    $m = oci_error();
    trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
echo "<br>";
echo "<br>";

// Handle Deletion
if (isset($_POST['delete']) && isset($_POST['edit_cat'])) {
    $cat_id = $_POST['edit_cat'];

    // Delete Questions
    $query = "DELETE FROM ASSET_PARAMETER_QUESTION WHERE PARAM_ID IN (
                SELECT PARAM_ID FROM ASSET_PARAMETER WHERE CAT_ID = :catid)";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':catid', $cat_id);
    oci_execute($stmt);

    // Delete Parameters
    $stmt = oci_parse($conn, "DELETE FROM ASSET_PARAMETER WHERE CAT_ID = :catid");
    oci_bind_by_name($stmt, ':catid', $cat_id);
    oci_execute($stmt);

    // Delete Category
    $stmt = oci_parse($conn, "DELETE FROM ASSET_CATEGORY WHERE CAT_ID = :catid");
    oci_bind_by_name($stmt, ':catid', $cat_id);
    oci_execute($stmt);
    oci_commit($conn);

    echo "<p style='color:red'>Category deleted successfully.</p>";
}

// Handle Save (Add or Edit)
if (isset($_POST['save'])) {
    $cname = strtoupper($_POST['cname']);
    $cat_id = $_POST['cat_id'];
	 $block = $_POST['block'];

    if ($cat_id === "") {
        // New Category
        $res = oci_parse($conn, "SELECT TANS.ASSET_CAT.NEXTVAL NEXTVAL FROM dual");
        oci_execute($res);
        $row = oci_fetch_array($res, OCI_ASSOC);
        $cat_id = $row ? $row['NEXTVAL'] : null;

        $stmt = oci_parse($conn, "INSERT INTO TANS.ASSET_CATEGORY (CAT_ID, CDATE, NAME,BLOCK) VALUES (:catid, SYSDATE, upper(:cname),upper(:block))");
        oci_bind_by_name($stmt, ':catid',$cat_id);
        oci_bind_by_name($stmt, ':cname', $cname);
		oci_bind_by_name($stmt, ':block', $block);
        oci_execute($stmt);
    } else {
        // Update Category
        $stmt = oci_parse($conn, "UPDATE TANS.ASSET_CATEGORY SET NAME = upper(:cname) WHERE CAT_ID = :catid");
        oci_bind_by_name($stmt, ':catid', $cat_id);
        oci_bind_by_name($stmt, ':cname', $cname);
        oci_execute($stmt);
    }

    // Clear old params/questions
    if ($_POST['cat_id'] !== "") {
        $stmt = oci_parse($conn, "DELETE FROM ASSET_PARAMETER_QUESTION WHERE PARAM_ID IN (
            SELECT PARAM_ID FROM ASSET_PARAMETER WHERE CAT_ID = :catid)");
        oci_bind_by_name($stmt, ':catid', $cat_id);
        oci_execute($stmt);

        $stmt = oci_parse($conn, "DELETE FROM ASSET_PARAMETER WHERE CAT_ID = :catid");
        oci_bind_by_name($stmt, ':catid', $cat_id);
        oci_execute($stmt);
    }

    // Add Parameters & Questions
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
                                  VALUES (:pid, SYSDATE, upper(:pname), :catid)");
        oci_bind_by_name($stmt, ':pid', $param_id);
        oci_bind_by_name($stmt, ':pname', $param);
        oci_bind_by_name($stmt, ':catid', $cat_id);
        oci_execute($stmt);

        $res = oci_parse($conn, "SELECT TANS.ASSET_PARAM_QUESTION_SEQ.NEXTVAL NEXTVAL FROM dual");
        @oci_execute($res);
        @$row2 = oci_fetch_array($res, OCI_ASSOC);
        $apq_id = $row2 ? $row2['NEXTVAL'] : null;

        $stmt = oci_parse($conn, "INSERT INTO ASSET_PARAMETER_QUESTION (APQ_ID, CDATE, ENG_QUESTION, TAMIL_QUESTION,  PARAM_ID)
                                  VALUES (:qid, SYSDATE, upper(:qname),:qname1, :pid)");
        oci_bind_by_name($stmt, ':qid', $cat_id);
        oci_bind_by_name($stmt, ':qname', $question);
		 oci_bind_by_name($stmt, ':qname1', $question1);
        oci_bind_by_name($stmt, ':pid', $param_id);
        oci_execute($stmt);
    }

    oci_commit($conn);
    echo "<p style='color:green'>Category saved successfully.</p>";
}

// Load category for editing
$edit_cat_id = isset($_POST['load']) && !empty($_POST['edit_cat']) ? $_POST['edit_cat'] : null;
$params_data = array();
$cat_data = array();
if ($edit_cat_id) {
    $stmt = oci_parse($conn, "SELECT NAME FROM TANS.ASSET_CATEGORY WHERE CAT_ID = :catid");
    oci_bind_by_name($stmt, ':catid', $edit_cat_id);
    oci_execute($stmt);
    $cat_data = oci_fetch_array($stmt);

    $stmt = oci_parse($conn, "SELECT P.PARAM_ID, P.NAME PNAME, Q.TAMIL_QUESTION, Q.ENG_QUESTION
                              FROM ASSET_PARAMETER P
                              JOIN ASSET_PARAMETER_QUESTION Q ON P.PARAM_ID = Q.PARAM_ID
                              WHERE P.CAT_ID = :catid");
    oci_bind_by_name($stmt, ':catid', $edit_cat_id);
    oci_execute($stmt);
    while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
        $params_data[] = $row;

    }
}
?>


<!-- Category Selection -->
<form  action="#" method="post" onsubmit="return true;" >
    <select name="edit_cat">
        <option value="">-- Select Category to Edit/Delete --</option>
        <?php
        $result = oci_parse($conn, "SELECT CAT_ID, NAME FROM TANS.ASSET_CATEGORY");
        oci_execute($result);
        while ($row = oci_fetch_array($result)) {
            $selected = ($edit_cat_id == $row['CAT_ID']) ? "selected" : "";
            echo "<option value='{$row['CAT_ID']}' $selected>{$row['NAME']}</option>";
        }
        ?>
    </select>
    <button name="load">Edit</button>
    <button name="delete" onClick="return confirm('Delete this category?')">Delete</button>
</form>


<html lang="ta">
<head>
<meta charset="UTF-8">
</head>
<body>
<!-- Add/Edit Form -->
<form method="post" action="newedit.php">
<input type="hidden" name="cat_id" value="<?= htmlspecialchars(isset($edit_cat_id) ? $edit_cat_id : '') ?>" />
  <input style="text-transform:uppercase" placeholder="Category" type="text" name="cname"
       value="<?= htmlspecialchars(isset($cat_data['NAME']) ? $cat_data['NAME'] : '') ?>" required /><br>
       <input style="text-transform:uppercase" placeholder="Block/Building" type="text" name="block"
       value="<?= htmlspecialchars(isset($cat_data['BLOCK']) ? $cat_data['BLOCK'] : '') ?>" required /><br>

   

    <div id="param_question_blocks">
        <?php
        if (!empty($params_data)) {
            foreach ($params_data as $pq) {
                echo "<div  class='block'>
                        <input  style='text-transform:uppercase;'  type='text' name='pname[]' value='{$pq['PNAME']}' placeholder='Parameter Name' required />
                        <input type='text'  name='eng_question[]' value='{$pq['ENG_QUESTION']}' placeholder='Question' required /><br><br>
 <input lang='ta' type='text' name='tamil_question[]' value='" . htmlspecialchars(isset($pq['TAMIL_QUESTION']) ? $pq['TAMIL_QUESTION'] : '') . "' placeholder='Tamil Question' required /><br><br>

                    </div>";
            }
        } else {
            echo "<div class='block'>
                    <input  style='text-transform:uppercase;' type='text' name='pname[]' placeholder='Parameter Name' required />
                    <input type='text'   name='eng_question[]' placeholder='Question' required /><br><br>
					 <input type='text'   name='tamil_question[]' placeholder='Tamil Question' required /><br><br>
                

                </div>";
        }
        ?>
    </div>
    <button type="button" onClick="addParamWithQuestion()">Add Another Parameter + Question</button><br><br>
    <input type="submit"  name="save" value="Save" />
</form>
</body>
</html>
<script>
document.querySelector('form').reset();
function addParamWithQuestion() {
    var container = document.getElementById('param_question_blocks');
    var block = document.createElement('div');
    block.className = 'block';

    var paramInput = document.createElement('input');
    paramInput.type = 'text';
    paramInput.name = 'pname[]';
    paramInput.placeholder = 'Parameter Name';
    paramInput.style = 'text-transform:uppercase';
    paramInput.required = true;

    var questionInput = document.createElement('input');
    questionInput.type = 'text';
    questionInput.name = 'eng_question[]';
    questionInput.placeholder = 'Question';
    questionInput.style = 'text-transform:uppercase;width:500px;';

    questionInput.required = true;
	
	var questionInput1 = document.createElement('input');
    questionInput1.type = 'text';
    questionInput1.name = 'tamil_question[]';
    questionInput1.placeholder = 'Tamil Question';
    questionInput1.style = 'width:500px;';
	questionInput1.lang='ta';
    questionInput1.required = true;

    block.appendChild(paramInput);
    block.appendChild(questionInput);
	block.appendChild(questionInput1);
    block.appendChild(document.createElement('br'));
    block.appendChild(document.createElement('br'));
	 
    container.appendChild(block);
}
</script>
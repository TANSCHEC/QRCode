<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title></title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f4f8;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: nowrap;
    }

    .header-img {
      height: 100px;
      width: 100px;
      object-fit: contain;
    }

    .header-text {
      text-align: center;
      flex-grow: 1;
    }

    .header-text h1 {
      margin: 0;
      font-size: 1.8rem;
    }

    .header-text h2 {
      margin: 0;
      font-size: 1.2rem;
      font-weight: normal;
    }

    .container {
      max-width: 700px;
      margin: 40px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 30px;
    }

    h2 {
      text-align: center;
      color: #FFFBF0;
    }
	
	 h3 {
      text-align: center;
      color: #2A3F55;
    }

    pre {
      text-align: center;
      font-size: 1.1em;
      color: #555;
    }

    .question {
      margin-bottom: 25px;
    }

    .question label {
      display: block;
      font-weight: 600;
      color: #34495e;
      margin-bottom: 10px;
    }

    .options {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      margin-top: 5px;
    }

    .options label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 500;
      color: #2c3e50;
      cursor: pointer;
    }

    input[type="radio"] {
      accent-color: #2980b9;
    }

    .submit-btn {
      display: block;
      margin: 30px auto 0;
      background: #2980b9;
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .submit-btn:hover {
      background: #1c5980;
    }
	

    @media (max-width: 600px) {
      header {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .header-img {
        margin: 5px;
      }

      .header-text h1 {
        font-size: 1.4rem;
      }

      .header-text h2 {
        font-size: 1rem;
      }

      .options {
        flex-direction: column;
        gap: 10px;
      }
    }
	
	
  </style>
</head>
<body>

<header>
  <img src="TN_LOGO.png" alt="Left Logo" class="header-img left-img">
  <div class="header-text">
   <h1><span lang="ta"><b>அண்ணா பல்கலைக்கழகம்</b></span></h1>
    <h2><b>ANNA UNIVERSITY<b></h2>
    
  </div>
  <img src="Annalogo.png" alt="Right Logo" class="header-img right-img">
</header>

<?php
@$myarray = json_decode($_GET["myarray"]);
@$check=$_GET["check"];
//$conn = oci_connect("tans", "tans", "tans",'AL32UTF8');
include 'connection.php';
if (!$conn) {
  $m = oci_error();
  trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
@$pname = $_POST['PNAME'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['save']=="1") {
		
  foreach ($_POST['responses'] as $qsname => $response) {
	  

	  
    //@$param = htmlspecialchars($myarray[0]);
	@$param = $pname[$qsname];
    $qsname_escaped = htmlspecialchars($qsname);
    //
    $cat = $_POST['category'];
	$catname = $_POST['CAT_NAME'];
	$block=$_POST['block'];
	//$ins= $_POST['INS_NAME'];
	//$gri= $_POST['grievance'];
	if($response=='NO')
	{
    	$res = oci_parse($conn, "SELECT TANS.TICKET.NEXTVAL NEXTVAL FROM dual");
        oci_execute($res);
        $row1 = oci_fetch_array($res, OCI_ASSOC);
        $param_id = $row1 ? $row1['NEXTVAL'] : null;
	}
	else
	{
		$param_id = '';
	
  }


      
		
    $sql = "INSERT INTO tans.master_asset_data (parameter,GNDATE,TICKET, QUESTIONS, status, category,CAT_NAME,ins_name,BLOCK) VALUES (:param,sysdate,'$param_id', :qs, '$response', '$cat','$catname','ANNA UNIVERSITY,CHENNAI','$block')";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":param", $param);

    oci_bind_by_name($stmt, ":qs", $qsname_escaped);
    $r = oci_execute($stmt, OCI_DEFAULT);
    if (!$r) {
      $e = oci_error($stmt);
      trigger_error(htmlentities($e['message']), E_USER_ERROR);
    }
  }
  oci_commit($conn);
  oci_close($conn);
echo "<script> document.getElementById('save').value='2'; </script>";
 header("Location: thankyou.php");
 exit(); 
}
else{

@$sqlstr = "SELECT ENG_QUESTION||'|'||TAMIL_QUESTION AS ENG_QUESTION ,name FROM TANS.ASSET_PARAMETER_QUESTION a,tans.ASSET_PARAMETER b where a.param_id=b.param_id and APQ_ID = '$myarray[1]'";
$stid = oci_parse($conn, $sqlstr);
oci_execute($stid);

?>

<div class="container">
  <h3>"<?php echo htmlspecialchars($myarray[0]) .'-'.htmlspecialchars($myarray[2]) ; ?>"</h3>
  <form id="myForm" method="post" action="tranupdate.php">
  
     
       
    <?php
    $index = 0;
    while (oci_fetch($stid)) {
      $qsname = oci_result($stid, 'ENG_QUESTION');
	   $psname = oci_result($stid, 'NAME');
      $fieldName = "question_$index";
    ?>
      <div class="question">
        <label><?php echo htmlspecialchars(substr($qsname,0,strrpos($qsname,'|'))); ?><br>
               <?php echo htmlspecialchars(substr($qsname,strrpos($qsname,'|')+1));?>
        </label>
        <div class="options">
          <label>
          
            <input    type="radio" name="responses[<?php echo htmlspecialchars($qsname); ?>]" value="YES"> YES/ஆம்
          </label> 
          <label>
            <input  type="radio" name="responses[<?php echo htmlspecialchars($qsname); ?>]" value="NO"> NO/இல்லை
          </label>
        </div>
      </div>
      <input type="text" name="PNAME[<?php echo htmlspecialchars($qsname); ?>]" hidden value="<?php echo $psname; ?>">


    <?php $index++; } ?>
    <input type="hidden" id="save" name="save" value="1">
    <input type="text" name="category"  hidden  value="<?php echo $myarray[1]; ?>">
    <input type="text" name="CAT_NAME"  hidden  value="<?php echo $myarray[0]; ?>">
     <input type="text" name="block"  hidden  value="<?php echo $myarray[2]; ?>">
     
      
    <button type="submit" class="submit-btn">Submit</button>
    <input type='text' name="check" value=1 hidden />
  </form>
</div>

</body>
</html>
<?php } ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("myForm");
    const submitButton = form.querySelector("button[type='submit']");

    form.addEventListener("submit", function (e) {
        const allQuestions = document.querySelectorAll('.question');
        let allAnswered = true;

        allQuestions.forEach(question => {
            const inputs = question.querySelectorAll('input[type="radio"]');
            const name = inputs[0].name;
            const checked = [...inputs].some(input => input.checked);

            if (!checked) {
                allAnswered = false;
                // Optionally highlight the question
                question.style.border = '2px solid red';
            } else {
                question.style.border = 'none'; // Reset if fixed
            }
        });

        if (!allAnswered) {
            e.preventDefault();
            alert("Please answer all the questions before submitting.");
            submitButton.disabled = false;
            submitButton.innerText = "Submit";
        } else {
            submitButton.disabled = true;
            submitButton.innerText = "Submitting...";
        }
    });
});
</script>

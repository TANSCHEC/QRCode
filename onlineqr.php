<style>
  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f9f9fb;
    color: #333;
  }

  header {
    width: 100%;
    background-color: #3f51b5;
    color: white;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .header-img {
    height: 80px;
    object-fit: contain;
  }

  .header-text {
    flex: 1;
    text-align: center;
    font-size: 1.5rem;
    font-weight: 500;
    font-family: 'Noto Sans Tamil', sans-serif;
  }

  .header-text h1 {
    margin: 0;
    font-size: 1.6rem;
    letter-spacing: 2px;
    line-height: 1.6;
  }

  .qr-section {
    margin: 40px auto;
    text-align: center;
    background: white;
    padding: 30px;
    border-radius: 12px;
    width: 80%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  }

  h4 {
    color: #3f51b5;
    font-weight: bold;
	letter-spacing:2px;
  }
  
  h5{
	  text-align:center;
  }

  table {
    margin: 20px auto;
    border-collapse: collapse;
    width: 10%;
  }

  th, td {
    padding: 20px;
    border: 2px solid #3f51b5;
    font-family: "Times New Roman", Times, serif;
    font-size: 14px;
    text-align: center;
  }

  @media print {
    body {
      background: white;
    }
    .qr-section {
      box-shadow: none;
      border: none;
    }
    header {
      display: none;
    }
  }
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include 'header.php'; ?>
<?PHP


include 'connection.php';


include '../phpqrcode/qrlib.php';
   
@$searchq = strtoupper($_GET['id']);


//$conn=oci_connect("tans","tans","tans","AL32UTF8");
//require_once 'connection.php';
//require 'connection.php';
if($searchq=='')
{
$sqlstr="select NAME,CAT_ID,BLOCK FROM tans.ASSET_CATEGORY";

$stid=oci_parse($conn,$sqlstr);
oci_execute($stid);



while(oci_fetch($stid))
{
	
$qr_title=oci_result($stid,'NAME');

$qr_catid=oci_result($stid,'CAT_ID');

$qr_block=oci_result($stid,'BLOCK');

$myarray = array($qr_title,$qr_catid,$qr_block);
$serialized = json_encode($myarray);
$data = 'myarray=' . rawurlencode($serialized);

//$qr_csv=oci_result($stid,'APPLI_NO').','.oci_result($stid,'STUDNAME').','.oci_result($stid,'CCTYPE').','.oci_result($stid,'CNAME');
//$qr_csv="http://14.139.188.102/SIS/qrupdate.php?".$data;
//QRcode::png($qr_csv, '../qrcodes/'.$qr_title.'.png');
$qr_csv=oci_result($stid,'NAME');
$qr_csv="http://192.168.0.200/tansi/tranupdate.php?".$data;
//$qr_csv="http://49.204.138.234/tansi/tranupdate.php?".$data;

QRcode::png($qr_csv, '../qrcodes/'.$qr_title.'.png');



?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

</head>
<body  style="margin-left:10px;" >
<div align="center">
<h4 align="center">ANNA UNIVERSITY<BR /> 
   QR-CODE FORMS <BR />  </h4>
     <h5>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $qr_title.'-'.$qr_block;?></h5>
   
   <table border="0">
   <tr>
   <th align="center"><img src="../qrcodes/<?php echo "$qr_title".".png";?>"  alt=""> 
   </th>
   </tr>
   </table>
<?php

echo '<div style="break-after:page"></div>';
}
}
?>
</div>

</body>

</html>
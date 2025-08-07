<?php
//$conn = oci_connect("tans", "tans", "tans");
include 'connection.php';
if (!$conn) {
  die(json_encode(["error" => oci_error()['message']]));
}

$data = [
  "total" => 0,
  "Highp" => "",
  "histogram" => []
];

// Total YES feedbacks
$q1 = "SELECT COUNT(*) AS TOTAL FROM tans.master_asset_data WHERE status = 'NO'";
$s1 = oci_parse($conn, $q1);
oci_execute($s1);
$data["total"] = oci_fetch_assoc($s1)["TOTAL"];

// Institution with highest YES feedbacks
$q2 = "SELECT PARAMETER, COUNT(*) AS COUNT FROM tans.master_asset_data WHERE status = 'NO' GROUP BY PARAMETER order by 1";
$s2 = oci_parse($conn, $q2);
oci_execute($s2);
if ($row = oci_fetch_assoc($s2)) {
  $data["Highp"] = $row["PARAMETER"];
}

// Histogram
$q3 = "SELECT PARAMETER, COUNT(*) AS COUNT FROM tans.master_asset_data WHERE status = 'NO' GROUP BY PARAMETER ORDER BY PARAMETER";
$s3 = oci_parse($conn, $q3);
oci_execute($s3);
while ($row = oci_fetch_assoc($s3)) {
  $data["histogram"][] = [
    "label" => $row["PARAMETER"],
    "value" => $row["COUNT"]
  ];
}

oci_close($conn);
header('Content-Type: application/json');
echo json_encode($data);

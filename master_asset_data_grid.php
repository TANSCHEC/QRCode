<?php
// Oracle DB Connection
//$conn = oci_connect('your_username', 'your_password', 'your_host/service_name');
include 'connection.php';

// Fetch data from the table
$sql = "SELECT CAT_NAME, PARAMETER, QUESTIONS,  STATUS, GNDATE, TICKET, BLOCK FROM TANS.MASTER_ASSET_DATA where  TICKET is not null and status='NO' AND DATE_RESOLVED IS NULL";
$stid = oci_parse($conn, $sql);
oci_execute($stid);

?>

<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>
<head>
    <title>TICKETS RAISED</title>
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
  position: relative; /* or 'fixed' if you want it to stick at the top */
  top: 0;
  left: 0;
  z-index: 1000;
}

    .header-img {
      height: 120px;
      object-fit: contain;
    }

    .header-text {
      flex: 1;
      text-align: center;
      text-align: center;
  padding-top: 8px;
  padding-bottom: 8px;
   font-size: 1.5rem; /* bigger Tamil text */
  font-weight: 500;
  letter-spacing: 1.2px;
  margin: 0;
  font-family: 'Noto Sans Tamil', sans-serif;
    }

    .header-text h1 {
       margin: 0;
  font-size: 1.89rem;
  font-weight: normal;
  letter-spacing: 4px; /* adds spacing between letters */
  padding-top: 5px;
  line-height: 1.6;
    }

    .header-text h2 {
      font-size: 1rem;
      margin: 0;
      font-weight: 400;
    }

    .openbtn {
  position: fixed;
  bottom: 20px;
  left: 20px;
  z-index: 1001;
  background-color: #007bff;
  color: white;
  padding: 12px 18px;
  border: none;
  border-radius: 50px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  font-size: 18px;
}

    .openbtn:hover {
      background-color: #303f9f;
    
    }

    .sidebar {
      height: 100%;
      width: 240px;
      position: fixed;
      left: -240px;
      top: 0;
      background-color: #2c3e50;
      overflow-y: auto;
      transition: 0.3s ease;
      padding-top: 80px;
      z-index: 1000;
    }

    .sidebar.active {
      left: 0;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      margin: 10px 0;
      text-align: center;
    }

    .sidebar ul li button {
      width: 85%;
      padding: 12px;
      font-size: 15px;
      border: none;
      border-radius: 8px;
      background-color: #34495e;
      color: white;
      cursor: pointer;
      transition: all 0.3s;
    }

    .sidebar ul li button:hover {
      background-color: #1abc9c;
      transform: scale(1.02);
    }

    .main-container {
      margin-left: 0;
      transition: margin-left 0.3s;
      padding: 30px;
    }

    .main-container.shifted {
      margin-left: 240px;
    }

    #content {
      background-color: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
      min-height: 80vh;
    }

    h3 {
      font-weight: 600;
      color: #3f51b5;
    }

    .btn-green {
      background-color: #28a745 !important;
    }

    .btn-red {
      background-color: #dc3545 !important;
    }

    .btn-orange {
      background-color: #f39c12 !important;
    }

    .openbtn-header {
  font-size: 20px;
  background: transparent;
  color: white;
  border: none;
  margin-right: 15px;
  cursor: pointer;
}

  h2 {
    text-align: center;
    color: #2A3F55;
    margin-bottom: 30px;
  }

  .scrollable-container {
    max-height: 500px;
    overflow-y: auto;
    border: 1px solid #c8e6c9;
    border-radius: 10px;
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead th {
    position: sticky;
    top: 0;
    background-color: #2A1FAA;
    color: #fff;
    padding: 12px;
    font-weight: bold;
    z-index: 2;
  }

  th, td {
    padding: 12px 14px;
    border-bottom: 1px solid #e0e0e0;
    text-align: left;
  }

  tbody tr:hover {
    background-color: #f1f8e9;
  }

  .details {
    display: none;
    background-color: #f9fbe7;
  }

  .expand-btn {
    color: #1e88e5;
    cursor: pointer;
    font-weight: bold;
  }

  .expand-btn:hover {
    text-decoration: underline;
  }

  td[colspan="7"] {
    padding: 16px;
    font-size: 0.95rem;
    color: #555;
    border-top: 1px solid #c5e1a5;
  }

  @media (max-width: 768px) {
    th, td {
      padding: 10px;
      font-size: 0.9rem;
    }

    .scrollable-container {
      max-height: 400px;
    }
  }
</style>

    <script>
        function toggleDetails(index) {
            const row = document.getElementById('details-' + index);
            if (row.style.display === 'table-row') {
                row.style.display = 'none';
            } else {
                row.style.display = 'table-row';
            }
        }
    </script>
</head>
<body>

<h2>TICKETS RAISED</h2>

<div class="scrollable-container">
<form id="grd">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Block</th>
                <th>Parameter</th>
                <th>Question</th>
                <th>Status</th>
                <th>Ticket</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        while ($row = oci_fetch_assoc($stid)) {
            $catName = htmlspecialchars($row['CAT_NAME']);
            $param = htmlspecialchars($row['PARAMETER']);
            $question = htmlspecialchars($row['QUESTIONS']);
            $status = htmlspecialchars($row['STATUS']);
            $ticket = htmlspecialchars($row['TICKET']);
            $gndate = htmlspecialchars($row['GNDATE']);
            $block = htmlspecialchars($row['BLOCK']);
			

            echo "<tr>";
            echo "<td>{$i}</td>";
            echo "<td>{$catName}</td>";
			echo  "<td>{$block}</td>";
            echo "<td>{$param}</td>";
            echo "<td>".substr($question,0,strrpos($question,'|')). "<br>" . substr($question,strrpos($question,'|')+1). "</td>";
            echo "<td>{$status}</td>";
            echo "<td>{$ticket}</td>";
			echo "<td><span class='expand-btn' onclick=\"updateGNDate('{$ticket}', {$i})\">RESOLVE</span></td>";
            echo "</tr>";

            echo "<tr id='details-{$i}' class='details'>";
echo "<td colspan='7'>
        <strong>GNDATE:</strong> <span id='gndate-{$i}'>{$gndate}</span><br>
        
      </td>";
echo "</tr>";

            $i++;
        }
		
		if ($i==1)
		{
			$question=htmlspecialchars(' No Grievances Recieved...');
			
            echo "<td>".substr($question,0,strrpos($question,'|')). "<br>" . substr($question,strrpos($question,'|')+1). "</td>";
          
		}
        ?>
        </tbody>
    </table>
    </form>
</div>

</body>
</html>

<?php
oci_free_statement($stid);
oci_close($conn);
?>


<script>
function updateGNDate(ticket, index) {
    fetch("update_resolved.php", {
        method: "POST",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: "ticket=" + encodeURIComponent(ticket)
    })
    .then(response => response.text())
    .then(result => {
        if (result.trim() === "success") {
            alert("Ticket Resolved");
            document.getElementById("gndate-" + index).innerText = new Date().toLocaleString();
			document.getElementById("grd").submit();
        } else {
            alert("Unable to Resolve.");
        }
    });
    toggleDetails(index);
}

</script>

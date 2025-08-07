<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TNSCHE Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
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
  </style>
</head>
<body>
<header>
  <img src="TN_LOGO.png" alt="Left Logo" class="header-img">
  <div class="header-text">
    <h1><span lang="ta"><b>அண்ணா பல்கலைக்கழகம்</b></span></h1>
<h1><b>ANNA UNIVERSITY</b></h1>
  </div>
  <img src="Annalogo.png" alt="Right Logo" class="header-img">
</header>

<!-- Sidebar Toggle -->
<button class="openbtn" onclick="toggleSidebar()">☰ Menu</button>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
  <ul> 
    <li><button   type="button" onclick="window.location.href='dashboard2.php';">Dashboard</button></li>
    <li><button onclick="window.location.href='newadd.php';">Add New Parameter</button></li>
  <li><button onclick="window.location.href='newedit.php';">Edit Parameter</button></li>
      <li><button  onclick="window.location.href='onlineqr.php';">QR-Code</button></li> 
   <li><button  onclick="window.location.href='positive_feedback.php';">Positive Feedback</button></li>
    <li><button  onclick="window.location.href='negative_feedback.php';">Negative Feedback</button></li>
    <li><button  onclick="window.location.href='master_asset_data_grid.php';">Complaints</button></li>
  </ul>
</div>

<script>

document.getElementById('loadTest1').addEventListener('click', function(e) {
  e.preventDefault(); // Prevent navigation
  loadPage('test1.php');
});
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const main = document.getElementById("main");
  sidebar.classList.toggle("active");
  main.classList.toggle("shifted");
}

function loadPage(page) {
  fetch(page)
    .then(response => {
      if (!response.ok) throw new Error("Network error");
      return response.text();
    })
    .then(html => {
      const content = document.getElementById("content");
      content.innerHTML = html;

      // Re-execute scripts if needed
      const scripts = content.querySelectorAll("script");
      scripts.forEach(script => {
        const newScript = document.createElement("script");
        newScript.text = script.textContent;
     //document.body.appendChild(newScript).parentNode.removeChild(newScript);
	   document.body.appendChild(newScript).remove();
		
      });
    })
    .catch(err => {
      document.getElementById("content").innerHTML = "<p>Error loading content.</p>";
      console.error(err);
    });
}

</script>

</body>
</html>

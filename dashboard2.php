<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>
<head>
    <meta charset="UTF-8">
    <title>Feedback Dashboard - TNSCHE</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            margin-bottom: 40px;
        }

        .summary {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .card {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin: 10px;
            text-align: center;
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 1.2em;
        }
		
		.categoryChart {
  width: 800px;
  height: 400px;
  position: relative;
  margin: 0 auto;
}

 .statusPieChart {
 width: 800px;
  height: 200px;
  position: relative;
  margin: 0 auto;
}

        canvas {
            max-width: 700px;
			max-height:700px;
            height:200px;
            margin: 0 auto 40px;
            display: block;
        }
		.sidebar a.active {
    font-weight: bold;
    background: #ddd;
    border-radius: 5px;
}


#pagination-controls button {
    margin: 0 5px;
    padding: 5px 10px;
    border: none;
    background: #007BFF;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}

#pagination-controls button:disabled {
    background: #aaa;
    cursor: not-allowed;
}

    </style>
</head>
<body>

<h2>Feedback Dashboard</h2>


<div class="summary">
    <div class="card">
        <h3>Total Feedbacks</h3>
        <p id="total-count">0</p>
    </div>
    <div style="color:#3F9;" class="card">
        <h3>Positive (YES)</h3>
        <p id="yes-count">0</p>
    </div>
    <div style="color:#F36;"   class="card">
        <h3>Negative (NO)</h3>
        <p id="no-count">0</p>
    </div>
    
   <div style="color:#F36;"   class="card">
        <h3>Resolved</h3>
        <p id="re-count">0</p>
    </div> 
</div>

<div class="categoryChart">
<canvas id="categoryChart"></canvas>
</div>
<div class="statusPieChart">
<canvas id="statusPieChart"></canvas>
</div>
<h2>All Feedback Data</h2>


<div style="overflow-x: auto;">
    <table id="feedback-table" border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse: collapse; background:#fff; margin-top: 20px;">
    
        <thead id="feedback-table-head">
            <!-- Will be populated dynamically -->
        </thead>
        <tbody id="feedback-table-body">
            <!-- Will be populated dynamically -->
        </tbody>
    </table>
    <div id="pagination-controls" style="text-align:center; margin-top: 20px;"></div>

</div>




<script>

fetch('get_dashboard_data.php')
  .then(res => res.json())
  .then(data => {
      document.getElementById("total-count").textContent = data.total;
      document.getElementById("re-count").textContent = data.retotal;
      document.getElementById("yes-count").textContent = data.yes;
      document.getElementById("no-count").textContent = data.no;

      const ctx1 = document.getElementById('categoryChart').getContext('2d');
      new Chart(ctx1, {
          type: 'bar',
          data: {
              labels: data.category_distribution.map(d => d.label),
              datasets: [{
                  label: 'Feedback Count per Category',
                  data: data.category_distribution.map(d => d.value),
                  backgroundColor: 'rgba(54, 162, 235, 0.6)'
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: { display: false }
              },
              scales: {
                  y: { beginAtZero: true }
              }
          }
      });

      const ctx2 = document.getElementById('statusPieChart').getContext('2d');
      new Chart(ctx2, {
          type: 'pie',
          data: {
              labels: ['YES', 'NO'],
              datasets: [{
                  data: [data.yes, data.no],
                  backgroundColor: ['#4caf50', '#f44336']
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: { position: 'top' }
              }
          }
      });

      // Pagination
      const tableHead = document.getElementById('feedback-table-head');
      const tableBody = document.getElementById('feedback-table-body');
      const feedbackData = data.all_feedback || [];
      const rowsPerPage = 10;
      let currentPage = 1;
      let totalPages = Math.ceil(feedbackData.length / rowsPerPage);

      function renderTable(page) {
          const start = (page - 1) * rowsPerPage;
          const end = start + rowsPerPage;
          const pageData = feedbackData.slice(start, end);

          // Generate headers
          if (feedbackData.length > 0) {
              const headers = Object.keys(feedbackData[0]);
              let headRow = "<tr>";
              headers.forEach(key => {
                  headRow += `<th>${key}</th>`;
              });
              headRow += "</tr>";
              tableHead.innerHTML = headRow;

              let bodyHTML = "";
              pageData.forEach(row => {
                  bodyHTML += "<tr>";
                  headers.forEach(key => {
                      bodyHTML += `<td>${row[key] ?? ''}</td>`;
                  });
                  bodyHTML += "</tr>";
              });
              tableBody.innerHTML = bodyHTML;
          } else {
              tableBody.innerHTML = "<tr><td colspan='100%'>No data available.</td></tr>";
          }

          renderPaginationControls();
      }

      function renderPaginationControls() {
          let paginationHTML = "";
          if (totalPages > 1) {
              paginationHTML += `<button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>Previous</button>`;
              for (let i = 1; i <= totalPages; i++) {
                  paginationHTML += `<button onclick="changePage(${i})" ${i === currentPage ? 'style="font-weight:bold;"' : ''}>${i}</button>`;
              }
              paginationHTML += `<button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>Next</button>`;
          }
          document.getElementById('pagination-controls').innerHTML = paginationHTML;
      }

      window.changePage = function(page) {
          if (page >= 1 && page <= totalPages) {
              currentPage = page;
              renderTable(currentPage);
          }
      }

      renderTable(currentPage);
  });
  
  

  
</script>



</body>
</html>

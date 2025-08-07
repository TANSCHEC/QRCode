<!-- positive_feedback.php -->
<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>
<head>
  <meta charset="UTF-8">
  <title>Positive Feedbacks - TNSCHE</title>
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
    font-size: 1.8rem;
    color: #3f51b5;
    margin-bottom: 30px;
    font-weight: 600;
  }

  .summary {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 50px; /* Increased spacing between cards */
  margin-bottom: 40px;
}

.card {
  background: #2AFFAA;
  padding: 16px 24px; /* Reduced height */
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  text-align: center;
  min-width: 300px;
  flex: 1;
  max-width: 400px; /* Increased width */
  height: auto; /* Allow flexible height */
}

  .card h3 {
    font-size: 1.1rem;
    color: #2e7d32;
    margin-bottom: 10px;
    font-weight: 500;
  }

  .card p {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    color: #1b5e20;
  }

  .chart-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 40px;
  }

  canvas {
    max-width: 900px;
    width: 100% !important;
    height: auto !important;
  }

  @media (max-width: 768px) {
    .summary {
      flex-direction: column;
      align-items: center;
    }
  }
</style>

</head>
<body>
<h2>Positive Feedback </h2>
<div class="summary">
  <div class="card">
    <h3>Total YES Feedbacks</h3>
    <p id="total-yes">0</p>
  </div>
  <div class="card">
    <h3>Best Parameter</h3>
    <p id="BEST PARAMETER">-</p>
  </div>
</div>

<div class="chart-container">
  <canvas id="yesHistogram"></canvas>
</div>

<script>
fetch('get_positive_feedback.php')
  .then(res => res.json())
  .then(data => {
    document.getElementById('total-yes').textContent = data.total;
    document.getElementById('BEST PARAMETER').textContent = data.highp;

    new Chart(document.getElementById('yesHistogram').getContext('2d'), {
      type: 'bar',
      data: {
        labels: data.histogram.map(item => item.label),
        datasets: [{
          label: 'Positive Feedback Count',
          data: data.histogram.map(item => item.value),
          backgroundColor: '#4caf50'
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  });
</script>
</body>
</html>

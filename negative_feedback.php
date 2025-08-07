<!-- negative_feedback.php -->
<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>
<head>
  <meta charset="UTF-8">
  <title>Negative Feedbacks - TNSCHE</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
  <style>
  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f9f9fb;
    color: #333;
    padding: 30px;
  }

  h2 {
    text-align: center;
    font-size: 1.8rem;
    color: #e53935;
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
  background: #fdecea;
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
    color: #c62828;
    margin-bottom: 10px;
    font-weight: 500;
  }

  .card p {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    color: #b71c1c;
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

<h2>Negative Feedback Summary</h2>
<div class="summary">
  <div class="card">
    <h3>Total no Feedbacks</h3>
    <p id="total-no">0</p>
  </div>
  <div class="card">
    <h3>High Grievance Parameter</h3>
    <p id="High-Grievance-Parameter">-</p>
  </div>
</div>

<div class="chart-container">
  <canvas id="noHistogram"></canvas>
</div>

<script>
fetch('get_negative_feedback.php')

  .then(res => res.json())
  .then(data => {
	 
    document.getElementById('total-no').textContent = data.total;
	
    document.getElementById("High-Grievance-Parameter").textContent = data.Highp;

    new Chart(document.getElementById('noHistogram').getContext('2d'), {
      type: 'bar',
      data: {
        labels: data.histogram.map(item => item.label),
        datasets: [{
          label: 'Negative Feedback Count',
          data: data.histogram.map(item => item.value),
          backgroundColor:'#F88175'
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

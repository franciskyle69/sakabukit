<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports</title>
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="styles/styles.css">
  <link rel="icon" type="image/png" href="../assets/images/logo.png">
</head>

<body>
  <?php include '../includes/navbar.php'; ?>


  <div class="container mt-4">
    <div class="content">
      <h1>Welcome to the Reports</h1>
      <p>Reports section</p>

      <!-- Placeholder Chart Section -->
      <div class="chart-placeholder text-center mb-4">
        <img src="../assets/images/Chart.png" alt="Sales/Statistics Chart" class="img-fluid"
          style="width: 30%; border-radius: 30px;">
      </div>

      <!-- Sales Report Table -->
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Category</th>
            <th scope="col">Product</th>
            <th scope="col">Sales</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Submariner</td>
            <td>Submariner Black dial</td>
            <td>₱13,123,210</td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>GMT-Master</td>
            <td>GMT-Master II yellow gold</td>
            <td>₱10,303,220</td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <td>GMT-Master</td>
            <td>GMT-Master II "batman"</td>
            <td>₱9,801,000</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>



</body>

</html>
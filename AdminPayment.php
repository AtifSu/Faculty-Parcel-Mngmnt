<?php
include('php/connect.php');
session_start();

// Handle deletion of appointment
if (isset($_POST['delete_appointment']) && isset($_POST['StdID'])) {
  $StdID = mysqli_real_escape_string($connect, $_POST['StdID']);
  $delete_sql = "DELETE FROM Appointment WHERE StdID = ?";
  $stmt = mysqli_prepare($connect, $delete_sql);
  mysqli_stmt_bind_param($stmt, "s", $StdID);

  if (mysqli_stmt_execute($stmt)) {
    $delete_message = "Appointment deleted successfully.";
  } else {
    $delete_message = "Failed to delete appointment: " . mysqli_error($connect);
  }

  mysqli_stmt_close($stmt);
}

// Handle search functionality
if (isset($_POST['search'])) {
  $searchTerm = mysqli_real_escape_string($connect, $_POST['search']);
  $sql = "SELECT StdID, AppointmentDate, AppointmentTime FROM Appointment WHERE StdID LIKE '%$searchTerm%' OR AppointmentDate LIKE '%$searchTerm%'";
  $result = mysqli_query($connect, $sql);
} else {
  $sql = "SELECT StdID, AppointmentDate, AppointmentTime FROM Appointment";
  $result = mysqli_query($connect, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Payment</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="custom.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="js/bootstrap.bundle.js"></script>
</head>

<body>
  <!-- Navigation bar -->
  <nav class="navbar navbar-expand-lg bg-body-secondary">
    <div class="container-fluid">
      <a class="navbar-brand" href="AdminHome.php">
        <img src="img/logo.png" alt="logo" width="95" height="60">
        Faculty Parcel Management
      </a>
      <ul class="nav justify-content-end">
        <!-- Manage Payment -->
        <li class="nav-item">
          <div class="h1">
            <a class="nav-link bi bi-credit-card active" aria-current="page" href="AdminPayment.php"></a>
          </div>
        </li>
        <!-- Parcels -->
        <li class="nav-item">
          <div class="h1">
            <a class="nav-link bi bi-archive active" aria-current="page" href="ManageParcels.php"></a>
          </div>
        </li>
        <!-- Toast Notification -->
        <li class="nav-item">
          <div class="h1">
            <button type="button" class="nav-link bi bi-bell" id="liveToastBtn"></button>
          </div>
        </li>
        <!-- Profile -->
        <li class="nav-item">
          <div class="h1">
            <a class="nav-link bi bi-person" href="AdminProfile.php"></a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <div class="row justify-content-center">
    <div class="col-md-4 ax">
      <div class="h2 mt-3"> Payment and Appointment </div>
      <form action="payment.php" method="post" enctype="multipart/form-data">
        <div class="card" style="width: 18rem;">
          <img src="payment/<?php echo isset($newImageName) ? $newImageName : ''; ?>" name="PaymentImg" alt="PaymentImage">
          <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png" required>
          <div class="card-body">
            <div class="input-group input-group-sm mb-3">
              <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" name="PaymentNumber" placeholder="Account Number">
            </div>
            <div class="input-group input-group-sm mb-3">
              <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" name="PaymentName" placeholder="Bank Name">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
      </form>
    </div>
  </div>

  <div class="col-4 md mt-3">
    <div class="card mt-4 ms-5 p-3 w-100">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="mb-3 row">
          <h2 class="text-center h2">Manage Appointment</h2>
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="input-group">
              <input type="text" class="form-control" name="search" placeholder="Search" aria-label="Search" aria-describedby="search-btn">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" id="search-btn">Search</button>
              </div>
            </div>
          </form>
          <?php
          if (isset($delete_message)) {
            echo "<p>$delete_message</p>";
            echo "<script>alert('Appointment Deleted!');</script>";
          }
          if (isset($result) && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <div class="card my-3">
                <div class="card-body">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                    <input type="hidden" name="StdID" value="<?php echo $row['StdID']; ?>">
                    <button type="submit" class="btn-close float-end" name="delete_appointment"></button>
                    <h5 class="card-title">Appointment Details</h5>
                    <p class="card-text">Matrics ID: <strong><?php echo $row["StdID"]; ?></strong></p>
                    <p class="card-text">Appointment Date: <strong><?php echo $row["AppointmentDate"]; ?></strong></p>
                    <p class="card-text">Appointment Time: <strong><?php echo $row["AppointmentTime"]; ?></strong></p>
                  </form>
                </div>
              </div>
          <?php
            }
          } else {
            echo "<p>No appointments found.</p>";
          }
          ?>
      </form>
    </div>
  </div>
  </div>

  </div>
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="">
        <strong class="me-auto">Faculty Parcel Management</strong>
        <!-- <small class="text-body-secondary">just now</small> -->
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        Parcels Have Arrived
      </div>
    </div>

    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="">
        <strong class="me-auto">Faculty Parcel Management</strong>
        <!-- <small class="text-body-secondary">2 seconds ago</small> -->
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        Appointment booked
      </div>
    </div>
  </div>

  <script>
    var toastTrigger = document.getElementById('liveToastBtn');
    var toasts = document.querySelectorAll('.toast');

    if (toastTrigger) {
      toastTrigger.addEventListener('click', function() {

        toasts.forEach(function(toast, index) {
          var delay = index * 1000; // 1000 milliseconds = 1 seconds

          setTimeout(function() {
            var bsToast = new bootstrap.Toast(toast);
            bsToast.show();
          }, delay);
        });
      });
    }
  </script>

</body>
</html>
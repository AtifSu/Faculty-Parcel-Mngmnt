<?php
include('php/connect.php');
session_start();

$paymentDetails = [];
$sql = "SELECT PaymentBank, PaymentNumber, PaymentImg FROM Payment ORDER BY PaymentID DESC LIMIT 1";
$result = mysqli_query($connect, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $paymentDetails = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Error: No results found');</script>";
}

mysqli_close($connect);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="custom.css" />
  <link rel="icon" href="img/logo.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="js/bootstrap.bundle.js"></script>

  <title>Payment</title>
</head>

<body>
  <!-- Navigation bar -->
  <nav class="navbar navbar-expand-lg bg-body-secondary" style="box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
    <div class="container-fluid">
      <a class="navbar-brand" href="trackPackage.php">
        <img src="img/logo.png" alt="logo" width="95" height="60">
        Faculty Parcel Management
      </a>
      <ul class="nav justify-content-end">
        <li class="nav-item">
          <div class="h1">
            <a class="nav-link bi bi-credit-card active" aria-current="page" href="StdPayment.php"></a>
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
            <a class="nav-link bi bi-person" href="StdProfile.php"></a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Input Text -->
  <div class="row justify-content-center">
    <div class="col-md-4 ax">
      <div class="h2 mt-5 "> Payment and Appointment </div>
      <div class="card" style="width: 18rem;">
        <?php if (!empty($paymentDetails['PaymentImg'])): ?>
          <img src="payment/<?php echo htmlspecialchars($paymentDetails['PaymentImg']); ?>" class="card-img-top" alt="Bank Image">
        <?php else: ?>
          <img src="img/default.png" class="card-img-top" alt="Default Bank Image">
        <?php endif; ?>
        <div class="card-body">
          <p class="card-text"><strong>Account Number: </strong><?php echo htmlspecialchars($paymentDetails['PaymentNumber']); ?></p>
          <p class="card-text"><strong>Bank Name: </strong><?php echo htmlspecialchars($paymentDetails['PaymentBank']); ?></p>
        </div>
      </div>
    </div>

    <div class="col-4 md mt-5">
      <div class="card mt-4 ms-5 p-3 w-100">
        <form action="appointment.php" method="post">
          <div class="mb-3 row">
            <h2 class="text-center">Appointment</h2>
            <label type="text" class="form-label">Date</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="AppointmentDate" name="AppointmentDate" required>
            </div>
          </div>
          <div class="mb-3 row">
            <label type="text" class="form-label">Time</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="AppointmentTime" name="AppointmentTime" required>
            </div>
          </div>
          <div class="mb-3 row">
            <label type="text" class="form-label">Matrics ID</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="StdID" name="StdID" required>
            </div>
          </div>
          <div class="mb-3 row">
            <label type="text" class="form-label">Tracking Number</label>
            <div class="input-group mb-3">
              <input type="text" class="form-control" id="ParcelTrackingNum" name="ParcelTrackingNum" required>
              <div class="h1 float-end">
                <button type="submit" class="btn btn-primary">
                  <span class="first-color bi bi-arrow-right-circle"></span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Toast Notifications -->
  <div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-fixed top-0 end-0 p-3">
      <?php
      if (isset($_SESSION['appointment_success'])) {
          echo '<div class="toast align-items-center text-bg border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                  <div class="toast-header">
                      <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="">
                      <strong class="me-auto">Faculty Parcel Management</strong>
                      <button type="button" class="btn-close btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                  <div class="toast-body">' . $_SESSION['appointment_success'] . '</div>
              </div>';
          unset($_SESSION['appointment_success']);
      }

      if (isset($_SESSION['appointment_error'])) {
          echo '<div class="toast align-items-center text-bg border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                  <div class="toast-header">
                      <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="">
                      <strong class="me-auto">Faculty Parcel Management</strong>
                      <button type="button" class="btn-close btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                  <div class="toast-body">' . $_SESSION['appointment_error'] . '</div>
              </div>';
          unset($_SESSION['appointment_error']);
      }
      ?>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var toasts = document.querySelectorAll('.toast');
      toasts.forEach(function(toast) {
        var bsToast = new bootstrap.Toast(toast);
        //bsToast.show();
      });
    });

    var toastTrigger = document.getElementById('liveToastBtn');
    if (toastTrigger) {
      toastTrigger.addEventListener('click', function() {
        var toasts = document.querySelectorAll('.toast');
        toasts.forEach(function(toast, index) {
          var delay = index * 1000; // 1000 milliseconds = 1 second
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

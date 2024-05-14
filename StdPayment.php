<?php
include('php/connect.php');
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="js/bootstrap.bundle.js"></script>

  <title>Payment</title>
</head>

<body>
  <!-- Navigation bar -->
  <nav class="navbar navbar-expand-lg bg-body-secondary">
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
        <img src="img/bank.png" class="card-img-top" alt="...">
        <div class="card-body">
          <p class="card-text">Account Number.</p>
          <p class="card-text">Bank Name.</p>
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

          <div class="mb-3 row ">
            <label type="text" class="form-label">Time</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="AppointmentTime" name="AppointmentTime" required>
            </div>
          </div>

          <div class="mb-3 row ">
            <label type="text" class="form-label">Matrics ID</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="StdID" name="StdID" required>
            </div>
          </div>

          <div class="mb-3 row ">
            <label type="text" class="form-label">Tracking Number</label>
            <div class="input-group mb-3">
              <input type="text" class="form-control" id="ParcelTrackingNum" name="ParcelTrackingNum" required>
              <div class="h1 float-end">
                <button type="submit" class="btn btn-primary">
                  <span class="bi bi-arrow-right-circle-fill"></span>
                </button>
              </div>
            </div>
          </div>
          <!--
              <a class="icon-link icon-link-hover" href="#">
                Mark as done
                <a type="" class="bi bi-check-circle mx-2">
              </a>
                -->
      </div>
      </form>
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
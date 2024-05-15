<?php
include('php/connect.php');

$parcelStatus = "";
$parcelArriveDate = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $trackingNumber = isset($_POST['ParcelTrackingNum']) ? $_POST['ParcelTrackingNum'] : '';

  if (empty($trackingNumber)) {
    echo 'Tracking number is required.';
  } else {
    $trackingNumber = mysqli_real_escape_string($connect, $trackingNumber);
    $query = "SELECT ParcelStatus, ParcelArriveDate FROM Parcel WHERE ParcelTrackingNum = '$trackingNumber'";
    $result = mysqli_query($connect, $query);

    if ($result) {
      if (mysqli_num_rows($result) > 0) {
        $parcel = mysqli_fetch_assoc($result);
        $parcelStatus = htmlspecialchars($parcel['ParcelStatus']);
        $parcelArriveDate = htmlspecialchars($parcel['ParcelArriveDate']);
      } else {
        echo 'No parcel found with the provided tracking number.';
      }
    } else {
      echo 'Database error: ' . mysqli_error($connect);
    }
  }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ParcelTrackingNum'])) {
  echo 'Invalid request method.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/bootstrap.css" />
  <link rel="stylesheet" href="custom.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <script src="js/bootstrap.bundle.js"></script>
  <title>Track package</title>
</head>

<body>
  <!-- Navigation bar -->
  <nav class="navbar navbar-expand-lg bg-body-secondary">
    <div class="container-fluid">
      <a class="navbar-brand" href="trackPackage.php">
        <img src="img/logo.png" alt="logo" width="95" height="60" />
        Faculty Parcel Management
      </a>
      <ul class="nav justify-content-end">
        <li class="nav-item">
          <div class="h1">
            <a class="nav-link bi bi-credit-card active" aria-current="page" href="StdPayment.php"></a>
          </div>
        </li>
        <li class="nav-item">
          <div class="h1">
            <button type="button" class="nav-link bi bi-bell" id="liveToastBtn"></button>
          </div>
        </li>
        <li class="nav-item">
          <div class="h1">
            <a class="nav-link bi bi-person" href="StdProfile.php"></a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <form id="trackingForm" target="" action="" method="post">
    <div class="form-group mx-auto">
      <div class="row no-gutters">
        <div class="col-md-8 col-lg-6 mx-auto mt-5">
          <h1 class="sec-color mx-auto text-center">Track your delivery</h1>
          <div class="input-group mb-3">
            <input class="form-control" type="text" id="trackingNumber" placeholder="Enter your tracking number" name="ParcelTrackingNum" aria-label=".form-control-lg example" />
            <button type="submit" class="first-color btn btn-primary bi bi-arrow-right-circle-fill"></button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div id="trackingInfo1" class="sec-color alert alert-light w-75 mx-auto" role="alert">
    <strong>Parcel Status:</strong> <?php echo $parcelStatus; ?>
  </div>

  <div id="trackingInfo2" class="sec-color alert alert-light w-75 mx-auto" role="alert">
    <strong>Parcel Arrival Date:</strong> <?php echo $parcelArriveDate; ?>
  </div>

  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="" />
        <strong class="me-auto">Faculty Parcel Management</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">Parcels Have Arrived</div>
    </div>
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="" />
        <strong class="me-auto">Faculty Parcel Management</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">Appointment booked</div>
    </div>
  </div>

  <script>
    // Toast Notification Logic
    var toastTrigger = document.getElementById("liveToastBtn");
    var toasts = document.querySelectorAll(".toast");

    if (toastTrigger) {
      toastTrigger.addEventListener("click", function() {
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
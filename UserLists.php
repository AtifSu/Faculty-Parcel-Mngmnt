<?php
include('php/connect.php');
session_start();

if (isset($_GET['id'])) {
  $StdID = $_GET['id'];

  $sql = "SELECT StdName, StdID, StdEmail, StdPass FROM Student WHERE StdID = ?";

  $stmt = mysqli_prepare($connect, $sql);

  mysqli_stmt_bind_param($stmt, "s", $StdID);

  mysqli_stmt_execute($stmt);

  mysqli_stmt_bind_result($stmt, $StdName, $StdID, $StdEmail, $StdPass);

  mysqli_stmt_fetch($stmt);

  mysqli_stmt_close($stmt);
} else {
  header("Location: error.php");
  exit;
}

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Lists</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="custom.css">
  <script src="js/bootstrap.bundle.js"></script>
</head>

<body>
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

  <br>
  <br>
  <br>
  <div class="container text-center">
    <div class="row justify-content-md-center">
      <div class="col col-lg-2">

        <div class="card float-end" style="width: 18rem;">
          <img src="uploads/<?php echo isset($newImageName) ? $newImageName : ''; ?>" alt="Profile Image">
        </div>
      </div>

      <div class="col-md-auto">
        <p><strong>Name:</strong> <?php echo $StdName; ?></span></p>
        <p><strong>Matrics ID:</strong> <?php echo $StdID; ?></span></p>
        <p><strong>Email:</strong> <?php echo $StdEmail; ?></span></p>
        <p>
          <input type="password" id="passwordField" hidden>
        </p>
        <p>
          <input type="text" id="emailField" hidden>
        </p>
        <form action="php/edit.php" method="post">
          <a class="icon-link" href="#" onclick="showPasswordField()"> <svg class="bi" aria-hidden="true">
              <use xlink:href="#box-seam"></use>
            </svg>
            Change Password
          </a>
          <a class="icon-link" href="#" onclick="showEmailField()"> <svg class="bi" aria-hidden="true">
              <use xlink:href="#box-seam"></use>
            </svg>
            Change Email
          </a>
          <br>
          <input class="btn btn-primary" type="submit" name="update-StdPass" value="Reset">

          <input class="btn btn-primary float-end" type="submit" name="update-StdEmail" value="Delete">
        </form>
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
    //Hidden toggle 
    function showPasswordField() {
      document.getElementById("passwordField").hidden = false;
    }

    function showEmailField() {
      document.getElementById("emailField").hidden = false;
    }
  </script>
</body>

</html>
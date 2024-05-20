<?php
include('php/connect.php');
session_start();

if (isset($_GET['id'])) {
  $StdID = $_GET['id'];

  $sql = "SELECT StdName, StdID, StdEmail, StdPass,StdImg FROM Student WHERE StdID = ?";
  $stmt = mysqli_prepare($connect, $sql);
  mysqli_stmt_bind_param($stmt, "s", $StdID);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $StdName, $StdID, $StdEmail, $StdPass, $StdImg);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
} else {
  header("Location: error.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['delete'])) {
    // Delete user profile
    $deleteSql = "DELETE FROM Student WHERE StdID = ?";
    $deleteStmt = mysqli_prepare($connect, $deleteSql);
    mysqli_stmt_bind_param($deleteStmt, "s", $StdID);

    if (mysqli_stmt_execute($deleteStmt)) {
      mysqli_stmt_close($deleteStmt);
      mysqli_close($connect);
      $_SESSION['message'] = 'Successfully deleted the student account!';
      $_SESSION['toast_type'] = 'success';
      header("Location: AdminHome.php");
      exit;
    } else {
      $errorMessage = "Failed to delete profile: " . mysqli_error($connect);
      mysqli_stmt_close($deleteStmt);
    }
  } else {
    $newEmail = $_POST['StdEmail'];
    $newPassword = $_POST['StdPass'];

    $updateSql = "UPDATE Student SET StdEmail = ?, StdPass = ? WHERE StdID = ?";
    $updateStmt = mysqli_prepare($connect, $updateSql);
    mysqli_stmt_bind_param($updateStmt, "sss", $newEmail, $newPassword, $StdID);

    if (mysqli_stmt_execute($updateStmt)) {
      $_SESSION['message'] = 'Profile updated successfully!';
      $_SESSION['toast_type'] = 'success';
    } else {
      $errorMessage = "Failed to update profile: " . mysqli_error($connect);
      $_SESSION['message'] = $errorMessage;
      $_SESSION['toast_type'] = 'error';
    }

    mysqli_stmt_close($updateStmt);

    $sql = "SELECT StdName, StdID, StdEmail, StdPass, StdImg FROM Student WHERE StdID = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "s", $StdID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $StdName, $StdID, $StdEmail, $StdPass, $StdImg);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
  }
}

mysqli_close($connect);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Details</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="custom.css">
  <script src="js/bootstrap.bundle.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-secondary" style="box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
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
          <img src="php/uploads/<?php echo htmlspecialchars($StdImg); ?>" alt="Profile Image" class="card-img-top" height="300">
        </div>
      </div>

      <div class="col-md-auto">
        <?php if (isset($errorMessage)) : ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $errorMessage; ?>
          </div>
        <?php endif; ?>
        <p><strong>Name:</strong> <?php echo $StdName; ?></p>
        <p><strong>Matrics ID:</strong> <?php echo $StdID; ?></p>
        <p><strong>Email:</strong> <?php echo $StdEmail; ?></p>
        <form action="" method="POST">
          <p>
            <input type="password" class="form-control" id="passwordField" name="StdPass" placeholder="Enter new password">
          </p>
          <p>
            <input type="email" class="form-control" id="emailField" name="StdEmail" placeholder="Enter new email">
          </p>
          <input type="hidden" name="AdminEmail" value="<?php echo $StdEmail; ?>">
          <br>
          <input class="btn btn-primary" type="submit" name="submit" value="Update Profile">
          <br>
          <input class="btn btn-danger mt-2" type="submit" name="delete" value="Delete Profile">
        </form>
      </div>
    </div>
  </div>

  <!-- Toast Notifications -->
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast" id="successToast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="">
        <strong class="me-auto">Faculty Parcel Management</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>
      </div>
    </div>
  </div>

  <?php
  if (isset($_SESSION['message'])) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
              var toast = new bootstrap.Toast(document.getElementById('successToast'));
              
            });
          </script>";
  }
  ?>

  <script>
    var toastTrigger = document.getElementById('liveToastBtn');
    var toasts = document.querySelectorAll('.toast');

    if (toastTrigger) {
      toastTrigger.addEventListener('click', function() {

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
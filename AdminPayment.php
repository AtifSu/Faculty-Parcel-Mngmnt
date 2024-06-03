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
    $_SESSION['toast_message'] = "Appointment deleted successfully.";
    $_SESSION['toast_type'] = "success"; 
  } else {
    $_SESSION['toast_message'] = "Failed to delete appointment: " . mysqli_error($connect);
    $_SESSION['toast_type'] = "danger";
  }

  mysqli_stmt_close($stmt);
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// Searching function
if (isset($_POST['search'])) {
  $searchTerm = mysqli_real_escape_string($connect, $_POST['search']);
  $sql = "SELECT StdID, AppointmentDate, AppointmentTime, ParcelTrackingNum, PaymentReceipt FROM Appointment WHERE StdID LIKE '%$searchTerm%' OR AppointmentDate LIKE '%$searchTerm%'";
  $result = mysqli_query($connect, $sql);
} else {
  $sql = "SELECT StdID, AppointmentDate, AppointmentTime, ParcelTrackingNum, PaymentReceipt FROM Appointment";
  $result = mysqli_query($connect, $sql);
}

$toast_message = isset($_SESSION['toast_message']) ? $_SESSION['toast_message'] : '';
$toast_type = isset($_SESSION['toast_type']) ? $_SESSION['toast_type'] : '';
unset($_SESSION['toast_message']);
unset($_SESSION['toast_type']);

// Check if new appointment was added
$new_appointment_added = isset($_SESSION['new_appointment_added']) ? $_SESSION['new_appointment_added'] : false;
unset($_SESSION['new_appointment_added']);

$uploaded_image = isset($_SESSION['uploaded_image']) ? $_SESSION['uploaded_image'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Payment</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="custom.css">
  <link rel="icon" href="img/logo.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="js/bootstrap.bundle.js"></script>
</head>

<body>
  <!-- Navigation bar -->
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

  <div class="row justify-content-center">
    <div class="col-md-4 ax">
      <div class="h2 mt-3"> Payment and Appointment </div>
      <form action="payment.php" method="post" enctype="multipart/form-data">
        <div class="card" style="width: 18rem;">
          <?php if ($uploaded_image) : ?>
            <img src="payment/<?php echo htmlspecialchars($uploaded_image); ?>" alt="Profile Image" class="card-img-top" height="300">
          <?php else : ?>
            <img src="default_image_path.jpg" alt="Default Image" class="card-img-top" height="300">
          <?php endif; ?>
          <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png" required>
          <div class="card-body">
            <div class="input-group input-group-sm mb-3">
              <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" name="PaymentNumber" placeholder="Account Number">
            </div>
            <div class="input-group input-group-sm mb-3">
              <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" name="PaymentBank" placeholder="Bank Name">
              <button type="submit" name="upload" class="btn btn-primary">Submit</button>
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
          if (isset($result) && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <div class="card my-3">
                <div class="card-body">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                    <input type="hidden" name="StdID" value="<?php echo $row['StdID']; ?>">
                    <button type="submit" class="btn-close float-end" name="delete_appointment"></button>
                    <h5 class="card-title"><strong>Appointment Details</strong></h5>
                    <p class="card-text">Matrics ID: <strong><?php echo $row["StdID"]; ?></strong></p>
                    <p class="card-text">Appointment Date: <strong><?php echo $row["AppointmentDate"]; ?></strong></p>
                    <p class="card-text">Appointment Time: <strong><?php echo $row["AppointmentTime"]; ?></strong></p>
                    <p class="card-text">Tracking Number: <strong><?php echo $row["ParcelTrackingNum"]; ?></strong></p>
                    <?php
                    if (!empty($row['PaymentReceipt'])) {
                      $imagePath = '' . $row['PaymentReceipt'];
                      //echo "Receipt Image Path: " . $imagePath . "<br>";
                      echo "<a href='$imagePath' target='_blank'>View Receipt Image</a><br>";
                      //echo "Receipt Image: <br><img src='" . $imagePath . "' alt='Payment Receipt' style='max-width: 100%;'><br>";
                    } else {
                      echo "<p>No receipt image available.</p>";
                    }
                    ?>
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

  <!-- Toast container -->
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="">
        <strong class="me-auto">Faculty Parcel Management</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <?php
        if ($new_appointment_added) {
          echo "New appointment added successfully.";
        } else {
          echo $toast_message;
        }
        ?>
      </div>
    </div>
  </div>

  <script>
    var toastTrigger = document.getElementById('liveToastBtn');
    var toastLive = document.getElementById('liveToast');

    if (toastTrigger) {
      toastTrigger.addEventListener('click', function() {
        var toast = new bootstrap.Toast(toastLive);
        toast.show();
      });
    }

    // Automatically show toast if there is a message
    <?php if ($new_appointment_added || !empty($toast_message)) : ?>
      document.addEventListener('DOMContentLoaded', function() {
        var toast = new bootstrap.Toast(toastLive);
        toast.show();
      });
    <?php endif; ?>
  </script>

</body>

</html>

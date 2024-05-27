<?php
include('php/connect.php');
session_start();

$stdID = isset($_SESSION['StdID']) ? $_SESSION['StdID'] : null;
$paymentDetails = [];
$parcels = [];

$sql = "SELECT PaymentBank, PaymentNumber, PaymentImg FROM Payment ORDER BY PaymentID DESC LIMIT 1";
$result = mysqli_query($connect, $sql);

if ($result && mysqli_num_rows($result) > 0) {
  $paymentDetails = mysqli_fetch_assoc($result);
} else {
  echo "<script>alert('Error: No results found');</script>";
}

if ($stdID) {
  $parcelQuery = "SELECT ParcelTrackingNum FROM Parcel WHERE StdID = '$stdID'";
  $parcelResult = mysqli_query($connect, $parcelQuery);
  if ($parcelResult && mysqli_num_rows($parcelResult) > 0) {
    while ($row = mysqli_fetch_assoc($parcelResult)) {
      $parcels[] = $row['ParcelTrackingNum'];
    }
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['schedule_appointment'])) {
  $appointmentDate = mysqli_real_escape_string($connect, $_POST['AppointmentDate']);
  $appointmentTime = mysqli_real_escape_string($connect, $_POST['AppointmentTime']);

  if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] == 0) {
    $targetDir = "payment/receipts/";
    $targetFile = $targetDir . basename($_FILES["receipt_image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["receipt_image"]["tmp_name"]);
    if ($check === false) {
      $_SESSION['appointment_error'] = "File is not an image.";
    } elseif ($_FILES["receipt_image"]["size"] > 500000) {
      $_SESSION['appointment_error'] = "Sorry, your file is too large.";
    } elseif (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
      $_SESSION['appointment_error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    } else {
      if (move_uploaded_file($_FILES["receipt_image"]["tmp_name"], $targetFile)) {
        $appointmentSql = "INSERT INTO Appointment (StdID, AppointmentDate, AppointmentTime, ParcelTrackingNum, PaymentReceipt) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $appointmentSql);
        mysqli_stmt_bind_param($stmt, "sssss", $stdID, $appointmentDate, $appointmentTime, $parcels[0], $targetFile); // assuming $parcels[0] as ParcelTrackingNum

        if (mysqli_stmt_execute($stmt)) {
          $_SESSION['appointment_success'] = "Appointment scheduled successfully.";
        } else {
          $_SESSION['appointment_error'] = "Error inserting appointment: " . mysqli_error($connect);
        }
        mysqli_stmt_close($stmt);
      } else {
        $_SESSION['appointment_error'] = "Sorry, there was an error uploading your file.";
      }
    }
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
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
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
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
    <div class="col-md-4 ax justify-content-center">
      <div class="h2 mt-3"> Payment and Appointment </div>
      <div class="card" style="width: 18rem;">
      <div class="h6">
        <a type="button" class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="1 Parcel = RM1">
          Payment information
        </a>
        </div>
        <?php if (!empty($paymentDetails['PaymentImg'])) : ?>
          <img src="payment/<?php echo htmlspecialchars($paymentDetails['PaymentImg']); ?>" class="card-img-top" alt="Bank Image">
        <?php else : ?>
          <img src="img/default.png" class="card-img-top" alt="Default Bank Image">
        <?php endif; ?>
        <div class="card-body">
          <p class="card-text"><strong>Account Number: </strong><?php echo htmlspecialchars($paymentDetails['PaymentNumber']); ?></p>
          <p class="card-text"><strong>Bank Name: </strong><?php echo htmlspecialchars($paymentDetails['PaymentBank']); ?></p>
        </div>
      </div>
    </div>

    <div class="col-md-4 ax mt-5">
      <div class="card mt-4  p-3 w-100 ">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
          <div class="mb-3 row">
            <h2 class="text-center">Appointment</h2>
            <label for="AppointmentDate" class="form-label">Date</label>
            <div class="col-sm-10">
              <input type="date" class="form-control" id="AppointmentDate" name="AppointmentDate" required>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="AppointmentTime" class="form-label">Time</label>
            <div class="col-sm-10">
              <input type="time" class="form-control" id="AppointmentTime" name="AppointmentTime" required>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="ParcelList" class="form-label">Your Parcels</label>
            <div class="col-sm-10">
              <ul class="list-group">
                <?php foreach ($parcels as $parcel) : ?>
                  <li class="list-group-item"><?php echo htmlspecialchars($parcel); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="receipt_image" class="form-label">Choose Receipt Image</label>
            <div class="col-sm-10">
              <input type="file" class="form-control" id="receipt_image" name="receipt_image" accept="image/*" required>
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-10">
              <button type="submit" name="schedule_appointment" class="btn btn-primary w-100">Schedule Appointment</button>
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
                  <div class="toast-body">' . $_SESSION['appointment_error'] . '</div>';
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

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  </script>
</body>

</html>
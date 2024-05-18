<?php
include('php/connect.php');
session_start();

if (isset($_SESSION['StdID'])) {
    $StdID = $_SESSION['StdID'];

    $sql = "SELECT StdName, StdID, StdEmail FROM Student WHERE StdID = '$StdID'";
    $result = mysqli_query($connect, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $StdName = $row['StdName'];
        $StdID = $row['StdID'];
        $StdEmail = $row['StdEmail'];
    } else {
        echo "Error: No results found";
    }
} else {
    header("Location: login.html");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="js/bootstrap.bundle.js"></script>
    <title>Student Profile</title>
</head>

<body>
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
                <li class="nav-item">
                    <div class="h1">
                        <a class="nav-link bi bi-person" href="StdProfile.php"></a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Image & Details -->
    <div class="container text-center mt-5">
        <div class="row justify-content-md-center">
            <div class="col col-lg-2">
                <div class="card float-end" style="width: 18rem;">
                    <form action="php/upload.php" method="post" enctype="multipart/form-data">
                        <img src="uploads/<?php echo isset($newImageName) ? $newImageName : ''; ?>" alt="Profile Image">
                        <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png" required>
                        <button type="submit" class="btn btn-primary" name="upload">Upload</button>
                        <input type="hidden" name="StdID" value="<?php echo $_SESSION['StdID']; ?>">
                    </form>
                </div>
            </div>

            <div class="col-md-auto">
                <p><strong>Name:</strong> <?php echo $StdName; ?></span></p>
                <p><strong>Matrics ID:</strong> <?php echo $StdID; ?></span></p>
                <p><strong>Email:</strong> <?php echo $StdEmail; ?></span></p>
                <form action="php/stdUpdate.php" method="POST">
                    <p>
                        <input type="password" class="form-control" id="passwordField" name="StdPass" placeholder="Enter new password">
                    </p>
                    <p>
                        <input type="email" class="form-control" id="emailField" name="StdEmail" placeholder="Enter new email">
                    </p>
                    <input type="hidden" name="AdminEmail" value="<?php echo $StdEmail; ?>">
                    <br>
                    <input class="btn btn-primary" type="submit" name="submit" value="Update Profile">
                </form>
            </div>

            <div class="col col-lg-2">
                <div class="h2 float-end">
                    <a class="icon-link" href="php/logout.php">
                        Logout
                        <a class="bi bi-box-arrow-right" aria-hidden="true" href="php/logout.php"></a>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <?php
            if (isset($_SESSION['update_success'])) {
              echo '<div class="toast-container position-fixed top-0 end-0 p-3">
                      <div class="toast align-items-center text-bg border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                          <div class="toast-header">
                              <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="">
                              <strong class="me-auto">Faculty Parcel Management</strong>
                              <button type="button" class="btn-close btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                          </div>
                          <div class="toast-body">' . $_SESSION['update_success'] . '</div>
                      </div>
                    </div>';
          }
          if (isset($_SESSION['update_error'])) {
            echo '<div class="toast-container position-fixed top-0 end-0 p-3">
                    <div class="toast align-items-center text-bg border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                        <div class="toast-header">
                            <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="">
                            <strong class="me-auto">Faculty Parcel Management</strong>
                            <button type="button" class="btn-close btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">' . $_SESSION['update_error'] . '</div>
                    </div>
                  </div>';
        } 
            ?>
        </div>
    </div>

    <script>
        // Automatically show toast notifications if they exist
        document.addEventListener('DOMContentLoaded', function() {
            var toasts = document.querySelectorAll('.toast');
            toasts.forEach(function(toast) {
                var bsToast = new bootstrap.Toast(toast);
                //bsToast.show();
            });
        });

        // Show toast notifications when the bell icon is clicked
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

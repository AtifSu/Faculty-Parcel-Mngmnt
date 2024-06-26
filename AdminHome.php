<?php
include('php/connect.php');
session_start();
if (isset($_POST['search'])) {
  $searchTerm = mysqli_real_escape_string($connect, $_POST['search']);

  $sql = "SELECT StdName, StdID, StdEmail FROM Student WHERE StdName LIKE '%$searchTerm%' OR StdEmail LIKE '%$searchTerm%'";
  $result = mysqli_query($connect, $sql);
} else {
  $sql = "SELECT StdName, StdID, StdEmail FROM Student";
  $result = mysqli_query($connect, $sql);
}

if (isset($_SESSION['message'])) {
  $message = $_SESSION['message'];
  $toast_type = isset($_SESSION['toast_type']) ? $_SESSION['toast_type'] : 'info';
  unset($_SESSION['message']);
  unset($_SESSION['toast_type']);
} else {
  $message = "";
  $toast_type = "info";
}
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
  <link rel="icon" href="img/logo.png">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

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
  <br>

  <div class="container text-center">
    <div class="row">
      <div class="col">
        <h2 class="primary">User Lists</h2>
      </div>
      <div class="col">
        <div class="row justify-content-center">
          <div class="col-sm-6">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search" aria-label="Search" aria-describedby="search-btn">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit" id="search-btn">Search</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <br>
    <?php
    if (mysqli_num_rows($result) > 0) {
      $col_count = 0;
      echo "<div class='row'>";

      while ($row = mysqli_fetch_assoc($result)) {
        $col_count++;

        echo "  <div class='col'>";
        echo "    <a class='no-decoration' href='UserLists.php?id=" . $row["StdID"] . "'>";
        echo "      <div class='card mb-3' style='width: 18rem;'> ";
        echo "        <ul class='list-group list-group-flush'>";
        echo "          <li class='list-group-item'>" . $row["StdName"] . "</li>";
        echo "          <li class='list-group-item'>" . $row["StdID"] . "</li>";
        echo "          <li class='list-group-item'>" . $row["StdEmail"] . "</li>";
        echo "        </ul>";
        echo "      </div>";
        echo "    </a>";
        echo "  </div>";

        if ($col_count % 3 == 0) {
          echo "</div>";
          echo "<div class='row'>";
          $col_count = 0;
        }
      }

      if ($col_count > 0) {
        echo "</div>";
      }
    } else {
      echo "No data found";
    }

    mysqli_close($connect);
    ?>
  </div>

  <!-- Toast container -->
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast" id="successToast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <img src="img/logo.png" class="rounded me-2" width="30" height="20" alt="">
        <strong class="me-auto">Faculty Parcel Management</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <?php echo $message; ?>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
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

    document.addEventListener('DOMContentLoaded', function() {
      var toast = new bootstrap.Toast(document.getElementById('liveToast'));

      // Check if a new appointment is added and show the toast notification
      <?php if (isset($_SESSION['new_appointment_added']) && $_SESSION['new_appointment_added'] === true) : ?>
        toast.show();
        <?php unset($_SESSION['new_appointment_added']); // unset the session variable after displaying the toast 
        ?>
      <?php endif; ?>
    });
  </script>

</body>

</html>
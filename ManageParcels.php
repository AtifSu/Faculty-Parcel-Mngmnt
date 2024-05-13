<?php
include('php/connect.php');
session_start();


$sql = "SELECT StdID, ParcelTrackingNum, AppointmentDate FROM Appointment";
$result = mysqli_query($connect, $sql);

if (isset($_GET['search'])) {
  $search = $_GET['search'];
  $sql = "SELECT StdID, ParcelTrackingNum, AppointmentDate FROM Appointment WHERE StdID LIKE '%$search%' OR ParcelTrackingNum LIKE '%$search%'";
} else {
  $sql = "SELECT StdID, ParcelTrackingNum, AppointmentDate FROM Appointment";
}
$result = mysqli_query($connect, $sql);


if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql = "DELETE FROM Appointment WHERE ParcelTrackingNum = ?";
  $stmt = mysqli_prepare($connect, $sql);
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);

  if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(array("success" => true));
  } else {
    echo json_encode(array("success" => false));
  }

  mysqli_stmt_close($stmt);
  mysqli_close($connect);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Parcels</title>
  <link rel="stylesheet" href="css/bootstrap.css">
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

  <div class="container text-center">
    <div class="row mt-3">
      <div class="col">
        <h2 class="primary">Manage Parcels</h2>
      </div>
      <div class="col">
        <div class="row justify-content-center">
          <div class="col-sm-6">
            <form action="ManageParcels.php" method="get" id="searchForm">
              <div class="row justify-content-between">
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Search" id="searchInput" name="search" aria-label="Search" aria-describedby="search-btn">
                </div>
                <div class="col-sm-3">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" id="searchBtn">Search</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <br>
    <?php
    
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $StdID = $row['StdID'];
        $TrackingNum = $row['ParcelTrackingNum'];
        $AppDate = $row['AppointmentDate'];

        echo '<div class="row">';
        echo '<div class="col">';
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo "<h6>";
        echo "<p> <strong> Matrics ID:</strong> $StdID</p>";
        echo "<p> <strong>Tracking No:</strong> $TrackingNum</p>";
        echo "<p> <strong> Appointment Date:</strong>  $AppDate</p>";
        echo "</h6>";
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="col">';
        echo '<a class="icon-link remove-link" href="#" onclick="removeParcel(\'' . $TrackingNum . '\')">Remove</a>';
        echo '</div>';

        echo '</div>';
      }
    }
    ?>

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
      //Searching function
      document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("searchBtn").addEventListener("click", function() {
          const searchValue = document.getElementById("searchInput").value.toLowerCase();
          const parcelCards = document.getElementById("parcelCards");
          const cards = parcelCards.querySelectorAll(".card");

          cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            if (text.includes(searchValue)) {
              card.style.display = "block";
            } else {
              card.style.display = "none";
            }
          });
        });
      });
      //Remove Function (Delete Parcel)
      function removeParcel(AppointmentID) {
        fetch(window.location.href + "?id=" + AppointmentID, {
            method: 'DELETE'
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              location.reload();
            } else {
              alert("Failed to remove parcel. The server responded with an error.");
            }
          })
          .catch(error => {
            console.error("Error:", error);
            alert("Parcel removed sucesffully. Please refresh.");
          });
        event.preventDefault();
      }
    </script>
</body>
</html>
<?php
include('php/connect.php');
session_start();

$sql = "SELECT StdID, ParcelTrackingNum, ParcelCourier, ParcelStatus FROM Parcel";
$result = mysqli_query($connect, $sql);

if (isset($_GET['search'])) {
  $search = $_GET['search'];
  $sql = "SELECT StdID, ParcelTrackingNum, ParcelCourier, ParcelStatus FROM Parcel WHERE StdID LIKE '%$search%' OR ParcelTrackingNum LIKE '%$search%'";
} else {
  $sql = "SELECT StdID, ParcelTrackingNum, ParcelCourier, ParcelStatus FROM Parcel";
}
$result = mysqli_query($connect, $sql);


if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql = "DELETE FROM Parcel WHERE ParcelID = ?";
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
          <div class="h2 col-auto">
            <a type="button" class="bi bi-plus-square" data-bs-toggle="modal" data-bs-target="#exampleModal"></a>
          </div>
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
        $ParcelTrackingNum = $row['ParcelTrackingNum'];
        $ParcelCourier = $row['ParcelCourier'];
        $ParcelStatus = $row['ParcelStatus'];

        echo '<div class="row">';
        echo '<div class="col">';
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo "<h6>";
        echo "<p> <strong> Matrics ID:</strong> $StdID</p>";
        echo "<p> <strong>Tracking No:</strong> $ParcelTrackingNum</p>";
        echo "<p> <strong>Parcel Courier:</strong> $ParcelCourier</p>";
        echo "<p> <strong>Status:</strong> $ParcelStatus</p>";
        echo "</h6>";
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="col">';
        echo '<a class="icon-link remove-link" href="#" onclick="removeParcel(\'' . $ParcelTrackingNum . '\')">Remove</a>';
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

    <!-- Add appointment popup -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Parcel</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Form -->
            <form action="addparcel.php" method="post">
              <div class="row g-3 align-items-center">
                <div class="col">
                  <div class="row">
                    <div class="col-auto">
                      <label for="ParcelTrackingNum" class="col-form-label">Tracking Number</label>
                    </div>
                    <div class="col-auto">
                      <input type="text" id="ParcelTrackingNum" name="ParcelTrackingNum" class="form-control">
                    </div>
                  </div>
                  <div class="row mt-1">
                    <div class="col-auto">
                      <label for="ParcelCourier" class="col-form-label">Courier</label>
                    </div>
                    <div class="col-auto ms-5">
                      <input type="text" id="ParcelCourier" name="ParcelCourier" class="form-control">
                    </div>
                  </div>
                  <div class="row mt-1">
                    <div class="col-auto">
                      <label for="ParcelStatus" class="col-form-label">Status</label>
                    </div>
                    <div class="col-auto ms-5">
                      <input type="text" id="ParcelStatus" name="ParcelStatus" class="form-control">
                    </div>
                  </div>
                  <div class="row mt-1">
                    <div class="col-auto">
                      <label for="ParcelArriveDate" class="col-form-label">Arrived Date</label>
                    </div>
                    <div class="col-auto ms-1">
                      <input type="date" id="ParcelArriveDate" name="ParcelArriveDate" class="form-control">
                    </div>
                  </div>
                  <div class="row mt-1">
                    <div class="col-auto">
                      <label for="StdID" class="col-form-label">Matrics ID</label>
                    </div>
                    <div class="col-auto ms-3">
                      <input type="text" id="StdID" name="StdID" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
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
            function removeParcel(ParcelID) {
              fetch(window.location.href + "?id=" + ParcelID, {
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
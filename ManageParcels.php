<?php
include('php/connect.php');
session_start();
$ParcelID = null;
$sql = "SELECT StdID, ParcelID,ParcelTrackingNum, ParcelCourier, ParcelStatus FROM Parcel";
$result = mysqli_query($connect, $sql);

//Search

if (isset($_GET['search'])) {
  $search = $_GET['search'];
  $sql = "SELECT ParcelID, StdID, ParcelTrackingNum, ParcelCourier, ParcelStatus FROM Parcel WHERE StdID LIKE '%$search%' OR ParcelTrackingNum LIKE '%$search%'";
} else {
  $sql = "SELECT ParcelID, StdID, ParcelTrackingNum, ParcelCourier, ParcelStatus FROM Parcel";
}
$result = mysqli_query($connect, $sql);

$ParcelID = null;

//Delete parcel
if (isset($_GET['ParcelID'])) {
  $ParcelID = $_GET['ParcelID'];

  $sql = "DELETE FROM Parcel WHERE ParcelID = ?";
  $stmt = mysqli_prepare($connect, $sql);

  mysqli_stmt_bind_param($stmt, "i", $ParcelID);

  if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
      echo json_encode(array("success" => true));
    } else {
      echo json_encode(array("success" => false, "error" => "No parcel found with the specified ID."));
    }
  } else {
    echo json_encode(array("success" => false, "error" => mysqli_error($connect)));
  }
  mysqli_stmt_close($stmt);
  mysqli_close($connect);

  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" >
  <title>Manage Parcels</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="custom.css">
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

  <div class="container text-center">
    <div class="row mt-3">
      <div class="col">
        <h2 class="primary">Manage Parcels</h2>
      </div>
      <div class="col">
        <div class="row justify-content-center">
          <div class="h2 col-auto">
            <a type="button" class="bi bi-plus-square" style="color: #F46E75" data-bs-toggle="modal" data-bs-target="#exampleModal"></a>
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
    <div id="parcelCards">
      <?php
      if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
          $ParcelID = $row['ParcelID'];
          $StdID = $row['StdID'];
          $ParcelTrackingNum = $row['ParcelTrackingNum'];
          $ParcelCourier = $row['ParcelCourier'];
          $ParcelStatus = $row['ParcelStatus'];

          echo '<div class="row mb-3">';
          echo '<div class="col">';
          echo '<div class="card">';
          echo '<div class="card-body">';
          echo "<h6>";
          echo "<p> <strong> Matrics ID:</strong> $StdID</p>";
          echo "<p> <strong>Tracking No:</strong> $ParcelTrackingNum</p>";
          echo "<p> <strong>Parcel Courier:</strong> $ParcelCourier</p>";
          echo "<p> <strong>Status:</strong> $ParcelStatus</p>";
          echo "</h6>";
          echo '<div class="h2 mb-0">';
          echo '</div>';
          echo '</div>';
          echo '</div>';
          echo '</div>';

          echo '<div class="col">';
          echo "<a type='button' style='color: #F46E75' class='bi bi-pencil-square' data-bs-toggle='modal' data-bs-target='#editModal' data-parcel-id='$ParcelID' data-parcel-status='$ParcelStatus'></a>";
          echo '<a class="ms-4 icon-link remove-link" href="#"  onclick="removeParcel(\'' . $ParcelID . '\')" style="color: #F46E75" >Remove</a>';
          echo '</div>';
          echo '</div>';
        }
      }
      ?>
    </div>

    <!-- Toast notifications -->
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

    <!-- Add parcel modal -->
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
              <div class="modal-footer mt-5">
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit parcel modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="editModalLabel">Edit Parcel</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Edit Form -->
            <form id="editParcelForm">
              <input type="hidden" id="editParcelID" name="ParcelID">
              <div class="row g-3 align-items-center">
                <div class="col">
                  <div class="row">
                    <div class="col-auto">
                      <label for="editParcelStatus" class="col-form-label">Status</label>
                    </div>
                    <div class="col-auto ms-5">
                      <input type="text" id="editParcelStatus" name="ParcelStatus" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer mt-5">
                <button type="button" class="btn btn-primary" onclick="updateParcel()">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Handle edit button click
      var editModal = document.getElementById('editModal');
      editModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var parcelID = button.getAttribute('data-parcel-id');
        var parcelStatus = button.getAttribute('data-parcel-status');

        var modalParcelID = editModal.querySelector('#editParcelID');
        var modalParcelStatus = editModal.querySelector('#editParcelStatus');

        modalParcelID.value = parcelID;
        modalParcelStatus.value = parcelStatus;
      });

      // Update parcel status
      function updateParcel() {
        var form = document.getElementById('editParcelForm');
        var formData = new FormData(form);

        fetch('edit_parcel.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert("Successfully updated the parcel.");
              location.reload();
            } else {
              alert("Failed to update parcel. The server responded with an error.");
            }
          })
          .catch(error => {
            console.error("Error:", error);
            alert("Error updating parcel.");
          });
      }

      // Remove parcel
      function removeParcel(ParcelID) {
        fetch('ManageParcels.php?ParcelID=' + ParcelID, {
            method: 'GET'
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              const toast = new bootstrap.Toast(document.querySelector('.toast.bg-primary')); // Target success toast
              toast.show();
              alert("Successfully updated the parcel.");
              location.reload();
            } else {
              const toast = new bootstrap.Toast(document.querySelector('.toast.bg-danger')); // Target removal toast
              toast.show();
              alert("Successfully removed the parcel.");
              location.reload();
            }
          })
          .catch(error => {
            console.error("Error:", error);
            alert("Error removing parcel.");
          });
      }

      // Searching function
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

      // Toast notification
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
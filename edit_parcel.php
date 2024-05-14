<?php
include('php/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ParcelID = $_POST['ParcelID'];
    $ParcelStatus = $_POST['ParcelStatus'];

    error_log("ParcelID: $ParcelID");
    error_log("ParcelStatus: $ParcelStatus");

    $sql = "UPDATE Parcel SET ParcelStatus = ? WHERE ParcelID = ?";
    $stmt = mysqli_prepare($connect, $sql);

    if (!$stmt) {
        error_log("Prepare failed: (" . $connect->errno . ") " . $connect->error);
        echo json_encode(array("success" => false, "message" => "Prepare failed: " . $connect->error));
        exit;
    }

    mysqli_stmt_bind_param($stmt, "si", $ParcelStatus, $ParcelID);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(array("success" => true));
    } else {
        error_log("Update failed: (" . $connect->errno . ") " . $connect->error);
        echo json_encode(array("success" => false, "message" => "Update failed: " . $connect->error));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
}
?>

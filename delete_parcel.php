<?php
include('php/connect.php');
session_start();

if(isset($_GET['ParcelTrackingNum'])) {
    $ParcelTrackingNum = $_GET['ParcelTrackingNum'];

    $sql = "DELETE FROM Parcel WHERE ParcelTrackingNum = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ParcelTrackingNum); 
    mysqli_stmt_execute($stmt);

    if(mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);

    exit;
} else {
    echo json_encode(array("success" => false, "error" => "ParcelTrackingNum not provided"));
}
?>

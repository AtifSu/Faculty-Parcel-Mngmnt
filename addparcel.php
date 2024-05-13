<?php
include('php/connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ParcelTrackingNum = $_POST['ParcelTrackingNum'];
    $ParcelCourier = $_POST['ParcelCourier'];
    $ParcelStatus = $_POST['ParcelStatus'];
    $ParcelArriveDate = $_POST['ParcelArriveDate'];
    $StdID = $_POST['StdID'];

    $sql = "INSERT INTO Parcel (ParcelTrackingNum, ParcelCourier, ParcelStatus, ParcelArriveDate, StdID) VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connect, $sql);

    mysqli_stmt_bind_param($stmt, "sssss", $ParcelTrackingNum, $ParcelCourier, $ParcelStatus, $ParcelArriveDate, $StdID);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Parcel inserted successfully!');</script>";
        echo "<script>window.location.href = 'ManageParcels.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_stmt_error($stmt) . "');</script>";
    }

    mysqli_stmt_close($stmt);
}
mysqli_close($connect);

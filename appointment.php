<?php
include('php/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $AppointmentDate = $_POST['AppointmentDate'];
    $AppointmentTime = $_POST['AppointmentTime'];
    $StdID = $_POST['StdID'];
    $ParcelTrackingNum = $_POST['ParcelTrackingNum'];

    $sql = "INSERT INTO Appointment (AppointmentDate, AppointmentTime, StdID, ParcelTrackingNum) VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($connect, $sql);

    mysqli_stmt_bind_param($stmt, "ssss", $AppointmentDate, $AppointmentTime, $StdID, $ParcelTrackingNum);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Appointment booked successfully!');</script>";
        echo "<script>window.location.href = 'StdPayment.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_stmt_error($stmt) . "');</script>"; 
        echo "<script>window.location.href = 'StdPayment.php';</script>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($connect);
?>

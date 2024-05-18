<?php
session_start();
include('php/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointmentDate = $_POST['AppointmentDate'];
    $appointmentTime = $_POST['AppointmentTime'];
    $stdID = $_POST['StdID'];
    $parcelTrackingNum = $_POST['ParcelTrackingNum'];

    $sql = "INSERT INTO Appointment (AppointmentDate, AppointmentTime, StdID, ParcelTrackingNum) VALUES ('$appointmentDate', '$appointmentTime', '$stdID', '$parcelTrackingNum')";
     
    if (mysqli_query($connect, $sql)) {
        $_SESSION['appointment_success'] = "Appointment successfully booked at " . "$appointmentDate" . " $appointmentTime";
    } else {
        $_SESSION['appointment_error'] = "Failed to book appointment: " . mysqli_error($connect);
    }

    mysqli_close($connect);
    header("Location: StdPayment.php");
    exit();
}

<?php
include('php/connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stdID = isset($_SESSION['StdID']) ? $_SESSION['StdID'] : null;
    $appointmentDate = $_POST['AppointmentDate'];
    $appointmentTime = $_POST['AppointmentTime'];

    if ($stdID && $appointmentDate && $appointmentTime) {
        $parcelQuery = "SELECT ParcelTrackingNum FROM Parcel WHERE StdID = '$stdID'";
        $parcelResult = mysqli_query($connect, $parcelQuery);

        if ($parcelResult && mysqli_num_rows($parcelResult) > 0) {
            while ($row = mysqli_fetch_assoc($parcelResult)) {
                $parcelTrackingNum = $row['ParcelTrackingNum'];
                $appointmentQuery = "INSERT INTO Appointment (StdID, ParcelTrackingNum, AppointmentDate, AppointmentTime) 
                                     VALUES ('$stdID', '$parcelTrackingNum', '$appointmentDate', '$appointmentTime')";
                if (!mysqli_query($connect, $appointmentQuery)) {
                    $_SESSION['appointment_error'] = "Error scheduling appointment for parcel $parcelTrackingNum.";
                    header('Location: StdPayment.php');
                    exit();
                }
            }
            $_SESSION['appointment_success'] = "Appointment scheduled successfully for all parcels.";
        } else {
            $_SESSION['appointment_error'] = "No parcels found for the student.";
        }
    } else {
        $_SESSION['appointment_error'] = "Invalid appointment data.";
    }
}

mysqli_close($connect);
header('Location: StdPayment.php');
exit();
?>

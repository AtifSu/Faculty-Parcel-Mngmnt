<?php
include('php/connect.php');
session_start();

// Handle appointment creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['StdID'])) {
        $stdID = $_SESSION['StdID'];
        $appointmentDate = $_POST['AppointmentDate'];
        $appointmentTime = $_POST['AppointmentTime'];

        if ($appointmentDate && $appointmentTime) {
            $parcelQuery = "SELECT ParcelTrackingNum FROM Parcel WHERE StdID = ?";
            $stmt = mysqli_prepare($connect, $parcelQuery);
            mysqli_stmt_bind_param($stmt, "s", $stdID);
            mysqli_stmt_execute($stmt);
            $parcelResult = mysqli_stmt_get_result($stmt);

            if ($parcelResult && mysqli_num_rows($parcelResult) > 0) {
                while ($row = mysqli_fetch_assoc($parcelResult)) {
                    $parcelTrackingNum = $row['ParcelTrackingNum'];
                    $appointmentQuery = "INSERT INTO Appointment (StdID, ParcelTrackingNum, AppointmentDate, AppointmentTime) 
                                         VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_prepare($connect, $appointmentQuery);
                    mysqli_stmt_bind_param($stmt, "ssss", $stdID, $parcelTrackingNum, $appointmentDate, $appointmentTime);
                    if (!mysqli_stmt_execute($stmt)) {
                        $_SESSION['appointment_error'] = "Error scheduling appointment for parcel $parcelTrackingNum: " . mysqli_error($connect);
                        header('Location: StdPayment.php');
                        exit();
                    }
                }
                $_SESSION['appointment_success'] = "Appointment scheduled successfully for all parcels.";
                $_SESSION['new_appointment_added'] = true;
            } else {
                $_SESSION['appointment_error'] = "No parcels found for the student.";
            }
        } else {
            $_SESSION['appointment_error'] = "Invalid appointment data.";
        }
    } else {
        $_SESSION['appointment_error'] = "Student ID not set.";
    }
}

// Redirect to AdminPayment.php
header('Location: AdminPayment.php');
exit();
?>

<?php
session_start();
include('connect.php');

if (!isset($_SESSION['StdID']) || $_SESSION['status'] != 'student') {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentEmail = filter_var($_POST['CurrentStudentEmail'], FILTER_SANITIZE_EMAIL);
    $newEmail = filter_var($_POST['NewStudentEmail'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['StdPass'];

    // Ensure both email and password fields are not empty
    if (empty($newEmail) || empty($password)) {
        header("Location: ../StdProfile.php");
        exit();
    }

    // Update query
    $sql_update = "UPDATE Student SET StdPass=?, StdEmail=? WHERE StdEmail=?";
    if ($stmt = $connect->prepare($sql_update)) {
        $stmt->bind_param("sss", $password, $newEmail, $currentEmail);
        if ($stmt->execute()) {
            $_SESSION['update_success'] = "Profile updated successfully!";
            echo "<script>window.location.href = '../StdProfile.php';</script>";
            exit();
        } else {
            $_SESSION['update_error'] = "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['update_error'] = "Error preparing statement: " . $connect->error;
    }
    header("Location: ../StdProfile.php");
}

$connect->close();
?>
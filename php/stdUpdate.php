<?php
session_start();
include('connect.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'student') {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['StdEmail'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['StdPass'];

    $sql_update = "UPDATE Student SET StdPass=? WHERE StdEmail=?";
    if ($stmt = $connect->prepare($sql_update)) {
        $stmt->bind_param("ss", $password, $email);
        if ($stmt->execute()) {
            $_SESSION['update_success'] = "Profile updated successfully!";
        } else {
            $_SESSION['update_error'] = "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['update_error'] = "Error preparing statement: " . $connect->error;
    }
    header("Location: ../StdProfile.php");
    exit();
}

$connect->close();
?>

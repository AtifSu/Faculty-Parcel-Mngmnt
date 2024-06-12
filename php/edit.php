<?php
session_start();
include('connect.php');

if (!isset($_SESSION['AdminID']) || $_SESSION['status'] != 'admin') {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentEmail = filter_var($_POST['CurrentAdminEmail'], FILTER_SANITIZE_EMAIL);
    $newEmail = filter_var($_POST['NewAdminEmail'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['AdminPass'];

    // Ensure both email and password fields are not empty
    if (empty($newEmail) || empty($password)) {
        header("Location: ../AdminProfile.php");
        exit();
    }

    // Update query
    $sql_update = "UPDATE FSPAdmin SET AdminPass=?, AdminEmail=? WHERE AdminEmail=?";
    if ($stmt = $connect->prepare($sql_update)) {
        $stmt->bind_param("sss", $password, $newEmail, $currentEmail);
        if ($stmt->execute()) {
            $_SESSION['update_success'] = "Profile updated successfully!";
            echo "<script>window.location.href = '../AdminProfile.php';</script>";
            exit();
        } else {
            $_SESSION['update_error'] = "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['update_error'] = "Error preparing statement: " . $connect->error;
    }
    header("Location: ../AdminProfile.php");
}

$connect->close();
?>
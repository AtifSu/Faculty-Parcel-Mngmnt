<?php
session_start();
include('php/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $AdminName = $_POST["AdminName"];
    $AdminEmail = $_POST["AdminEmail"];
    $AdminPhoneNum = $_POST["AdminPhoneNum"];
    $AdminID = $_POST["AdminID"];
    $AdminPass = $_POST["AdminPass"];
    $confirmPass = $_POST["confirmPass"];

    if ($AdminPass !== $confirmPass) {
        echo "<script>alert('Passwords do not match.');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    $check_sql = "SELECT * FROM FSPAdmin WHERE AdminID = ?";
    $check_stmt = mysqli_prepare($connect, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $AdminID);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        echo "<script>alert('Admin ID already in use! Contact admin to reset.')</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO FSPAdmin (AdminName, AdminEmail, AdminPhoneNum, AdminID, AdminPass) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $sql);

    if (!$stmt) {
        die('Error in preparing the statement: ' . mysqli_error($connect));
    }

    mysqli_stmt_bind_param($stmt, "ssiss", $AdminName, $AdminEmail, $AdminPhoneNum, $AdminID, $AdminPass);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die('Error in executing the statement: ' . mysqli_error($connect));
    }
    
    echo "<script>alert('Sign Up successful!');</script>";
    include ('php/create.php');
    echo "<script>window.location='login.html'</script>";

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($check_stmt);
    mysqli_close($connect);
}
?>

<?php
session_start();
include('php/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $StdName = $_POST["StdName"];
    $StdEmail = $_POST["StdEmail"];
    $StdPhoneNum = $_POST["StdPhoneNum"];
    $StdID = $_POST["StdID"];
    $StdPass = $_POST["StdPass"];
    $confirmPass = $_POST["confirmPass"];

    if ($StdPass !== $confirmPass) {
        echo "<script>alert('Passwords do not match.');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    $check_sql = "SELECT * FROM Student WHERE StdID = ?";
    $check_stmt = mysqli_prepare($connect, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $StdID);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        echo "<script>alert('Student ID already in use! Contact admin to reset.');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO Student (StdName, StdEmail, StdPhoneNum, StdID, StdPass) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $sql);

    if (!$stmt) {
        die('Error in preparing the statement: ' . mysqli_error($connect));
    }

    mysqli_stmt_bind_param($stmt, "ssiss", $StdName, $StdEmail, $StdPhoneNum, $StdID, $StdPass);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die('Error in executing the statement: ' . mysqli_stmt_error($stmt));
    }

    echo "<script>alert('Sign Up successful!');</script>";
    include ('php/create.php');
    echo "<script>window.location='login.html'</script>";

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($check_stmt);
    mysqli_close($connect);
}
?>

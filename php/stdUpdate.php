<?php
session_start();
include('connect.php');

echo "User type: " . $_SESSION['status'] . "<br>";
echo "Session variables: ";
print_r($_SESSION);

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
            echo "<script>alert('Profile updated successfully!');</script>";
            echo "<script>window.location.href = '../StdProfile.php';</script>";
            exit();
        } else {
            echo "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $connect->error;
    }
}

$connect->close();
?>

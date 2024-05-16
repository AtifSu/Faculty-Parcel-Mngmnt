<?php
session_start();
include('connect.php');

echo "User type: " . $_SESSION['status'] . "<br>";
echo "Session variables: ";
print_r($_SESSION);

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'admin') {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['AdminEmail'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['AdminPass'];

    $sql_update = "UPDATE FSPAdmin SET AdminPass=? WHERE AdminEmail=?";
    if ($stmt = $connect->prepare($sql_update)) {
        $stmt->bind_param("ss", $password, $email);
        if ($stmt->execute()) {
            echo "<script>alert('Profile updated successfully!');</script>";
            echo "<script>window.location.href = '../AdminProfile.php';</script>";
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

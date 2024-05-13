<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('connect.php');

if (isset($_SESSION['email']) && (isset($_POST['update-StdEmail']) || isset($_POST['update-StdPass']))) {
  $StdEmail = $_POST['StdEmail'];
  $StdPass = $_POST['StdPass'];
  $id = $_SESSION['StdID'];     
  if (isset($_POST['update-StdEmail'])) {
    $sql = "UPDATE Student SET StdEmail = ? WHERE StdID = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ss", $StdEmail, $id);
  } else {
    $hashedPassword = password_hash($StdPass, PASSWORD_DEFAULT); // Hash password if updating password
    $sql = "UPDATE Student SET StdPass = ? WHERE StdID = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("si", $hashedPassword, $id);
  }

  if ($stmt->execute()) {
    echo " <script>alert('Update Successful!');
             window.location='StdProfile.php'</script> ";
  } else {
    echo "Error updating fields: " . mysqli_error($connect);
  }

  $stmt->close();
}
?>
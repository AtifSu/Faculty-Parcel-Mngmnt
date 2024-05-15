<?php
include ('connect.php');
session_start(); 

if (isset($_SESSION['email']) && (isset($_POST['update-AdminEmail']) || isset($_POST['update-AdminPass']))) {
  $id = $_SESSION['AdminID']; 

  if (isset($_POST['update-AdminEmail'])) {
    $AdminEmail = $_POST['AdminEmail'];
    $sql = "UPDATE FSPAdmin SET AdminEmail = ? WHERE AdminID = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("si", $AdminEmail, $id);
  } else {
    $AdminPass = $_POST['AdminPass'];
    $sql = "UPDATE FSPAdmin SET AdminPass = ? WHERE AdminID = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("si", $AdminPass, $id); 
  }

  if ($stmt->execute()) {
    echo "<script>alert('Update Successful!');
          window.location='../AdminProfile.php'</script>";
  } else {
    echo "Error updating fields: " . mysqli_error($connect);
  }

  $stmt->close();
}
?>

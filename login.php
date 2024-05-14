<?php
include('php/connect.php');
session_start();

if (isset($_POST['email'], $_POST['password'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM FSPAdmin WHERE AdminEmail = ? AND AdminPass = ?";
  $stmt = mysqli_prepare($connect, $sql);
  mysqli_stmt_bind_param($stmt, "ss", $email, $password);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($result && $admin = mysqli_fetch_assoc($result)) {
    $_SESSION['email'] = $admin['AdminEmail'];
    $_SESSION['name'] = $admin['AdminName'];
    $_SESSION['AdminID'] = $admin['AdminID'];
    $_SESSION['status'] = 'admin';

    session_regenerate_id(true);

    echo "<script>alert('Logged in as admin with email: " . $admin['AdminEmail'] . "');</script>";
    header("Location: AdminHome.php");

    exit();
  }

  $sql = "SELECT * FROM Student WHERE StdEmail = ? AND StdPass = ?";
  $stmt = mysqli_prepare($connect, $sql);
  mysqli_stmt_bind_param($stmt, "ss", $email, $password);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($result && $student = mysqli_fetch_assoc($result)) {
    $_SESSION['email'] = $student['StdEmail'];
    $_SESSION['name'] = $student['StdName'];
    $_SESSION['StdID'] = $student['StdID'];
    $_SESSION['status'] = 'student';

    session_regenerate_id(true);

    header("Location: trackPackage.php");
    echo "<script>alert('Logged in as student with email: " . $student['StdEmail'] . "');</script>";

    exit();
  }
  echo "<script>alert('Incorrect Email or Password'); window.location='login.html'</script>";
}
?>

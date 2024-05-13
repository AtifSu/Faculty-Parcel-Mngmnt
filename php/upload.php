<?php
  include ('connect.php');
  session_start();

  // Check if upload form is submitted
  if(isset($_POST['upload'])) {

    // Check if user is logged in (session variable set)
    if (isset($_SESSION['StdID'])) {
      $StdID = $_SESSION['StdID'];

      // Handle image upload
      if($_FILES["image"]["error"] === 4){
        echo "<script>alert('Image Does Not Exist');</script>";
      } else {
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));

        if (!in_array($imageExtension, $validImageExtension)) {
          echo "<script>alert('Invalid Image Extension');</script>";
        } else if ($fileSize > 1000000) {
          echo "<script>alert('Image Size Is Too Large');</script>";
        } else {
          $newImageName = uniqid();
          $newImageName .= '.' . $imageExtension;

          // Move uploaded file
          move_uploaded_file($tmpName, 'uploads/' . $newImageName);

          // Update student record with StdImg
          $sql = "UPDATE Student SET StdImg = ? WHERE StdID = ?";
          $stmt = mysqli_prepare($connect, $sql);
          mysqli_stmt_bind_param($stmt, "si", $newImageName, $StdID);
          $result = mysqli_stmt_execute($stmt);

          if ($result) {
            echo "<script>alert('Profile picture updated!')</script>";
          } else {
            echo "<script>alert('Error updating database: " . mysqli_error($connect) . "');</script>"; 
          }

          mysqli_stmt_close($stmt);
        }
      }
    } else {
      echo "<script>alert('Session data unavailable. Please login and try again.'); window.location='../StdProfile.php'</script>";
    }
  }
  mysqli_close($connect); 
?>
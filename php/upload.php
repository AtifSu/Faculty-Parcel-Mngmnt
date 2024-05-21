<?php
include('connect.php');
session_start();

if (isset($_POST['upload'])) {
    if (isset($_SESSION['StdID'])) {
        $StdID = $_SESSION['StdID'];

        if ($_FILES["image"]["error"] === 4) {
            echo "<script>alert('Image does not exist');</script>";
        } else {
            $fileName = $_FILES["image"]["name"];
            $fileSize = $_FILES["image"]["size"];
            $tmpName = $_FILES["image"]["tmp_name"];

            $validImageExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = explode('.', $fileName);
            $imageExtension = strtolower(end($imageExtension));

            if (!in_array($imageExtension, $validImageExtension)) {
                echo "<script>alert('Invalid image extension');</script>";
            } else if ($fileSize > 1000000) {
                echo "<script>alert('Image size is too large');</script>";
            } else {
                $newImageName = uniqid();
                $newImageName .= '.' . $imageExtension;

                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                move_uploaded_file($tmpName, 'uploads/' . $newImageName);

                $sql = "UPDATE Student SET StdImg = ? WHERE StdID = ?";
                $stmt = mysqli_prepare($connect, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ss", $newImageName, $StdID);  // Use "ss" since StdID is a string
                    $result = mysqli_stmt_execute($stmt);

                    if ($result) {
                        echo "<script>alert('Profile picture updated!'); window.location='../StdProfile.php';</script>";
                    } else {
                        echo "<script>alert('Error updating database: " . htmlspecialchars(mysqli_error($connect)) . "');</script>";
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo "<script>alert('Error preparing statement: " . htmlspecialchars(mysqli_error($connect)) . "');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Session data unavailable. Please login and try again.'); window.location='../StdProfile.php';</script>";
    }
}
mysqli_close($connect);
?>

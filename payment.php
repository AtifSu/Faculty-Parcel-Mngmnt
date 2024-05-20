<?php
include('php/connect.php');
session_start();

if (isset($_POST['upload'])) {
    if (isset($_SESSION['AdminID'])) {
        // Retrieve form data
        $PaymentNumber = $_POST['PaymentNumber'];
        $PaymentBank = $_POST['PaymentBank'];

        // Handle image upload
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
                $newImageName = uniqid() . '.' . $imageExtension;

                if (!is_dir('payment')) {
                    mkdir('payment', 0777, true);
                }

                move_uploaded_file($tmpName, 'payment/' . $newImageName);

                // Insert payment details into the database
                $sql = "INSERT INTO Payment (PaymentNumber, PaymentImg, PaymentBank) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($connect, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $PaymentNumber, $newImageName, $PaymentBank);
                    $result = mysqli_stmt_execute($stmt);

                    if ($result) {
                        $_SESSION['uploaded_image'] = $newImageName; // Store image name in session
                        echo "<script>alert('Payment details inserted successfully!'); window.location='AdminPayment.php';</script>";
                    } else {
                        echo "<script>alert('Error inserting into database: " . htmlspecialchars(mysqli_error($connect)) . "');</script>";
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo "<script>alert('Error preparing statement: " . htmlspecialchars(mysqli_error($connect)) . "');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Session data unavailable. Please login and try again.'); window.location='AdminPayment.php';</script>";
    }
}
mysqli_close($connect);
?>

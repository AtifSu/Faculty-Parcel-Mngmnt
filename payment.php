<?php
include('php/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $PaymentNumber = $_POST['PaymentNumber'];
    $PaymentName = $_POST['PaymentName'];

    $targetDir = "../payment/";

    $targetFile = "";
    $uploadOk = 1;
    $imageFileType = "";
    $uploadErrorMsg = "";

    if (isset($_FILES['image'])) {

        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
        } else {
            $uploadOk = 0;
            $uploadErrorMsg .= "File is not an image. ";
        }

        if (file_exists($targetFile)) {
            $uploadOk = 0;
            $uploadErrorMsg .= "Sorry, file already exists. ";
        }

        if ($_FILES["image"]["size"] > 500000) {
            $uploadOk = 0;
            $uploadErrorMsg .= "Sorry, your file is too large. (Max: 500KB) ";
        }

        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            $uploadOk = 0;
            $uploadErrorMsg .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
        }

        // Proceed with file upload if all checks pass
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";

                $sql = "INSERT INTO Payment (PaymentNumber, PaymentBank, PaymentImg) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($connect, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $PaymentNumber, $PaymentName, mysqli_real_escape_string($connect, $targetFile));

                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Payment information inserted successfully!');</script>";
                    echo "<script>window.location.href = 'AdminPayment.php';</script>";
                } else {
                    echo "<script>alert('Error inserting payment information: " . mysqli_error($connect) . ". Please try again later.');</script>";
                    echo "<script>window.location.href = 'AdminPayment.php';</script>";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            if (!empty($uploadErrorMsg)) {
                echo "<script>alert('" . $uploadErrorMsg . "');</script>";
            }
        }
    } else {
        // Handle case where no image file is selected
        echo "<script>alert('Please select an image file to upload.');</script>";
        echo "<script>window.location.href = 'AdminPayment.php';</script>";
    }
}

mysqli_close($connect);

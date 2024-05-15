<?php
include('php/connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $AdminID = $_POST['AdminID'];
    $AdminEmail = $_POST['AdminEmail'];
    $AdminPass = $_POST['AdminPass'];

    error_log("AdminID: $AdminID");
    error_log("AdminEmail: $AdminEmail");
    error_log("AdminPass: $AdminPass");

    $sql = "UPDATE FSPAdmin SET AdminEmail = ?, AdminPass = ? WHERE AdminID = ?";
    $stmt = mysqli_prepare($connect, $sql);

    if (!$stmt) {
        error_log("Prepare failed: (" . $connect->errno . ") " . $connect->error);
        echo json_encode(array("success" => false, "message" => "Prepare failed: " . $connect->error));
        exit;
    }

    mysqli_stmt_bind_param($stmt, "ssi", $AdminEmail, $AdminPass, $AdminID);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(array("success" => true));
    } else {
        error_log("Update failed: (" . $connect->errno . ") " . $connect->error);
        echo json_encode(array("success" => false, "message" => "Update failed: " . $connect->error));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
}
?>

<?php
$link = mysqli_connect("localhost", "root", "");

if (!$link) {
    die('Could not connect: ' . mysqli_connect_error());
}

$mysql_query = "CREATE DATABASE IF NOT EXISTS fsp_db";

if ($link->query($mysql_query) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . $link->error . "<br>";
}

mysqli_select_db($link, "fsp_db") or die(mysqli_connect_error());

$mysql_query1 = "CREATE TABLE IF NOT EXISTS Student (
    StdID VARCHAR(10),
    StdName VARCHAR(50),
    StdEmail VARCHAR(50),
    StdPass VARCHAR(20),
    StdPhoneNum VARCHAR(20),
    StdImg VARCHAR(50),
    PRIMARY KEY(StdID)
)";

$mysql_query2 = "CREATE TABLE IF NOT EXISTS FSPAdmin (
    AdminID VARCHAR(10),
    AdminName VARCHAR(50),
    AdminEmail VARCHAR(50),
    AdminPass VARCHAR(20),
    AdminPhoneNum VARCHAR(20),
    AdminImg VARCHAR(50),
    PRIMARY KEY(AdminID)
)";

$mysql_query3 = "CREATE TABLE IF NOT EXISTS Parcel (
    ParcelID INT AUTO_INCREMENT,
    ParcelTrackingNum VARCHAR(50),
    ParcelCourier VARCHAR(20),
    ParcelStatus VARCHAR(50),
    ParcelArriveDate DATE,
    StdID VARCHAR(10),
    PRIMARY KEY(ParcelID),
    FOREIGN KEY(StdID) REFERENCES Student(StdID)
)";

$mysql_query4 = "CREATE TABLE IF NOT EXISTS Payment (
    PaymentID INT AUTO_INCREMENT,
    PaymentNumber VARCHAR(50),
    PaymentImg VARBINARY(256),
    PaymentBank VARCHAR(20),
    PRIMARY KEY(PaymentID)
)";

$mysql_query5 = "CREATE TABLE IF NOT EXISTS Appointment (
  AppointmentID INT AUTO_INCREMENT,
  AppointmentDate DATE,
  AppointmentTime VARCHAR(10),
  StdID VARCHAR(10),
  ParcelTrackingNum VARCHAR(50),
  PaymentReceipt VARBINARY(256),
  PRIMARY KEY(AppointmentID),
  FOREIGN KEY(StdID) REFERENCES Student(StdID)
)";

if (
    $link->query($mysql_query1) === TRUE &&
    $link->query($mysql_query2) === TRUE &&
    $link->query($mysql_query3) === TRUE &&
    $link->query($mysql_query4) === TRUE &&
    $link->query($mysql_query5) === TRUE 
) {
    echo "Tables created successfully.<br>";
    echo "<script>window.location.href = 'login.html';</script>";
} else {
    echo "Error creating tables: " . $link->error . "<br>";
}

$link->close();

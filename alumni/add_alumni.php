<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $education = $_POST['education'];
    $work_experience = $_POST['work_experience'];

    // Menambahkan data alumni ke database
    $sql = "INSERT INTO alumni (name, email, phone, education, work_experience) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $name, $email, $phone, $education, $work_experience);

    if ($stmt->execute()) {
        header("Location: alumni.php"); // Redirect ke halaman alumni setelah sukses
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

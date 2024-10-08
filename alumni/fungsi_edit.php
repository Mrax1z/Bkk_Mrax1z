<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data alumni berdasarkan ID
    $sql = "SELECT * FROM alumni WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $alumni = $result->fetch_assoc();

    if (!$alumni) {
        echo "Data alumni tidak ditemukan!";
        exit();
    }
} else {
    header("Location: alumni_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $education = $_POST['education'];
    $work_experience = $_POST['work_experience'];

    // Validasi data (misalnya format email atau nomor telepon)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Format email tidak valid!";
        exit();
    }
    
    if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        echo "Nomor telepon tidak valid!";
        exit();
    }

    // Update data alumni
    $sql = "UPDATE alumni SET name=?, email=?, phone=?, education=?, work_experience=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $email, $phone, $education, $work_experience, $id);

    if ($stmt->execute()) {
        // Set flash message untuk notifikasi berhasil
        $_SESSION['success_message'] = "Data alumni berhasil diupdate!";
        header("Location: alumni.php");
        exit();
    } else {
        echo "Terjadi kesalahan dalam mengupdate data!";
    }
}
?>

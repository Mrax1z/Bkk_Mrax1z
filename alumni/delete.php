<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Menghapus alumni berdasarkan ID
    $sql = "DELETE FROM alumni WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: alumni.php");
        exit();
    } else {
        echo "Terjadi kesalahan dalam menghapus data!";
    }
} else {
    header("Location: alumni.php");
    exit();
}
?>

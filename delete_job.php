<?php
session_start();
include 'includes/db.php';

// Pastikan ID pekerjaan disediakan
if (isset($_POST['id'])) {
    $job_id = $_POST['id'];

    // Query untuk menghapus pekerjaan
    $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
    $stmt->bind_param('i', $job_id);

    if ($stmt->execute()) {
        header('Location: dashboard.php'); // Redirect kembali ke dashboard
        exit;
    } else {
        echo "Failed to delete job. Please try again.";
    }
} else {
    echo "No job ID specified.";
}
?>

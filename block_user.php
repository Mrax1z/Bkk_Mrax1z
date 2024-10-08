<?php
session_start();
include 'includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: admin.php');
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Ambil status pengguna saat ini
    $sql = "SELECT status FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $new_status = ($user['status'] === 'blocked') ? 'free' : 'blocked';

        // Perbarui status pengguna
        $sql = "UPDATE users SET status = '$new_status' WHERE id = '$user_id'";
        if ($conn->query($sql) === TRUE) {
            header('Location: admin.php');
            exit;
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
} else {
    header('Location: admin.php');
    exit;
}
?>

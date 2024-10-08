<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';
include '../includes/header.php';

$alumni_id = $_GET['id'];
$sql = "SELECT * FROM alumni WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $alumni_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $alumni = $result->fetch_assoc();
} else {
    die("Alumni tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1><?php echo htmlspecialchars($alumni['name']); ?></h1>
    <p>Email: <?php echo htmlspecialchars($alumni['email']); ?></p>
    <p>Telepon: <?php echo htmlspecialchars($alumni['phone']); ?></p>
    <p>Pendidikan: <?php echo htmlspecialchars($alumni['education']); ?></p>
    <p>Pengalaman Kerja: <?php echo nl2br(htmlspecialchars($alumni['work_experience'])); ?></p>
    <p>Tanggal Dibuat: <?php echo htmlspecialchars($alumni['created_at']); ?></p>
    <a href="alumni.php" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>

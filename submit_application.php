<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit;
}

// Koneksi ke database
$host = "localhost";
$db = "loker_kerja";
$user = "root";
$pass = "";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari formulir
$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'];
$resume = $_FILES['resume'];

// Proses unggah file resume
$target_dir = "uploads/";
$resume_link = $target_dir . basename($resume["name"]);

if (move_uploaded_file($resume["tmp_name"], $resume_link)) {
    // Simpan data lamaran ke database
    $sql = "INSERT INTO applications (user_id, job_id, resume_link) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $job_id, $resume_link);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Lamaran berhasil dikirim!</div>";
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . $stmt->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Gagal mengunggah resume.</div>";
}

$conn->close();
?>

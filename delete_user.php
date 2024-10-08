<?php
session_start();
include 'includes/db.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header('Location: admin.php');
    exit;
}

// Ambil ID user dari URL
$user_id = $_GET['id'];

if (isset($_POST['confirm_delete'])) {
    // Hapus entri terkait di tabel jobs sebelum menghapus user
    $stmt_jobs = $conn->prepare("DELETE FROM jobs WHERE user_id = ?");
    $stmt_jobs->bind_param('i', $user_id);
    $stmt_jobs->execute(); // Eksekusi penghapusan entri jobs

    // Hapus user dari database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);

    if ($stmt->execute()) {
        header('Location: admin.php');
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <!-- Tambahkan SweetAlert2 CSS dan JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: "Apakah Anda yakin ingin menghapus pengguna ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            } else {
                window.location.href = 'admin.php';
            }
        });
    });
</script>

<!-- Form tersembunyi untuk menangani penghapusan pengguna -->
<form id="deleteForm" method="post">
    <input type="hidden" name="confirm_delete" value="1">
</form>

</body>
</html>

<?php include 'includes/footer.php'; ?>

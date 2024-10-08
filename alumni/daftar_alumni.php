<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';
include '../includes/header.php';

// Mengambil data alumni dari database
$sql = "SELECT * FROM alumni WHERE status = 'active'";
$result = $conn->query($sql);
$alumni = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alumni[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h1 class="mt-4">Daftar Alumni</h1>
    
    <div class="mb-3">
        <a href="#addAlumniModal" class="btn btn-primary" data-bs-toggle="modal">Tambah Alumni</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Pendidikan</th>
                <th>Pengalaman Kerja</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumni as $alum): ?>
            <tr>
                <td><?php echo htmlspecialchars($alum['id']); ?></td>
                <td><?php echo htmlspecialchars($alum['name']); ?></td>
                <td><?php echo htmlspecialchars($alum['email']); ?></td>
                <td><?php echo htmlspecialchars($alum['phone']); ?></td>
                <td><?php echo htmlspecialchars($alum['education']); ?></td>
                <td><?php echo htmlspecialchars($alum['work_experience']); ?></td>
                <td><?php echo htmlspecialchars($alum['created_at']); ?></td>
                <td>
                    <a href="edit_alumni.php?id=<?php echo $alum['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_alumni.php?id=<?php echo $alum['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus alumni ini?');">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal untuk menambah alumni -->
<div class="modal fade" id="addAlumniModal" tabindex="-1" aria-labelledby="addAlumniModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="add_alumni.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAlumniModalLabel">Tambah Alumni Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="education" class="form-label">Pendidikan</label>
                        <input type="text" class="form-control" id="education" name="education">
                    </div>
                    <div class="mb-3">
                        <label for="work_experience" class="form-label">Pengalaman Kerja</label>
                        <textarea class="form-control" id="work_experience" name="work_experience"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons"></script>
<script>
    feather.replace();
</script>
</body>
</html>

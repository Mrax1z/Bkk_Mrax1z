<?php 
include 'includes/header.php'; 

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header('Location: admin.php');
    exit;
}

// Ambil data pengguna dan urutkan berdasarkan role, admin terlebih dahulu
$result = $conn->query("SELECT * FROM users ORDER BY CASE WHEN role = 'admin' THEN 1 ELSE 2 END, role ASC");

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-3">
    <h1 class="h4">Admin Dashboard</h1>
</div>

<div class="mb-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td><?php echo $user['status']; ?></td>
                <td>
                    <a class="btn btn-sm btn-outline-primary" href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                    <a class="btn btn-sm btn-outline-danger" href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                    <?php if ($user['role'] !== 'admin'): // Hanya tampilkan tombol block/unblock jika bukan admin ?>
                        <?php if ($user['status'] === 'blocked'): ?>
                            <a class="btn btn-sm btn-outline-success" href="block_user.php?id=<?php echo $user['id']; ?>">Unblock</a>
                        <?php else: ?>
                            <a class="btn btn-sm btn-outline-warning" href="block_user.php?id=<?php echo $user['id']; ?>">Block</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
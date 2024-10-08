<?php

include 'includes/header.php'; // Koneksi ke database

// Ambil user_id dan user_role dari session
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Ambil halaman saat ini (default ke 1 jika tidak ada)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Limit 5 data per halaman
$offset = ($page - 1) * $limit;

// SQL dasar untuk mengambil lamaran pekerjaan dengan paginasi
$sql = "
    SELECT applications.id as app_id, applications.status, applications.applied_at, applications.resume_link, 
           users.username, jobs.title, jobs.company
    FROM applications
    JOIN users ON applications.user_id = users.id
    JOIN jobs ON applications.job_id = jobs.id";

// Admin dapat melihat semua lamaran
if ($user_role == 'admin') {
    $sql .= " ORDER BY applications.applied_at DESC LIMIT ? OFFSET ?";
} 
// Employer hanya dapat melihat lamaran untuk pekerjaan yang mereka posting
elseif ($user_role == 'employer') {
    $sql .= " WHERE jobs.user_id = ? ORDER BY applications.applied_at DESC LIMIT ? OFFSET ?";
}

// Siapkan statement SQL
$stmt = $conn->prepare($sql);

// Bind parameter
if ($user_role == 'employer') {
    $stmt->bind_param("iii", $user_id, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

// Eksekusi statement SQL
$stmt->execute();
$result = $stmt->get_result();

// Hitung total data untuk pagination
$count_sql = "
    SELECT COUNT(*) as total 
    FROM applications
    JOIN users ON applications.user_id = users.id
    JOIN jobs ON applications.job_id = jobs.id";

if ($user_role == 'employer') {
    $count_sql .= " WHERE jobs.user_id = ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("i", $user_id);
} else {
    $count_stmt = $conn->prepare($count_sql);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_data = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_data / $limit); // Menghitung jumlah total halaman

// Proses pembaruan status lamaran
// Proses pembaruan status lamaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['status'];
    
    // Query untuk mengupdate status lamaran
    $update_sql = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $application_id);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Status updated successfully.</div>";
        echo "<script>
                setTimeout(function() {
                    window.location.reload();
                }, 1500); // Refresh after 1.5 seconds
              </script>";
    } else {
        echo "<div class='alert alert-danger'>Failed to update status.</div>";
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Manage Job Applications</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No.</th>
                <th>Applicant</th>
                <th>Job Title</th>
                <th>Company</th>
                <th>Status</th>
                <th>Applied At</th>
                <th>Resume</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $no = $offset + 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['company']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['applied_at']) ?></td>
                    <td><a href="<?= htmlspecialchars($row['resume_link']) ?>" target="_blank">View Resume</a></td>
                    <td>
                        <form method="post" class="d-flex align-items-center">
                            <input type="hidden" name="application_id" value="<?= $row['app_id'] ?>">
                            <select name="status" class="form-select me-2">
                                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="reviewed" <?= $row['status'] == 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                                <option value="accepted" <?= $row['status'] == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                <option value="rejected" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No applications found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<?php include "includes/footer.php" ?>
</body>
</html>

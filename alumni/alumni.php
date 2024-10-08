<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $isBlocked = $user['status'] === 'blocked';
} else {
    $user = null;
    $isBlocked = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @media (max-width: 768px) {
            #sidebar {
                display: none;
                transition: transform 0.3s ease;
            }
            #sidebar.show {
                display: block;
                transform: translateX(0);
                position: fixed;
                z-index: 1000;
                width: 80%;
                height: 100%;
                background-color: #f8f9fa;
                box-shadow: 2px 0 5px rgba(0,0,0,0.5);
            }
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            .overlay.show {
                display: block;
            }
        }
        .btn-sidebar-toggle {
            display: none;
        }
        @media (max-width: 768px) {
            .btn-sidebar-toggle {
                display: inline-block;
            }
        }
        .main-content {
            min-height: calc(100vh - 120px); /* Adjust based on header/footer height */
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <button class="btn btn-primary btn-sidebar-toggle d-md-none" onclick="toggleSidebar()"><i data-feather="menu"></i></button>
        
        <div class="overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar p-3">
            <div class="d-flex flex-column align-items-center">
                <a href="dashboard.php" class="mb-3 d-flex align-items-center">
                    <img src="uploads/logosekolah.png" alt="Logo" class="img-fluid logo" style="width: 50px;">
                    <div class="ms-3 text-container">
                        <h4 class="mb-0">BKK</h4>
                        <small class="text-muted">SMK Negeri 02 Balikpapan</small>
                    </div>
                </a>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="../dashboard.php">
                            <i data-feather="home"></i>
                            <span class="ms-2">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="../profile.php">
                            <i data-feather="user"></i>
                            <span class="ms-2">Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'apply_job.php' ? 'active' : ''; ?>" href="../apply_job.php">
                            <i data-feather="check-circle"></i>
                            <span class="ms-2">Status</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_job.php' ? 'active' : ''; ?>" href="../add_job.php">
                            <i data-feather="plus-circle"></i>
                            <span class="ms-2">Post a Job</span>
                        </a>
                    </li>

                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : ''; ?>" href="../admin.php">
                            <i data-feather="users"></i>
                            <span class="ms-2">Manage Users</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'employer'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'lamaran.php' ? 'active' : ''; ?>" href="../lamaran.php">
                            <i data-feather="file-text"></i>
                            <span class="ms-2">Lamaran</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'alumni'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'alumni.php' ? 'active' : ''; ?>" href="alumni.php">
                            <i data-feather="star"></i>
                            <span class="ms-2">Alumni</span>
                        </a>
                    </li>
                    <?php endif; ?>

         

                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i data-feather="log-out"></i>
                            <span class="ms-2">Logout</span>
                        </a>
                    </li>
                </ul>
                <div class="profile text-center mt-auto">
                    <?php if ($user): ?>
                        <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="User Profile Picture" class="rounded-circle mb-2" style="width: 100px; height: 100px;">
                        <h5><?php echo htmlspecialchars($user['username']); ?></h5>
                        <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                    <?php else: ?>
                        <p>User data not available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3 main-content">
        <!-- Modal Static Backdrop untuk User yang Diblokir -->
        <?php if ($isBlocked): ?>
        <div class="modal fade" id="blockedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="blockedModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="blockedModalLabel">Account Blocked</h5>
                    </div>
                    <div class="modal-body">
                        Your account has been blocked. Please contact the administrator for more details.
                    </div>
                    <div class="modal-footer">
                        <a href="../logout.php" class="btn btn-primary">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var blockedModal = new bootstrap.Modal(document.getElementById('blockedModal'));
            blockedModal.show();
        </script>
        <?php endif; ?>



<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
    header("Location: ../login.php");
    exit();
}



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
    <?php
if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>{$_SESSION['success_message']}</div>";
    unset($_SESSION['success_message']); // Hapus setelah ditampilkan
} ?>
    <h1 class="mt-4">Daftar Alumni</h1>
    
    <div class="mb-3">
        <a href="#addAlumniModal" class="btn btn-primary" data-bs-toggle="modal">Tambah Alumni</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
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
        <?php
                    $no = 0;
                    foreach ($alumni as $alum):
                        $no++;
                    ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo htmlspecialchars($alum['name']); ?></td>
                            <td><?php echo htmlspecialchars($alum['email']); ?></td>
                            <td><?php echo htmlspecialchars($alum['phone']); ?></td>
                            <td><?php echo htmlspecialchars($alum['education']); ?></td>
                            <td><?php echo htmlspecialchars($alum['work_experience']); ?></td>
                            <td><?php echo htmlspecialchars($alum['created_at']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $alum['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete.php?id=<?php echo $alum['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus alumni ini?');">Hapus</a>
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

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
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
    <link rel="stylesheet" href="css/styles.css">
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
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                            <i data-feather="home"></i>
                            <span class="ms-2">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
                            <i data-feather="user"></i>
                            <span class="ms-2">Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'apply_job.php' ? 'active' : ''; ?>" href="apply_job.php">
                            <i data-feather="check-circle"></i>
                            <span class="ms-2">Status</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_job.php' ? 'active' : ''; ?>" href="add_job.php">
                            <i data-feather="plus-circle"></i>
                            <span class="ms-2">Post a Job</span>
                        </a>
                    </li>

                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : ''; ?>" href="admin.php">
                            <i data-feather="users"></i>
                            <span class="ms-2">Manage Users</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'employer'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'lamaran.php' ? 'active' : ''; ?>" href="lamaran.php">
                            <i data-feather="file-text"></i>
                            <span class="ms-2">Lamaran</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'alumni'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'alumni/alumni.php' ? 'active' : ''; ?>" href="alumni/alumni.php">
                            <i data-feather="star"></i>
                            <span class="ms-2">Alumni</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
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
                        <a href="logout.php" class="btn btn-primary">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var blockedModal = new bootstrap.Modal(document.getElementById('blockedModal'));
            blockedModal.show();
        </script>
        <?php endif; ?>

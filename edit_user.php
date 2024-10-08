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

// Ambil data user dari database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Inisialisasi variabel error
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    // Validasi input
    if (empty($username) || empty($email)) {
        $error = 'Please fill all required fields.';
    } else {
        // Update data user di database
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, status = ? WHERE id = ?");
        $stmt->bind_param('ssssi', $username, $email, $role, $status, $user_id);

        if ($stmt->execute()) {
            header('Location: admin.php');
            exit;
        } else {
            $error = 'Failed to update user. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 20px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit User</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" class="form-control">
                <option value="user" <?php if($user['role'] === 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                <option value="employer" <?php if($user['role'] === 'employer') echo 'selected'; ?>>Employer</option>
                <option value="job_seeker" <?php if($user['role'] === 'job_seeker') echo 'selected'; ?>>Job Seeker</option>
                <option value="guest" <?php if($user['role'] === 'guest') echo 'selected'; ?>>Guest</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" class="form-control">
                <option value="free" <?php if($user['status'] === 'free') echo 'selected'; ?>>Free</option>
                <option value="blocked" <?php if($user['status'] === 'blocked') echo 'selected'; ?>>Blocked</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include 'includes/footer.php'; ?>

</body>
</html>

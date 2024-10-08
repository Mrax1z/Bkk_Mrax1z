<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';
$user_id = $_SESSION['user_id'];

// Mendapatkan data user dari database
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Jika form disubmit, perbarui profil user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $education = $_POST['education'];
    $skills = $_POST['skills'];
    $experience = $_POST['experience'];
    
    // Jika user mengunggah foto baru
    if (!empty($_FILES['profile_picture']['name'])) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target = "uploads/" . basename($profile_picture);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target);
        $sql = "UPDATE users SET profile_picture='$profile_picture' WHERE id='$user_id'";
        $conn->query($sql);
    }
    
    $sql = "UPDATE users SET username='$username', email='$email', education='$education', skills='$skills', experience='$experience' WHERE id='$user_id'";
    if ($conn->query($sql)) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Profil Alumni</h1>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="username" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
        </div>
        <div class="mb-3">
            <label for="profile_picture" class="form-label">Foto Profil</label><br>
            <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" width="100" class="mb-2"><br>
            <input type="file" class="form-control" name="profile_picture">
        </div>
        <div class="mb-3">
            <label for="education" class="form-label">Riwayat Pendidikan</label>
            <textarea class="form-control" name="education"><?php echo htmlspecialchars($user['education']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="skills" class="form-label">Keterampilan</label>
            <textarea class="form-control" name="skills"><?php echo htmlspecialchars($user['skills']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="experience" class="form-label">Pengalaman Kerja</label>
            <textarea class="form-control" name="experience"><?php echo htmlspecialchars($user['experience']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
</body>
</html>

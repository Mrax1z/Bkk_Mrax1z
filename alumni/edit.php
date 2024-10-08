<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
    header("Location: login.php");
    
}

include '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Ambil data alumni berdasarkan ID
    $sql = "SELECT * FROM alumni WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $alumni = $result->fetch_assoc();

    if (!$alumni) {
        echo "Data alumni tidak ditemukan!";
        exit();
    }
} else {
    header("Location: alumni_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $education = $_POST['education'];
    $work_experience = $_POST['work_experience'];

    // Update data alumni
    $sql = "UPDATE alumni SET name=?, email=?, phone=?, education=?, work_experience=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $email, $phone, $education, $work_experience, $id);

    if ($stmt->execute()) {
        header("Location: alumni.php");
        exit();
    } else {
        echo "Terjadi kesalahan dalam mengupdate data!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Alumni</h2>
    <form action="fungsi_edit.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($alumni['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($alumni['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Telepon</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($alumni['phone']); ?>">
        </div>
        <div class="mb-3">
            <label for="education" class="form-label">Pendidikan</label>
            <input type="text" class="form-control" id="education" name="education" value="<?php echo htmlspecialchars($alumni['education']); ?>">
        </div>
        <div class="mb-3">
            <label for="work_experience" class="form-label">Pengalaman Kerja</label>
            <textarea class="form-control" id="work_experience" name="work_experience"><?php echo htmlspecialchars($alumni['work_experience']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>

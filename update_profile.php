<?php
session_start();
include 'includes/db.php';

$user_id = $_SESSION['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$bio = $_POST['bio'];

// Default path for profile picture
$profile_picture = 'default.png';

// Mengelola upload gambar
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file gambar
    $check = getimagesize($_FILES['profile_picture']['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Cek ukuran file (maksimal 5MB)
    if ($_FILES['profile_picture']['size'] > 5000000) {
        echo "File is too large.";
        $uploadOk = 0;
    }

    // Hanya izinkan format file tertentu
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
        echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // Cek jika $uploadOk diatur ke 0 oleh kesalahan
    if ($uploadOk == 0) {
        echo "Your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            $profile_picture = basename($_FILES['profile_picture']['name']);
        } else {
            echo "There was an error uploading your file.";
        }
    }
}

// Update user profile in the database
$sql = "UPDATE users SET username=?, email=?, bio=?, profile_picture=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssi', $username, $email, $bio, $profile_picture, $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Profile updated successfully!";
} else {
    $_SESSION['error'] = "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: profile.php");
exit();
?>

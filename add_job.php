<?php
ob_start();

include 'includes/header.php';

define('MAX_WIDTH', 600); // Lebar maksimum gambar
define('MAX_HEIGHT', 300); // Tinggi maksimum gambar

function resizeImage($file, $targetPath) {
    list($width, $height, $type) = getimagesize($file);
    $newWidth = $width;
    $newHeight = $height;

    // Menghitung rasio untuk menjaga proporsi gambar
    if ($width > MAX_WIDTH || $height > MAX_HEIGHT) {
        $widthRatio = MAX_WIDTH / $width;
        $heightRatio = MAX_HEIGHT / $height;
        $ratio = min($widthRatio, $heightRatio);

        $newWidth = $width * $ratio;
        $newHeight = $height * $ratio;
    }

    // Menentukan fungsi yang sesuai berdasarkan tipe gambar
    switch ($type) {
        case IMAGETYPE_JPEG:
            $src = imagecreatefromjpeg($file);
            break;
        case IMAGETYPE_PNG:
            $src = imagecreatefrompng($file);
            break;
        case IMAGETYPE_GIF:
            $src = imagecreatefromgif($file);
            break;
        default:
            throw new Exception("Unsupported image type.");
    }

    $dst = imagecreatetruecolor($newWidth, $newHeight);

    // Mengatur transparansi untuk PNG dan GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);
    }

    // Mengatur ukuran gambar
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Menyimpan gambar sesuai dengan tipe
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($dst, $targetPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($dst, $targetPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($dst, $targetPath);
            break;
    }

    imagedestroy($src);
    imagedestroy($dst);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['location']) && isset($_POST['company'])) {
        // Ambil data dari formulir
        $job_title = $_POST['title'];
        $job_description = $_POST['description'];
        $job_location = $_POST['location'];
        $company = $_POST['company'];
        $user_id = $_SESSION['user_id'];

        // Tangani unggahan gambar
        $image_name = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_name = basename($_FILES['image']['name']);
            $image_path = 'uploads/' . $image_name;

            // Resize gambar jika perlu
            resizeImage($image_tmp_name, $image_path);
        }

        // Validasi data
        if (!empty($job_title) && !empty($job_description) && !empty($job_location) && !empty($company)) {
            $sql = "INSERT INTO jobs (user_id, title, description, location, company, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $user_id, $job_title, $job_description, $job_location, $company, $image_name);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Job posted successfully!";
            } else {
                $_SESSION['error'] = "Error posting job: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $_SESSION['error'] = "All fields are required.";
        }
    } else {
        $_SESSION['error'] = "Required fields are missing.";
    }

    $conn->close();

    header("Location: dashboard.php");
    exit();
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-3">
    <h1 class="h4">Post a New Job</h1>
</div>

<form action="add_job.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title" class="form-label">Job Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="company" class="form-label">Company</label>
        <input type="text" class="form-control" id="company" name="company" required>
    </div>
    <div class="mb-3">
        <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control" id="location" name="location" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Job Description</label>
        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Job Image</label>
        <input type="file" class="form-control" id="image" name="image">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php
include 'includes/footer.php';
ob_end_flush();
?>
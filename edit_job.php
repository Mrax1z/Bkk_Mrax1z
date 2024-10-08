<?php
session_start();
include 'includes/db.php';

// Ambil ID dari query string
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data pekerjaan dari database berdasarkan ID
$sql = "SELECT * FROM jobs WHERE id = $id";
$result = $conn->query($sql);

// Periksa apakah data ditemukan
if ($result->num_rows > 0) {
    $job = $result->fetch_assoc();
} else {
    echo "<div class='alert alert-danger' role='alert'>Job not found.</div>";
    exit();
}

// Proses update data ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $location = $conn->real_escape_string($_POST['location']);
    $description = $conn->real_escape_string($_POST['description']);
    $image = $job['image']; // Default image

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger' role='alert'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            echo "<div class='alert alert-danger' role='alert'>Sorry, your file is too large.</div>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "<div class='alert alert-danger' role='alert'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<div class='alert alert-danger' role='alert'>Sorry, your file was not uploaded.</div>";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["image"]["name"]);
            } else {
                echo "<div class='alert alert-danger' role='alert'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    // Query update data
    $sql_update = "UPDATE jobs SET title='$title', company='$company', location='$location', description='$description', image='$image' WHERE id=$id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>Job updated successfully.</div>";
        header("Location: dashboard.php"); // Redirect ke dashboard setelah update
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error updating job: " . $conn->error . "</div>";
    }
}
?>

<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS (jika ada) -->
    <link href="path/to/your/custom.css" rel="stylesheet">
</head>
<body>
    <!-- Your Navbar or other components -->

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Edit Job</h3>
                </div>
                <div class="card-body">
                    <form action="edit_job.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Job Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($job['title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" name="company" id="company" class="form-control" value="<?php echo htmlspecialchars($job['company']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($job['location']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Job Description</label>
                            <textarea name="description" id="description" class="form-control" rows="5" required><?php echo htmlspecialchars($job['description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Job Image</label>
                            <input type="file" name="image" id="image" class="form-control-file">
                            <?php if ($job['image']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($job['image']); ?>" alt="Current Image" class="img-thumbnail mt-2" style="max-width: 200px;">
                            <?php endif; ?>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Update Job</button>
                            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include 'includes/footer.php'; ?>

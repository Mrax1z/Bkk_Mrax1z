<?php
include 'includes/header.php';

$selected_job_id = isset($_GET['job_id']) ? $_GET['job_id'] : null;
$jobs = $conn->query("SELECT id, title FROM jobs");
// Query untuk mengambil pekerjaan yang belum dilamar oleh user ini

?>

<div class="container mt-5">
    <h1 class="text-center">Formulir Lamaran Kerja</h1>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="submit_application.php" method="POST" enctype="multipart/form-data" class="border p-4 shadow rounded">
                <!-- Field user_id disembunyikan -->
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">

                <div class="mb-3">
    <label for="job_id" class="form-label">Pilih Pekerjaan:</label>
    <select id="job_id" name="job_id" class="form-select" required>
        <option value="">-- Pilih Pekerjaan --</option>
        <?php while ($row = $jobs->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>" <?= $selected_job_id == $row['id'] ? 'selected' : '' ?>>
                <?= $row['title'] ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>


                <div class="mb-3">
                    <label for="resume" class="form-label">Unggah Resume:</label>
                    <input type="file" class="form-control" id="resume" name="resume" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Lamar</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'includes/footer.php'; ?>

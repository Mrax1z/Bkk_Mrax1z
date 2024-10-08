<?php include 'includes/header.php'; 

// Ambil ID pekerjaan dari query string
$job_id = $_GET['id'];

// Ambil data pekerjaan dari database
$sql = "SELECT * FROM jobs WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$job) {
    echo "Job not found!";
    exit();
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-3">
    <h1 class="h4">Job Details</h1>
</div>

<div class="card">
    <?php if (!empty($job['image'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($job['image']); ?>" class="card-img-top" alt="Job Image" style="width: 50%; height: auto;">
    <?php endif; ?>
    <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
        <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($job['company']); ?></h6>
        <p class="card-text"><?php echo htmlspecialchars($job['description']); ?></p>
        <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($job['location']); ?></small></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

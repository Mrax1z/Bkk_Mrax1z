<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';
$user_id = $_SESSION['user_id'];

$message = "";

// Melamar pekerjaan (cek apakah sudah melamar)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    $job_id = (int)$_POST['job_id'];

    // Cek apakah sudah melamar pekerjaan ini
    $check_sql = "SELECT * FROM users WHERE job_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $job_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = 'You have already applied for this job.';
    } else {
        // Melamar pekerjaan
        $apply_sql = "INSERT INTO apply (job_id, user_id, status) VALUES (?, ?, 'pending')";
        $apply_stmt = $conn->prepare($apply_sql);
        $apply_stmt->bind_param("ii", $job_id, $user_id);

        if ($apply_stmt->execute()) {
            $message = 'Job application submitted successfully!';
        } else {
            $message = 'Failed to submit job application.';
        }
        $apply_stmt->close();
    }
    $check_stmt->close();
}

// Ambil daftar pekerjaan yang tersedia
$jobs_sql = "SELECT * FROM jobs";
$jobs_result = $conn->query($jobs_sql);

// Ambil daftar pekerjaan yang telah dilamar oleh user
$applied_jobs_sql = "SELECT j.job_title, a.status 
                     FROM apply a 
                     JOIN jobs j ON a.job_id = j.id 
                     WHERE a.user_id = ?";
$applied_jobs_stmt = $conn->prepare($applied_jobs_sql);
$applied_jobs_stmt->bind_param("i", $user_id);
$applied_jobs_stmt->execute();
$applied_jobs_result = $applied_jobs_stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Dashboard</title>
</head>
<body>

<h1>Job Seeker Dashboard</h1>

<?php if (!empty($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<!-- Daftar Lowongan Pekerjaan -->
<h2>Available Jobs</h2>
<table border="1">
    <tr>
        <th>Job Title</th>
        <th>Description</th>
        <th>Requirements</th>
        <th>Action</th>
    </tr>
    <?php while($job = $jobs_result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $job['job_title']; ?></td>
        <td><?php echo $job['job_description']; ?></td>
        <td><?php echo $job['job_requirements']; ?></td>
        <td>
            <form method="post" action="job_seeker_dashboard.php">
                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                <button type="submit">Apply</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<!-- Status Lamaran -->
<h2>Your Applications</h2>
<table border="1">
    <tr>
        <th>Job Title</th>
        <th>Status</th>
    </tr>
    <?php while($applied_job = $applied_jobs_result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $applied_job['job_title']; ?></td>
        <td><?php echo $applied_job['status']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

<?php
$applied_jobs_stmt->close();
$conn->close();
?>

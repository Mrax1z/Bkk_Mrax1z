<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'employer') {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';
$company_id = $_SESSION['user_id'];

$message = "";

// Posting Lowongan Pekerjaan
if (isset($_POST['action']) && $_POST['action'] == 'post_job') {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $job_requirements = $_POST['job_requirements'];

    $sql = "INSERT INTO jobs (company_id, job_title, job_description, job_requirements, created_at) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $company_id, $job_title, $job_description, $job_requirements);

    if ($stmt->execute()) {
        $message = 'Job posted successfully!';
    } else {
        $message = 'Failed to post job.';
    }
    $stmt->close();
}

// Mengedit Lowongan Pekerjaan
if (isset($_POST['action']) && $_POST['action'] == 'edit_job') {
    $job_id = $_POST['job_id'];
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $job_requirements = $_POST['job_requirements'];

    $sql = "UPDATE jobs SET job_title = ?, job_description = ?, job_requirements = ? WHERE id = ? AND company_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $job_title, $job_description, $job_requirements, $job_id, $company_id);

    if ($stmt->execute()) {
        $message = 'Job updated successfully!';
    } else {
        $message = 'Failed to update job.';
    }
    $stmt->close();
}

// Menghapus Lowongan Pekerjaan
if (isset($_GET['action']) && $_GET['action'] == 'delete_job') {
    $job_id = (int)$_GET['job_id'];

    $sql = "DELETE FROM jobs WHERE id = ? AND company_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $company_id);

    if ($stmt->execute()) {
        $message = 'Job deleted successfully!';
    } else {
        $message = 'Failed to delete job.';
    }
    $stmt->close();
}

// Mengelola Informasi Perusahaan
if (isset($_POST['action']) && $_POST['action'] == 'update_company') {
    $company_name = $_POST['company_name'];
    $company_description = $_POST['company_description'];

    $sql = "UPDATE companies SET company_name = ?, company_description = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $company_name, $company_description, $company_id);

    if ($stmt->execute()) {
        $message = 'Company information updated!';
    } else {
        $message = 'Failed to update company information.';
    }
    $stmt->close();
}

// Penyeleksian Pelamar
if (isset($_POST['action']) && $_POST['action'] == 'select_applicant') {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['status'];

    $sql = "UPDATE apply SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $application_id);

    if ($stmt->execute()) {
        $message = 'Application status updated!';
    } else {
        $message = 'Failed to update application status.';
    }
    $stmt->close();
}

// Melihat Pelamar
$sql = "SELECT a.id, a.job_id, u.name AS applicant_name, u.email AS applicant_email, a.status 
        FROM apply a 
        JOIN users u ON a.user_id = u.id 
        JOIN jobs j ON a.job_id = j.id 
        WHERE j.company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$apply_result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
</head>
<body>

<h1>Employer Dashboard</h1>

<?php if (!empty($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<!-- Form untuk Posting Lowongan Pekerjaan -->
<h2>Post a New Job</h2>
<form method="post" action="employer_dashboard.php">
    <input type="hidden" name="action" value="post_job">
    <label>Job Title:</label>
    <input type="text" name="job_title" required><br>
    <label>Job Description:</label>
    <textarea name="job_description" required></textarea><br>
    <label>Job Requirements:</label>
    <textarea name="job_requirements" required></textarea><br>
    <button type="submit">Post Job</button>
</form>

<!-- Form untuk Mengelola Informasi Perusahaan -->
<h2>Update Company Information</h2>
<form method="post" action="employer_dashboard.php">
    <input type="hidden" name="action" value="update_company">
    <label>Company Name:</label>
    <input type="text" name="company_name" required><br>
    <label>Company Description:</label>
    <textarea name="company_description" required></textarea><br>
    <button type="submit">Update Company</button>
</form>

<!-- Tabel Pelamar -->
<h2>Applicants</h2>
<table border="1">
    <tr>
        <th>Job ID</th>
        <th>Applicant Name</th>
        <th>Applicant Email</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while($row = $apply_result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['job_id']; ?></td>
        <td><?php echo $row['applicant_name']; ?></td>
        <td><?php echo $row['applicant_email']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
            <form method="post" action="employer_dashboard.php">
                <input type="hidden" name="action" value="select_applicant">
                <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                <select name="status">
                    <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="accepted" <?php echo $row['status'] == 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                    <option value="rejected" <?php echo $row['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
                <button type="submit">Update Status</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

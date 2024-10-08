<?php include 'includes/header.php'; ?>

<!-- Main container -->

<!-- Filter Form -->
<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="" class="d-flex align-items-center">
                    <div class="input-group">
                        <select name="filter" class="form-select">
                            <option value="all" <?= isset($_GET['filter']) && $_GET['filter'] == 'all' ? 'selected' : '' ?>>All Jobs</option>
                            <option value="applied" <?= isset($_GET['filter']) && $_GET['filter'] == 'applied' ? 'selected' : '' ?>>Applied Jobs</option>
                            <option value="not_applied" <?= isset($_GET['filter']) && $_GET['filter'] == 'not_applied' ? 'selected' : '' ?>>Not Applied Jobs</option>
                        </select>
                        <button class="btn btn-primary ms-2" type="submit">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Job Listings -->
<div class="row">
    <?php
    $limit = 6; // Limit to 6 posts per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $query = "SELECT * FROM jobs";

    if ($filter == 'applied') {
        // Show only applied jobs
        $query = "SELECT jobs.* FROM jobs 
                  JOIN applications ON jobs.id = applications.job_id 
                  WHERE applications.user_id = ? LIMIT ? OFFSET ?";
    } elseif ($filter == 'not_applied') {
        // Show only not applied jobs
        $query = "SELECT jobs.* FROM jobs 
                  WHERE jobs.id NOT IN (SELECT job_id FROM applications WHERE user_id = ?) LIMIT ? OFFSET ?";
    } else {
        // Default to all jobs
        $query .= " LIMIT ? OFFSET ?";
    }

    $stmt = $conn->prepare($query);

    if ($filter == 'applied' || $filter == 'not_applied') {
        $stmt->bind_param("iii", $user_id, $limit, $offset);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($job = $result->fetch_assoc()) {
            ?>
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><a href="view_job.php?id=<?= $job['id']; ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($job['title']); ?></a></h5>
                        <p class="card-text"><?= htmlspecialchars($job['company']); ?> - <?= htmlspecialchars($job['location']); ?></p>
                        <p class="card-text"><small class="text-muted">Posted on <?= htmlspecialchars($job['created_at']); ?></small></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <?php
                            // Check if applied and get the status
                            $check_applied_sql = "SELECT status FROM applications WHERE user_id = ? AND job_id = ?";
                            $stmt2 = $conn->prepare($check_applied_sql);
                            $stmt2->bind_param("ii", $user_id, $job['id']);
                            $stmt2->execute();
                            $applied_result = $stmt2->get_result();
                            
                            if ($applied_result->num_rows > 0) {
                                $application = $applied_result->fetch_assoc();
                                $status = $application['status'];
                            
                                // Display button based on application status
                                if ($status == 'accepted') {
                                    echo "<button class='btn btn-success' disabled>Accepted</button>";
                                } elseif ($status == 'rejected') {
                                    echo "<button class='btn btn-danger' disabled>Rejected</button>";
                                } elseif ($status == 'reviewed') {
                                    echo "<button class='btn btn-warning' disabled>Being Reviewed</button>";
                                } else {
                                    echo "<button class='btn btn-secondary' disabled>Pending</button>";
                                }
                            } else {
                                // If not applied, show the Apply button
                            echo "<a href='apply.php?job_id=" . $job['id'] . "' class='btn btn-info'>Apply</a>";

                            }
                            ?>
                            <a href="view_job.php?id=<?= $job['id']; ?>" class="btn btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='col-12'><p class='text-center'>No jobs available.</p></div>";
    }
    ?>

    <!-- Pagination -->
    <div class="col-12">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                // Get total job count for pagination
                $count_query = "SELECT COUNT(*) as total FROM jobs";
                if ($filter == 'applied') {
                    $count_query = "SELECT COUNT(*) as total FROM jobs 
                                    JOIN applications ON jobs.id = applications.job_id 
                                    WHERE applications.user_id = ?";
                } elseif ($filter == 'not_applied') {
                    $count_query = "SELECT COUNT(*) as total FROM jobs 
                                    WHERE jobs.id NOT IN (SELECT job_id FROM applications WHERE user_id = ?)";
                }

                $count_stmt = $conn->prepare($count_query);
                if ($filter == 'applied' || $filter == 'not_applied') {
                    $count_stmt->bind_param("i", $user_id);
                }
                $count_stmt->execute();
                $count_result = $count_stmt->get_result();
                $total_jobs = $count_result->fetch_assoc()['total'];
                $total_pages = ceil($total_jobs / $limit);

                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<li class="page-item' . ($page == $i ? ' active' : '') . '">';
                    echo '<a class="page-link" href="?page=' . $i . '&filter=' . $filter . '">' . $i . '</a>';
                    echo '</li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</div>

<!-- Feather Icons -->
<script>
    feather.replace();
</script>

</body>
</html>

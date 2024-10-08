<?php include 'includes/header.php' ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-3">
            <h1 class="h4">Home</h1>
        </div>

        <div class="mb-3">
            <form action="dashboard.php" method="get" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search jobs..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </form>
        </div>

        <h3 class="h5">Latest Job Listings</h3>

        <?php
        $jobs_per_page = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start_from = ($page - 1) * $jobs_per_page;

        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

        $where_clause = '';
        if ($search) {
            $where_clause = "WHERE title LIKE '%$search%' OR company LIKE '%$search%' OR location LIKE '%$search%'";
        }

        $sql = "SELECT * FROM jobs $where_clause ORDER BY created_at DESC LIMIT $start_from, $jobs_per_page";
        $result = $conn->query($sql);

        $current_user_id = $_SESSION['user_id'];
        $current_user_role = $_SESSION['role'];

        if ($result->num_rows > 0) {
            while($job = $result->fetch_assoc()) {
                echo "<div class='card mb-3'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'><a href='view_job.php?id=".$job['id']."'>".$job['title']."</a></h5>";
                echo "<p class='card-text'>".$job['company']." - ".$job['location']."</p>";
                echo "<p class='card-text'><small class='text-muted'>Posted on ".$job['created_at']."</small></p>";

                if ($current_user_role == 'admin' || $job['user_id'] == $current_user_id) {
                    echo "<form class='delete-form' method='POST' action='delete_job.php' style='display:inline;'>";
                    echo "<input type='hidden' name='id' value='".$job['id']."'>";
                    echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
                    echo "</form> ";
                }

                if ($current_user_role == 'admin' || $job['user_id'] == $current_user_id) {
                    echo "<a href='edit_job.php?id=".$job['id']."' class='btn btn-primary btn-sm'>Edit</a>";
                }

                if ($current_user_role != 'admin' && $job['user_id'] != $current_user_id) {
                    echo "<form method='POST' action='apply_job.php' style='display:inline;'>";
                    echo "<input type='hidden' name='job_id' value='".$job['id']."'>";
                    echo "<button type='submit' class='btn btn-success btn-sm'>Apply</button>";
                    echo "</form> ";
                }
                

                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No jobs available.</p>";
        }

        $sql_total = "SELECT COUNT(*) AS total FROM jobs $where_clause";
        $result_total = $conn->query($sql_total);
        $row_total = $result_total->fetch_assoc();
        $total_jobs = $row_total['total'];
        $total_pages = ceil($total_jobs / $jobs_per_page);

        echo "<div class='text-center mt-4'>";
        echo "<nav>";
        echo "<ul class='pagination justify-content-center'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<li class='page-item".($i == $page ? " active" : "")."'>";
            echo "<a class='page-link' href='dashboard.php?page=$i&search=$search'>$i</a>";
            echo "</li>";
        }
        echo "</ul>";
        echo "</nav>";
        echo "</div>";
        ?>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        
                        Swal.fire({
                            title: 'Konfirmasi Penghapusan',
                            text: "Apakah Anda yakin ingin menghapus pekerjaan ini?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Hapus',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });

            function toggleSidebar() {
                var sidebar = document.getElementById('sidebar');
                var overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        </script>
        </main>
    </div>
</div>

<script>
    feather.replace();
</script>

<footer class="bg-light text-center text-lg-start mt-auto py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
              
            </div>
        </div>
    </div>
</footer>

</body>
</html>
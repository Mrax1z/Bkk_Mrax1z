<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = $_POST['job_id'];
    $user_id = $_SESSION['user_id'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO comments (job_id, user_id, comment) VALUES ('$job_id', '$user_id', '$comment')";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_job.php?id=$job_id");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

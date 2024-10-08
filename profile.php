<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-3">
    <h1 class="h4">User Profile</h1>
</div>

<div class="card mb-3">
    <div class="card-body text-center">
        <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="User Profile Picture" class="rounded-circle mb-2" style="width: 100px; height: 100px;">
        <h5><?php echo $user['username']; ?></h5>
        <p class="text-muted"><?php echo $user['email']; ?></p>
        <p><?php echo $user['bio']; ?></p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit Profile</h5>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo $user['bio']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

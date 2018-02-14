<?php
    require_once('include/pdo.php');
    require_once('include/common.php');

    function get_profile_image_url($profile) {
        return $profile['image_url'] ? htmlentities($profile['image_url']) : NO_IMG_AVA;
    }

    session_start();

    if (!($profile = get_profile_data())) {
        $_SESSION['error'] = 'Could not load profile';
        header('Location: index.php');
        return;
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Arseni Rynkevich - Resume Registry, View Profile</title>
    <?php require_once 'include/bootstrap.php'; ?>
</head>

<body>
    <div class='container'>
        <h1>Profile Information</h1>
        <p><?= '<img src="' . get_profile_image_url($profile) . '" class="img-rounded" width="150">' ?></p>
        <p><b>First Name:</b> <?= htmlentities($profile['first_name']); ?></p>
        <p><b>Last Name:</b> <?= htmlentities($profile['last_name']); ?></p>
        <p><b>Email:</b> <?= htmlentities($profile['email']); ?></p>
        <p><b>Headline:</b></p>
        <p><?= htmlentities($profile['headline']); ?></p>
        <p><b>Summary:</b></p>
        <p><?= htmlentities($profile['summary']); ?></p>
        <p><a href="/index.php">Done</a></p>
    </div>
</body>

</html>

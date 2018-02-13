<?php
    require_once('pdo.php');
    require_once('common.php');

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
    <?php require_once 'bootstrap.php'; ?>
</head>

<body>
    <div class='container'>
        <h1>Profile Information</h1>
        <p><b>First Name:</b> <?= htmlentities($profile['first_name']); ?></p>
        <p><b>Last Name:</b> <?= htmlentities($profile['last_name']); ?></p>
        <p><b>Email:</b> <?= htmlentities($profile['email']); ?></p>
        <p><b>Headline:</b><br><?= htmlentities($profile['headline']); ?></p>
        <p><b>Summary:</b><br><?= htmlentities($profile['summary']); ?></p>
        <a href="/index.php">Done</a>
    </div>
</body>

</html>

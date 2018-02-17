<?php
    require_once('include/pdo.php');
    require_once('include/common.php');
    require_once('include/errors.php');

    function get_profile_image_url($profile) {
        return $profile['image_url'] ? htmlentities($profile['image_url']) : NO_IMG_AVA;
    }

    function show_positions($profile_id) {
        $positions = get_profile_positions($profile_id);

        $position = $positions->fetch(PDO::FETCH_ASSOC);
        if ($position) {
            echo '<label>Positions:</label>';
            echo '<ul>';
            do {
                echo '<li>' . htmlentities($position['year']) . ': ' .
                    htmlentities($position['description']) . '</li>';
            } while ($position = $positions->fetch(PDO::FETCH_ASSOC));
            echo '</ul>';
        }
    }

    session_start();

    if (!($profile = get_profile_data())) {
        $_SESSION['error'] = E_INVALID_PROFILE_ID;
        header('Location: index.php');
        return;
    }
?>

<!DOCTYPE html>
<html>

<head>
  <title>Arseni Rynkevich - Resume Registry, View Profile</title>
  <?php require_once 'styles/bootstrap.php'; ?>
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
    <?php show_positions($profile['profile_id']); ?>
    <p><a href="/index.php">Done</a></p>
  </div>
</body>

</html>

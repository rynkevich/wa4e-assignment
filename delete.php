<?php
    require_once('include/pdo.php');
    require_once('include/common.php');

    session_start();

    if (isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    if (!is_logged()) {
        die('ACCESS DENIED');
    }

    if (!($profile = get_profile_data())) {
        $_SESSION['error'] = 'Could not load profile';
        header('Location: index.php');
        return;
    }

    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare('DELETE FROM profile WHERE profile_id = :id');
        $stmt->execute(array(':id' => $_REQUEST['profile_id']));
        $_SESSION['success'] = 'Profile deleted';
        header('Location: index.php');
        return;
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Arseni Rynkevich - Resume Registry, Delete Profile</title>
    <?php require_once 'include/bootstrap.php'; ?>
</head>

<body>
    <div class='container'>
        <h1>Deleteing Profile</h1>
        <p><b>First Name:</b> <?= htmlentities($profile['first_name']); ?></p>
        <p><b>Last Name:</b> <?= htmlentities($profile['last_name']); ?></p>
        <form method="POST">
            <input type="submit" name="delete" value="Delete">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>

</html>

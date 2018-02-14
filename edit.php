<?php
    require_once('include/pdo.php');
    require_once('include/common.php');

    function edit_entry() {
        if (empty_field_found()) {
            $_SESSION['error'] = 'All fields are required';
            return;
        } else if (!is_valid_email($_POST['email'])) {
            $_SESSION['error'] = 'Email address must contain @';
            return;
        }

        global $pdo;
        $stmt = $pdo->prepare('UPDATE profile SET user_id = :uid, first_name = :first,
            last_name = :last, email = :em, headline = :hl, summary = :sum
            WHERE profile_id = :id');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':id' => $_REQUEST['profile_id'],
            ':first' => $_POST['first_name'],
            ':last' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':hl' => $_POST['headline'],
            ':sum' => $_POST['summary'])
        );
        $_SESSION['success'] = 'Profile edited';
    }

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
    }

    if (is_profiles_request()) {
        edit_entry();
        if (isset($_SESSION['success'])) {
            header('Location: index.php');
        } else {
            header('Location: edit.php?profile_id=' . $_REQUEST['profile_id']);
        }
        return;
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Arseni Rynkevich - Resume Registry, Edit Profile</title>
    <?php require_once 'include/bootstrap.php'; ?>
</head>

<body>
    <div class='container'>
        <h1>Editing Profile for <?php echo htmlentities($_SESSION['name']) ?></h1>
        <?php
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form method="POST">
            <label for="edt_first"><span style="font-weight:normal;">First Name:</span></label>
            <input type="text" name="first_name" id="edt_first" value="<?= htmlentities($profile['first_name']); ?>"><br>

            <label for="edt_last"><span style="font-weight:normal;">Last Name:</span></label>
            <input type="text" name="last_name" id="edt_last" value="<?= htmlentities($profile['last_name']); ?>"><br>

            <label for="edt_email"><span style="font-weight:normal;">Email:</span></label>
            <input type="text" name="email" id="edt_email" value="<?= htmlentities($profile['email']); ?>"><br>

            <label for="edt_headline"><span style="font-weight:normal;">Headline:</span></label>
            <input type="text" name="headline" id="edt_headline" value="<?= htmlentities($profile['headline']); ?>"><br>

            Summary:<br>
            <textarea name="summary" rows="8" cols="80"><?= htmlentities($profile['summary']); ?></textarea><br>

            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>

</html>

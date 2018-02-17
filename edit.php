<?php
    require_once('include/pdo.php');
    require_once('include/common.php');

    function edit_entry() {
        if (is_ok_field_size()) {
            $_SESSION['error'] = 'All fields are required';
            return;
        } else if (!empty($_POST['image_url']) && !url_exists($_POST['image_url'])) {
            $_SESSION['error'] = 'Invalid URL';
            return;
        } else if (!is_valid_email($_POST['email'])) {
            $_SESSION['error'] = 'Email address must contain @';
            return;
        }

        global $pdo;
        $stmt = $pdo->prepare('UPDATE profiles SET user_id = :uid, image_url = :img,
            first_name = :first, last_name = :last, email = :em, headline = :hl, summary = :sum
            WHERE profile_id = :id');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':img' => $_POST['image_url'],
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
        return;
    }

    if ($_SESSION['user_id'] != $profile['user_id']) {
        $_SESSION['error'] = 'You are not allowed to edit this profile';
        header('Location: index.php');
        return;
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
      <p>
        <label for="edt_first">First Name:</label>
        <input type="text" name="first_name" id="edt_first" value="<?= htmlentities($profile['first_name']); ?>">
      </p>
      <p>
        <label for="edt_last">Last Name:</label>
        <input type="text" name="last_name" id="edt_last" value="<?= htmlentities($profile['last_name']); ?>">
      </p>
      <p>
        <label for="edt_image_url">Image URL:</label>
        <input type="text" name="image_url" id="edt_image_url" value="<?= $profile['image_url'] == NO_IMG_AVA ? '' : htmlentities($profile['image_url']); ?>">
      </p>
      <p>
        <label for="edt_email">Email:</label>
        <input type="text" name="email" id="edt_email" value="<?= htmlentities($profile['email']); ?>">
      </p>
      <p>
        <label for="edt_headline">Headline:</label>
        <input type="text" name="headline" id="edt_headline" value="<?= htmlentities($profile['headline']); ?>">
      </p>
      <p><label for="txt_summary">Summary:</label></p>
      <p><textarea name="summary" rows="8" cols="80" id="txt_summary"><?= htmlentities($profile['summary']); ?></textarea></p>
      <p>
        <input type="submit" value="Save">
        <input type="submit" name="cancel" value="Cancel">
      </p>
    </form>
  </div>
</body>

</html>

<?php
    require_once('include/pdo.php');
    require_once('include/common.php');

    function add_entry() {
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
        $stmt = $pdo->prepare('INSERT INTO profiles (user_id, image_url, first_name, last_name,
            email, headline, summary) VALUES (:uid, :img, :first, :last, :em, :hl, :sum)');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':img' => $_POST['image_url'],
            ':first' => $_POST['first_name'],
            ':last' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':hl' => $_POST['headline'],
            ':sum' => $_POST['summary'])
        );
        $_SESSION['success'] = 'Profile added';
    }

    session_start();

    if (isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    if (!is_logged()) {
        die('ACCESS DENIED');
    }

    if (is_profiles_request()) {
        add_entry();
        if (isset($_SESSION['success'])) {
            header('Location: index.php');
        } else {
            header('Location: add.php');
        }
        return;
    }
?>

<!DOCTYPE html>
<html>

<head>
  <title>Arseni Rynkevich - Resume Registry, Add New</title>
  <?php require_once 'include/bootstrap.php'; ?>
</head>

<body>
  <div class='container'>
    <h1>Adding Profile for <?php echo htmlentities($_SESSION['name']) ?></h1>
    <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
    ?>
    <form method="POST">
      <p>
        <label for="edt_first">First Name:</label>
        <input type="text" name="first_name" id="edt_first">
      </p>
      <p>
        <label for="edt_last">Last Name:</label>
        <input type="text" name="last_name" id="edt_last">
      </p>
      <p>
        <label for="edt_image_url">Image URL:</label>
        <input type="text" name="image_url" id="edt_image_url">
      </p>
      <p>
        <label for="edt_email">Email:</label>
        <input type="text" name="email" id="edt_email">
      </p>
      <p>
        <label for="edt_headline">Headline:</label>
        <input type="text" name="headline" id="edt_headline">
      </p>
      <p><label for="txt_summary">Summary:</label></p>
        <p><textarea name="summary" rows="8" cols="80" id="txt_summary"></textarea></p>
        <p>
          <input type="submit" value="Add">
          <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
  </div>
</body>

</html>

<?php
    require_once 'include/pdo.php';
    require_once 'include/common.php';
    require_once 'include/errors.php';
    require_once 'include/position_queries.php';

    function edit_entry() {
        if (!is_ok_field_size()) {
            $_SESSION['error'] = E_PROFILE_BLANK_FIELD;
            return;
        } else if (!empty($_POST['image_url']) && !url_exists($_POST['image_url'])) {
            $_SESSION['error'] = E_INVALID_URL;
            return;
        } else if (!is_valid_email($_POST['email'])) {
            $_SESSION['error'] = E_INVALID_EMAIL;
            return;
        } else if (!validate_positions()) {
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
        edit_positions($_REQUEST['profile_id']);
        $_SESSION['success'] = 'Profile edited';
    }

    function edit_positions($profile_id) {
        $all_positions = get_profile_positions($profile_id)->fetchAll();

        for ($pos = 0; $pos < MAX_POS_ENTRIES; $pos++) {
            if (isset($_POST['year' . ($pos + 1)]) && isset($_POST['desc' . ($pos + 1)])) {
                if (isset($_POST['update_pos_' . ($pos + 1)]) && isset($all_positions[$pos])) {
                    if (is_modified_position($all_positions[$pos], $pos + 1)) {
                        sql_update_position($profile_id, $_POST['year' . ($pos + 1)],
                            $_POST['desc' . ($pos + 1)], $all_positions[$pos]['position_id']);
                    }
                } else {
                    if (isset($all_positions[$pos])) {
                        sql_delete_position($all_positions[$pos]['position_id']);
                    }
                    sql_insert_position($profile_id, $_POST['year' . ($pos + 1)],
                        $_POST['desc' . ($pos + 1)]);
                }
            } else if (isset($all_positions[$pos])) {
                sql_delete_position($all_positions[$pos]['position_id']);
            }
        }
    }

    function is_modified_position(&$position_data, $pos_num) {
        return $position_data['year'] != $_POST['year' . $pos_num] ||
            $position_data['description'] != $_POST['desc' . $pos_num];
    }

    function show_editable_positions($profile_id, &$pos_count) {
        $positions = get_profile_positions($profile_id);

        $pos_count = 0;
        while ($position = $positions->fetch(PDO::FETCH_ASSOC)) {
            $pos_count++;
            echo '<div id="blck_year' . $pos_count . '"><p><label for="edt_year' .
                $pos_count . '">Year:&nbsp;</label>' .
                '<input type="text" name="year' . $pos_count .
                '" id="edt_year' . $pos_count . '" value="' .
                htmlentities($position['year']) . '">' .
                '<input type="button" value="-" onclick="deletePositionField(' .
                $pos_count . ');"></p></div>';
            echo '<div id="blck_desc' . $pos_count .
                '"><p><textarea name="desc' . $pos_count .
                '" id="txt_desc' . $pos_count .
                '" rows="8" cols="80">' . htmlentities($position['description']) .
                '</textarea>';
            echo '<input type="hidden" name="update_pos_' . $pos_count .
                '"></p></div>';
        }
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
        $_SESSION['error'] = E_NO_PRIVILEGES_TO_EDIT;
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
  <?php require_once 'styles/inc_styles.php'; ?>
  <?php require_once 'include/inc_jquery.php'; ?>
  <script src="js/position-handler.js"></script>
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
      <div id="blck_positions">
        <p>
          <label for="btn_add_position">Position:</label>
          <input type="button" id="btn_add_position" value="+" onclick="addPositionField();">
          <?php show_editable_positions($profile['profile_id'], $pos_count); ?>
          <input type="hidden" id="hint_max_pos_entries" value="<?= MAX_POS_ENTRIES ?>">
          <input type="hidden" id="hint_initial_pos_entries_count" value="<?= $pos_count ?>">
        </p>
      </div>
      <p>
        <input type="submit" value="Save">
        <input type="submit" name="cancel" value="Cancel">
      </p>
    </form>
  </div>
</body>

</html>

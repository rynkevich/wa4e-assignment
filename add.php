<?php
    require_once('include/pdo.php');
    require_once('include/common.php');

    function add_entry() {
        if (empty_field_found()) {
            $_SESSION['error'] = 'All fields are required';
            return;
        } else if (!is_valid_email($_POST['email'])) {
            $_SESSION['error'] = 'Email address must contain @';
            return;
        }

        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO profile (user_id, first_name, last_name,
            email, headline, summary) VALUES (:uid, :first, :last, :em, :hl, :sum)');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
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
            <label for="edt_first"><span style="font-weight:normal;">First Name:</span></label>
            <input type="text" name="first_name" id="edt_first"><br>

            <label for="edt_last"><span style="font-weight:normal;">Last Name:</span></label>
            <input type="text" name="last_name" id="edt_last"><br>

            <label for="edt_email"><span style="font-weight:normal;">Email:</span></label>
            <input type="text" name="email" id="edt_email"><br>

            <label for="edt_headline"><span style="font-weight:normal;">Headline:</span></label>
            <input type="text" name="headline" id="edt_headline"><br>

            Summary:<br>
            <textarea name="summary" rows="8" cols="80"></textarea><br>

            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>

</html>

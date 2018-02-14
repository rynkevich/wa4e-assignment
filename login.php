<?php
    require_once('include/common.php');
    require_once('include/pdo.php');

    function login() {
        if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
            $_SESSION['error'] = 'User name and password are required';
            return;
        } else if (!is_valid_email($_POST['email'])) {
            $_SESSION['error'] = 'Email must have an at-sign (@)';
            return;
        }

        $userdata = get_userdata($_POST['email'], $_POST['pass']);
        if (!$userdata) {
            $_SESSION['error'] = 'Incorrect password';
            error_log('Login fail ' . htmlentities($_POST['email']) . ' ' . get_hash($_POST['pass']));
        } else {
            $_SESSION['name'] = $userdata['name'];
            $_SESSION['user_id'] = $userdata['user_id'];
            error_log('Login success ' . htmlentities($_POST['email']));
        }
    }

    function get_userdata($email, $password) {
        global $pdo;

        $hash = get_hash($password);
        $selection = $pdo->prepare('SELECT user_id, name FROM users
            WHERE email = :em AND password = :pw');
        $selection->execute(array(
            ':em' => $email,
            ':pw' => $hash)
        );

        return $selection->fetch(PDO::FETCH_ASSOC);
    }

    function get_hash($password) {
        $salt = 'XyZzy12*_';
        return hash('md5', $salt . $password);
    }

    session_start();

    if (isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    if (isset($_POST['email']) && isset($_POST['pass'])) {
        unset($_SESSION['name']);
        unset($_SESSION['user_id']);
        login();
        if (is_logged()) {
            header('Location: index.php');
        } else {
            header('Location: login.php');
        }
        return;
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Arseni Rynkevich - Resume Registry, Log In</title>
    <?php require_once 'include/bootstrap.php'; ?>
    <script src="js/validation.js"></script>
</head>

<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?php
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form method="POST">
            <label for="edt_username">User Name</label>
            <input type="text" name="email" id="edt_username" width="60"><br>

            <label for="edt_password">Password</label>
            <input type="password" name="pass" id="edt_password" width="60"><br>

            <input type="submit" onclick="return validateUserData();" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </form>
        For a password hint, view source and find an account and password hint in the HTML comments.
        <!-- Hint:
        The account is umsi@umich.edu
        The password is the three character name of the
        programming language used in this class (all lower case)
        followed by 123. -->
    </div>
</body>

</html>

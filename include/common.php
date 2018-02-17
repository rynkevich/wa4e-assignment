<?php
    require_once('pdo.php');

    define('NO_IMG_AVA', 'img/no-image.png');
    define('MAX_POS_ENTRIES', 9);

    function is_logged() {
        return isset($_SESSION['name']) && isset($_SESSION['user_id']);
    }

    function is_valid_email($email) {
        return strchr($email, '@') !== false;
    }

    function url_exists($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);

        curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $retcode == 200;
    }

    function get_profile_data() {
        global $pdo;

        $selection = $pdo->prepare('SELECT * FROM profiles WHERE profile_id = :id');
        $selection->execute(array(':id' => $_REQUEST['profile_id']));
        return $selection->fetch(PDO::FETCH_ASSOC);
    }

    function get_profile_positions($profile_id) {
        global $pdo;

        $selection = $pdo->prepare('SELECT * FROM positions WHERE profile_id = :id');
        $selection->execute(array(':id' => $profile_id));

        return $selection;
    }

    function is_ok_field_size() {
        return !(strlen($_POST['first_name']) > 1 && strlen($_POST['last_name']) > 1 &&
            strlen($_POST['email']) > 1 && strlen($_POST['headline']) > 1 &&
            strlen($_POST['summary']) > 1);
    }

    function is_profiles_request() {
        return isset($_POST['first_name']) && isset($_POST['last_name']) &&
            isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']);
    }

    function validate_positions() {
        for ($pos = 1; $pos <= MAX_POS_ENTRIES; $pos++) {
            if (isset($_POST['year' . $pos]) && isset($_POST['desc' . $pos])) {
                if (strlen($_POST['year' . $pos]) < 1 || strlen($_POST['desc' . $pos]) < 1) {
                    $_SESSION['error'] = 'Image URL is optional, all other fields are required';
                    return false;
                }
                if (!is_numeric($_POST['year' . $pos])) {
                    $_SESSION['error'] = 'Position year must be numeric';
                    return false;
                }
            }
        }
        return true;
    }
?>

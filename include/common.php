<?php
    require_once 'include/pdo.php';
    require_once 'include/errors.php';

    define('NO_IMG_AVA', 'img/no-image.png');
    define('MAX_POS_ENTRIES', 9);
    define('SQL_SELECT_PROFILE_BY_ID', 'SELECT * FROM profiles WHERE profile_id = :id');
    define('SQL_SELECT_POSITIONS_BY_ID', 'SELECT * FROM positions WHERE profile_id = :id');
    
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

    function sql_select($query, $substitutions) {
        global $pdo;

        $selection = $pdo->prepare($query);
        $selection->execute($substitutions);

        return $selection;
    }

    function get_profile_data() {
        return sql_select(SQL_SELECT_PROFILE_BY_ID,
            array(':id' => $_REQUEST['profile_id']))->fetch(PDO::FETCH_ASSOC);
    }

    function get_profile_positions($profile_id) {
        return sql_select(SQL_SELECT_POSITIONS_BY_ID,
            array(':id' => $profile_id));
    }

    function is_ok_field_size() {
        return (strlen($_POST['first_name']) > 1 && strlen($_POST['last_name']) > 1 &&
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
                    $_SESSION['error'] = E_PROFILE_BLANK_FIELD;
                    return false;
                }
                if (!is_numeric($_POST['year' . $pos])) {
                    $_SESSION['error'] = E_NON_NUMERIC_POSITION;
                    return false;
                }
            }
        }
        return true;
    }
?>

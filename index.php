<?php
    require_once 'include/pdo.php';
    require_once 'include/common.php';

    function show_registry() {
        global $pdo;

        $selection = $pdo->query('SELECT first_name, last_name, headline, profile_id FROM profiles');

        if ($profile = $selection->fetch(PDO::FETCH_ASSOC)) {
            echo '<table border="1">';
            echo '<tr><th>Name</th><th>Headline</th>' .
                (is_logged() ? '<th>Action</th>' : '') . '</tr>';
            do {
                echo '<tr>';

                # Name
                echo '<td><a href="/view.php?profile_id=' . $profile['profile_id'] . '">' .
                    htmlentities($profile['first_name']) . ' ' . htmlentities($profile['last_name']) . '</a></td>';
                # Headline
                echo '<td>' . htmlentities($profile['headline']) . '</td>';
                # Action
                echo (is_logged() ? '<td><a href="/edit.php?profile_id=' . $profile['profile_id'] .
                    '">Edit</a> <a href="/delete.php?profile_id=' . $profile['profile_id'] .
                    '">Delete</a></td>' : '');

                echo '</tr>';
            } while ($profile = $selection->fetch(PDO::FETCH_ASSOC));
            echo '</table>';
        }
        if (is_logged()) {
            echo '<p><a href="/add.php">Add New Entry</a></p>';
        }
    }

    session_start();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Arseni Rynkevich - Resume Registry</title>
  <?php require_once 'styles/bootstrap.php'; ?>
</head>

<body>
  <div class='container'>
    <h1>The Resume Registry</h1>
    <?php
        if (isset($_SESSION['success'])) {
            echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }

        echo is_logged() ? '<p><a href="/logout.php">Logout</a></p>' :
            '<p><a href="/login.php">Please log in</a></p>';
        show_registry();
    ?>
  </div>
</body>

</html>

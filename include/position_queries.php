<?php
    require_once 'include/pdo.php';

    function sql_update_position($id, $yr, $desc, $posid) {
        global $pdo;

        $stmt = $pdo->prepare('UPDATE positions SET profile_id = :id,
            `year` = :yr, description = :desc WHERE position_id = :posid');
        $stmt->execute(array(
            ':id' => $id,
            ':yr' => $yr,
            ':desc' => $desc,
            ':posid' => $posid)
        );
    }

    function sql_delete_position($posid) {
        global $pdo;

        $stmt = $pdo->prepare('DELETE FROM positions WHERE position_id = :posid');
        $stmt->execute(array(':posid' => $posid));
    }

    function sql_insert_position($id, $yr, $desc) {
        global $pdo;

        $stmt = $pdo->prepare('INSERT INTO positions (profile_id, `year`, description)
            VALUES (:id, :yr, :desc)');
        $stmt->execute(array(
            ':id' => $id,
            ':yr' => $yr,
            ':desc' => $desc)
        );
    }
?>

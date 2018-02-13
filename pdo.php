<?php
    $pdo = new PDO('mysql:host=localhost;port=8889;dbname=ResumeRegistry',
        'application', 'Pa$$w0rD');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

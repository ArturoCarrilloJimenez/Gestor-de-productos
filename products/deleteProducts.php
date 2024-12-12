<?php
if (isset($_GET['id'])) {

    if ($functionDb->delete('productos', $_GET['id'])) {
        header('Location: index.php');
        exit();
    } else die("Error: " . $stmt->error);
}

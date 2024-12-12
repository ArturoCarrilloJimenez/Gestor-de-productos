<?php
if (isset($_GET['id'])) {
    $sql = 'DELETE FROM productos WHERE id_producto = ?';

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Preparo la consulta
    $stmt->bind_param('i', $_GET['id']);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else die("Error: " . $stmt->error);

    $stmt->close();
}

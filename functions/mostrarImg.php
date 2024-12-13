<?php
include_once '../configs/db.php';

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del producto de la URL
$id = $_GET['id'];

// Consulta para obtener la imagen
$sql = "SELECT fotografia FROM productos WHERE id_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($image);
$stmt->fetch();

// Verifica si se obtuvo la imagen
if ($image) {
    // Configura las cabeceras para mostrar la imagen correctamente
    header("Content-Type: image/jpeg"); // Ajusta esto si las imágenes no son JPG
    echo $image;  // Muestra la imagen
} else {
    echo "Imagen no disponible.";
}
?>

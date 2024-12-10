<?php
$extensionesValidas = ['jpg', 'png', 'gif'];
$acceptExtension = '';
foreach ($extensionesValidas as $key => $value) {
    $acceptExtension .= '.' . $value . ', ';
}

// Si el método es post añado el nuevo producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    $ruta_image = 'img/';
    $max_file_size = '5120000';

    // Compruebo que aya mandado el archivo
    if (isset($_FILES['imagen'])) {
        // Obtengo toda la info del archivo
        $nameImg = $_FILES['imagen']['name'];
        $filesSize = $_FILES['imagen']['size'];
        $dirTemp = $_FILES['imagen']['tmp_name'];
        $tipoArchivo = $_FILES['imagen']['type'];
        $arrayImg = pathinfo($nameImg);
        $extension = $arrayImg['extension'];

        if (!in_array($extension, $extensionesValidas)) {
            $error = 'Extension no valida';
        }

        if ($filesSize > $max_file_size) {
            $error = 'El archivo es demasiado grande';
        }

        if (!isset($error)) {
            // Muevo el archivo a la carpeta destino
            $rutaDestino = $ruta_image . $nameImg;
            move_uploaded_file($dirTemp, $rutaDestino);

            // Inserto el producto en la vase de datos
            $sql = 'INSERT INTO productos (nombre, descripcion, precio, fotografia) VALUE (?,?,?,?)';

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                die("Error al preparar la consulta: " . $conn->error);
            }

            // Preparo la consulta
            $stmt->bind_param('ssis', $name, $descripcion, $precio, $nameImg);

            if ($stmt->execute()) {
                header('Location: index.php');
                exit();
            } else die("Error: " . $stmt->error);

            $stmt->close();
        }
    }
}
?>

<!-- Formulario -->

<h2 class="text-center">Añadir producto</h2>
<hr>
<?php
if (isset($error)) {
?>
    <div class="alert alert-secondary" role="alert">
        <?= $error ?>
    </div>
<?php
}
?>
<form action="<?= $_SERVER['PHP_SELF'] ?>?page=addProduct" method="POST" enctype="multipart/form-data">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="name" id="name" placeholder="name" required>
        <label for="name">Nombre de producto</label>
    </div>
    <div class="mb-3">
        <label for="descripcion">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="w-100 form-control" placeholder="Escribe aquí la descripción"></textarea>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text" id="precio">Precio</span>
        <input type="number" class="form-control" name="precio" placeholder="Precio" aria-label="precio" aria-describedby="basic-addon1" required>
    </div>
    <div class="mb-3">
        <input type="file" class="form-control" name="imagen" placeholder="Imagen" aria-label="imagen" aria-describedby="basic-addon1" accept="<?= $acceptExtension ?>" required>
    </div>
    <button type="submit" class="btn btn-dark w-100">Añadir</button>
</form>
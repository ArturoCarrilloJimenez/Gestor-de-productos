<?php
$extensionesValidas = ['jpg', 'png', 'gif'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
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
            echo 'Extension no valida';
            $error = 1;
        }

        if ($filesSize > $max_file_size) {
            echo 'El archivo es demasiado grande';
            $error = 1;
        }

        if (!isset($error)) {
            // Muevo el archivo a la carpeta destino
            $rutaDestino = $ruta_image . $nameImg;
            move_uploaded_file($dirTemp, $rutaDestino);
        }

        // Inserto el producto en la vase de datos
        $sql = 'INSERT INTO productos (id_producto, nombre, descripcion, precio, fotografia) VALUE (?,?,?,?,?)';
        
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param('issis', $id, $name, $descripcion, $precio, $nameImg);

        if ($stmt->execute()) echo 'Producto añadido';
        else echo "Error: " . $stmt->error;

        $stmt->close();
    }
} else {
?>
    <div class="container my-4">
        <h2 class="text-center">Añadir producto</h2>
        <form action="<?= $_SERVER['PHP_SELF'] ?>?page=addProduct" method="POST" enctype="multipart/form-data">
            <div class="input-group mb-3">
                <span class="input-group-text" id="id">Identificador</span>
                <input type="number" class="form-control" name="id" placeholder="Identificador" aria-label="id" aria-describedby="basic-addon1" autofocus required>
            </div>
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
                <input type="file" class="form-control" name="imagen" placeholder="Imagen" aria-label="imagen" aria-describedby="basic-addon1"
                    accept="<?php foreach ($extensionesValidas as $key => $value) {
                                echo '.', $value, ', ';
                            } ?>" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Añadir</button>
        </form>
    </div>
<?php
}

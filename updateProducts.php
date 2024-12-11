<?php
$extensionesValidas = ['jpg', 'png', 'gif'];
$acceptExtension = '';
foreach ($extensionesValidas as $key => $value) {
    $acceptExtension .= '.' . $value . ', ';
}

// Si el método es post añado el nuevo producto
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
            $error = 'Extension no valida';
        }

        if ($filesSize > $max_file_size) {
            $error = 'El archivo es demasiado grande';
        }

        if (!isset($error)) {
            // Muevo el archivo a la carpeta destino
            $rutaDestino = $ruta_image . $nameImg;
            move_uploaded_file($dirTemp, $rutaDestino);
        }
    }

    if (!isset($error)) {
        echo 'Todo correcto';

        // Inserto el producto en la vase de datos
        if (isset($_FILES['imagen'])) {
            $sql = 'UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, fotografia = ? WHERE id_producto = ?';
        } else {
            $sql = 'UPDATE productos SET nombre = ?, descripcion = ?, precio = ? WHERE id_producto = ?';
        }

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        // Preparo la consulta
        if (isset($_FILES['imagen'])) {
            $stmt->bind_param('ssssi', $name, $descripcion, $precio, $nameImg, $id);
        } else {
            $stmt->bind_param('sssi', $name, $descripcion, $precio, $id);
        }

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else die("Error: " . $stmt->error);

        $stmt->close();
    }
} else {
    // Si no es post muestro el formulario
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = 'SELECT * FROM productos WHERE id_producto = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            $error = 'Algo a fallado al intentar opener los datos';
        } else {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        }
    }
}
?>

<!-- Formulario -->

<h2 class="text-center">Editar producto</h2>
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
<form action="<?= $_SERVER['PHP_SELF'] ?>?page=updateProducts" method="POST" enctype="multipart/form-data">
    <?php
    if (isset($id)) {
    ?>
        <input type="hidden" name="id" value="<?= $id ?>">
    <?php
    } else {
    ?>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="id" id="id" placeholder="id" required>
            <label for="id">Id</label>
        </div>
    <?php
    }
    ?>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="name" id="name" placeholder="name" <?php if (isset($row)) { ?> value="<?= $row['nombre'] ?>" <?php } ?> required>
        <label for="name">Nombre de producto</label>
    </div>
    <div class="mb-3">
        <label for="descripcion">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="w-100 form-control" placeholder="Escribe aquí la descripción"><?php if (isset($row)) {echo $row['descripcion'];} ?></textarea>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text" id="precio">Precio</span>
        <input type="number" class="form-control" name="precio" placeholder="Precio" aria-label="precio" aria-describedby="basic-addon1" <?php if (isset($row)) { ?> value="<?= $row['precio'] ?>" <?php } ?> required>
    </div>
    <div>
        <input type="file" class="form-control" name="imagen" placeholder="Imagen" aria-label="imagen" aria-describedby="basic-addon1" disabled>
        <div class="form-check my-3">
            <input class="form-check-input" type="checkbox" value="modify" name="editImg" id="defaultCheck1">
            <label class="form-check-label text-muted" for="defaultCheck1">Cambiar imagen</label>
        </div>
    </div>
    <button type="submit" class="btn btn-dark w-100">Editar</button>
</form>

<script>
    const editImg = document.getElementById('defaultCheck1');
    const inputImg = document.querySelector('input[type="file"]');

    // Habilito o deshabilito el input de la imagen
    editImg.addEventListener('change', () => {
        if (editImg.checked) {
            inputImg.removeAttribute('disabled');
            inputImg.setAttribute('required', 'true');
        } else {
            inputImg.setAttribute('disabled', 'true');
        }
    });
</script>
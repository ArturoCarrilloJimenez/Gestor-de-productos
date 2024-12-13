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
        $image = file_get_contents($dirTemp); // Obtengo el contenido del archivo
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
            $stmt->bind_param('ssibi', $name, $descripcion, $precio, $null, $id);

            // Enlazo el parámetro binario manualmente
            $stmt->send_long_data(3, $image);
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
    // En caso de que le pase el id por parámetro cargo los datos
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = 'SELECT * FROM productos WHERE id_producto = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            $error = 'Algo a fallado al intentar opener los datos';
        } else {
            $result = $stmt->get_result();

            // Si no hay respuesta mostrara un mensaje de error y eliminar la variable id
            if ($result->num_rows == 0) {
                $error = 'No existe el producto con el id ' . $id;
                unset($id);
            } else {
                $row = $result->fetch_assoc();
            }
        }
    }
}
?>

<!-- Formulario -->

<h2 class="text-center mb-4">Editar Producto</h2>
<hr class="mb-4">

<?php if (isset($error)) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?= $_SERVER['PHP_SELF'] ?>?page=updateProducts" method="POST" enctype="multipart/form-data" class="p-4 shadow rounded bg-light">
    <?php if (isset($id)) : ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="nameImg" value="<?= htmlspecialchars($row['fotografia'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <?php else : ?>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="id" id="id" placeholder="ID" required>
            <label for="id">ID</label>
        </div>
    <?php endif; ?>

    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="name" id="name" placeholder="Nombre del producto" value="<?= htmlspecialchars($row['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        <label for="name">Nombre del Producto</label>
    </div>

    <div class="form-floating mb-3">
        <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción del producto" style="height: 150px;"><?= htmlspecialchars($row['descripcion'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <label for="descripcion">Descripción</label>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text">Precio</span>
        <input type="number" class="form-control" name="precio" placeholder="Precio" value="<?= htmlspecialchars($row['precio'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div>
        <input type="file" class="form-control" name="imagen" id="imagen" accept="<?= $acceptExtension ?>" disabled>
        <div class="form-check my-3">
            <input class="form-check-input" type="checkbox" value="modify" name="editImg" id="editImg">
            <label class="form-check-label text-muted" for="editImg">Cambiar Imagen</label>
        </div>
    </div>

    <button type="submit" class="btn btn-dark w-100">Editar</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editImg = document.getElementById('editImg');
        const inputImg = document.getElementById('imagen');

        // Habilitar o deshabilitar el input de la imagen
        editImg.addEventListener('change', () => {
            if (editImg.checked) {
                inputImg.removeAttribute('disabled');
                inputImg.setAttribute('required', 'true');
            } else {
                inputImg.setAttribute('disabled', 'true');
                inputImg.removeAttribute('required');
            }
        });
    });
</script>
<?php
// Obtengo los datos
$sql = 'SELECT * FROM productos';
$stmt = $conn->prepare($sql);

// Si no se puede ejecutar la consulta mostrara unn error
if (!$stmt->execute()) {
    $error = 'Algo a fallado al intentar opener los datos';
} else {
    // Obtener el resultado
    $result = $stmt->get_result();
}

?>

<!-- Lista -->

<h1 class="text-center">Productos</h1>
<hr>

<?php
// Compruebo que no aya errores
if (isset($error)) {
?>
    <div class="alert alert-secondary" role="alert">
        <?= $error ?>
    </div>
<?php
} else { // si no hay errores muestro la tabla
?>

    <ul class="list-group text-center">
        <li class="list-group-item list-group-item-dark">
            <div class="row">
                <div class="col-1">Id</div>
                <div class="col-2">Nombre</div>
                <div class="col-4">Descripción</div>
                <div class="col-2">Precio</div>
                <div class="col-1">Imagen</div>
                <div class="col-1">Editar</div>
                <div class="col-1">Eliminar</div>
            </div>
        </li>
        <?php // Si tiene mas de una fila, muestro los datos
        if ($result->num_rows > 0) {
            // Recorremos todas las filas
            while ($row = $result->fetch_assoc()) {  // Usamos fetch_assoc() para obtener una fila
        ?>
                <li class="list-group-item p-3 shadow-sm rounded-3">
                    <div class="row align-items-center fs-6">
                        <div class="col-1 text-center fw-bold"><?= $row['id_producto'] ?></div>
                        <div class="col-2 text-truncate"><?= $row['nombre'] ?></div>
                        <div class="col-4 text-muted text-truncate"><?= $row['descripcion'] ?></div>
                        <div class="col-2 fw-semibold"><?= $row['precio'] ?>€</div>
                        <div class="col-1 text-center">
                            <img src="img/<?= $row['fotografia'] ?>" alt="<?= $row['fotografia'] ?>" class="img-fluid rounded" style="max-height: 50px;">
                        </div>
                        <div class="col-1 text-center">
                            <a href="?page=updateProducts&id=<?= $row['id_producto'] ?>"><i class="bi bi-pencil-square text-success fs-5 cursor-pointer" title="Editar" style="cursor: pointer;"></i></a>
                        </div>
                        <div class="col-1 text-center">
                            <!-- TODO hacer eliminado -->
                            <a href=""><i class="bi bi-x-square text-danger fs-5 cursor-pointer" title="Eliminar" style="cursor: pointer;"></i></a>
                        </div>
                    </div>
                </li>

        <?php }
        } ?>
    </ul>
<?php
}

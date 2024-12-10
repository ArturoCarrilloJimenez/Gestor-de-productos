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
                <div class="col-3">Nombre</div>
                <div class="col-5">Descripción</div>
                <div class="col-2">Precio</div>
                <div class="col-1">Imagen</div>
            </div>
        </li>
        <?php // Si tiene mas de una fila, muestro los datos
        if ($result->num_rows > 0) {
            // Recorremos todas las filas
            while ($row = $result->fetch_assoc()) {  // Usamos fetch_assoc() para obtener una fila
        ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-1"><?= $row['id_producto'] ?></div>
                        <div class="col-3"><?= $row['nombre'] ?></div>
                        <div class="col-5"><?= $row['descripcion'] ?></div>
                        <div class="col-2"><?= $row['precio'] ?>€</div>
                        <div class="col-1"><img src="img/<?= $row['fotografia'] ?>" alt="<?= $row['fotografia'] ?>" class="w-100" ></div>
                    </div>
                </li>
        <?php }
        } ?>
    </ul>

<?php
}

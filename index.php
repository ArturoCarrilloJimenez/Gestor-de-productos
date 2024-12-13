<?php
ob_start(); // Inicia el buffer de salida
require_once './configs/db.php';
require_once './configs/functionsDb.php';

$functionDb = new Database($conn);

// Importa las configuraciones iniciales y los componentes comunes
require_once './imports/initial.php';
require_once './components/header.php';
?>

<main class="container my-4" style="min-height: 100vh;">
    <?php
    // Verifica si la página está definida en GET o POST
    $page = $_GET['page'] ?? $_POST['page'] ?? null;

    // Controlador de páginas
    switch ($page) {
        case 'addProduct':
            include_once './products/addProduct.php';
            break;

        case 'deleteProducts':
            include_once './products/deleteProducts.php';
            break;

        case 'updateProducts':
            include_once './products/updateProducts.php';
            break;

        default:
            include_once './products/listProduct.php';
            break;
    }
    ?>
</main>

<?php
// Importa el pie de página y los scripts finales
require_once './components/footer.php';
require_once './imports/end.php';
ob_end_flush(); // Envía el contenido del buffer

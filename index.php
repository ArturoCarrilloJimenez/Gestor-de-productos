<?php
require_once './configs/db.php';

require_once './imports/initial.php';
require_once './components/header.php';
?>
<main style="min-height: 100vh;">
<?php
if (isset($_GET['page']) or isset($_POST['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'addProduct':
            include_once './addProduct.php';
            break;
        
        case 'deleteProducts':
            include_once './deleteProducts.php';
            break;

        case 'updateProducts':
            include_once './updateProducts.php';
            break;

        default:
            include_once './listProduct.php';
            break;
    }
} else include_once './listProduct.php';
?>
</main>
<?php
require_once './components/footer.php';
require_once './imports/end.php';
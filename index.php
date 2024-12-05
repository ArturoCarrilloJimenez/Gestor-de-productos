<?php
require_once './configs/db.php';

require_once './imports/initial.php';
require_once './components/header.php';

if (isset($_GET['page'])) {
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

        case 'listProduct':
            include_once './listProduct.php';
            break;
            
        default:
            break;
    }
}

require_once './components/footer.php';
require_once './imports/end.php';
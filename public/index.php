<?php
// public/index.php
require_once __DIR__ . '/../app/config/db.php';

// Capturamos la URL (ej: /alumnos, /clases)
$route = $_GET['url'] ?? 'home';

// Un ruteador básico para este commit inicial
switch ($route) {
    case 'home':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
        
    case 'usuarios':
        require_once __DIR__ . '/../app/controllers/UsuarioController.php';
        $controller = new UsuarioController();
        $controller->index();
        break;

    default:
        http_response_code(404);
        echo "404 - Página no encontrada";
        break;
}
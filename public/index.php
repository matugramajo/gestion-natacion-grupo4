<?php
require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/core/Env.php';

// Cargamos variables de entorno si existen
// Env::load(__DIR__ . '/../.env'); 

$route = $_GET['url'] ?? 'home';

// Instanciamos los controladores según la ruta para evitar repetir código
switch ($route) {
    case 'home':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        (new HomeController())->index();
        break;

    // Todas estas rutas pertenecen al UsuarioController
    case 'login':
    case 'auth':
    case 'register':
    case 'forgot-password':
    case 'send-reset':
    case 'reset-password': // La vista de la nueva clave
    case 'update-password': // El proceso de cambio
        require_once __DIR__ . '/../app/controllers/UsuarioController.php';
        $controller = new UsuarioController();
        
        // Mapeo de rutas a métodos para que sea consistente
        if ($route === 'login')           $controller->showLogin();
        if ($route === 'auth')            $controller->auth();
        if ($route === 'register')        $controller->register();
        if ($route === 'forgot-password') $controller->forgotPassword();
        if ($route === 'send-reset')      $controller->sendReset();
        if ($route === 'reset-password')  $controller->resetPassword(); 
        if ($route === 'update-password') $controller->updatePassword();
        break;

    case 'logout':
        session_start();
        session_destroy();
        header('Location: ?url=login');
        break;

    default:
        http_response_code(404);
        echo '404 - Página no encontrada';
        break;
}
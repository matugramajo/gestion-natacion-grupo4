<?php


/**
 * EL ENRUTADOR (ROUTER)
 * Este archivo actúa como el "director de tráfico". Recibe la URL y decide
 * qué controlador y qué método deben ejecutarse.
 */

require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/core/Env.php';
require_once __DIR__ . '/../app/core/BaseController.php';

// Capturamos la ruta actual, por defecto es 'home'
$route = $_GET['url'] ?? 'home';

// El switch nos permite evaluar la ruta y ejecutar la acción correspondiente
switch ($route) {
    
    case 'home':
        // El listado principal de nadadores
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        (new HomeController())->index();
        break;

    // AGRUPACIÓN: Todas las rutas de gestión de acceso y usuarios
    case 'login':
    case 'authenticate': 
    case 'register':
    case 'forgot-password':
    case 'send-reset':
    case 'reset-password':
    case 'update-password':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();
        
        // Mapeo dinámico de rutas a los nuevos nombres de métodos
        if ($route === 'login')           $controller->showLogin();
        if ($route === 'authenticate')    $controller->authenticate();
        if ($route === 'register')        $controller->register();
        if ($route === 'forgot-password') $controller->forgotPassword();
        if ($route === 'send-reset')      $controller->sendReset();
        // Nota: Agregamos lógica para reset-password cuando estemos listos
        if ($route === 'update-password') $controller->updatePassword();
        break;

    case 'logout':
        /**
         * Lógica de cierre de sesión:
         * Limpiamos la memoria y redirigimos al login.
         */
        session_start();
        session_destroy();
        header('Location: ?url=login');
        exit; // Siempre usamos exit después de un redirect por seguridad

    default:
        // Manejo de errores 404 para rutas inexistentes
        http_response_code(404);
        echo '404 - Página no encontrada. Verifica la URL en el Router.';
        break;
}
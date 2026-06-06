<?php

/**
 * EL ENRUTADOR ( ROUTER ) - Front Controller Pattern
 */

require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/core/Env.php';
require_once __DIR__ . '/../app/core/BaseController.php';

$route = $_GET['url'] ?? 'home';

switch ( $route ) {

    case 'home':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        $controller = new HomeController();
        if ( !isset( $_SESSION['user_id'] ) ) {
            $controller->landing();
        } else {
            $controller->index();
        }
        break;

    case 'login':
    case 'authenticate':
    case 'register':
    case 'forgot-password':
    case 'send-reset':
    case 'reset-password':
    case 'update-password':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        if ( $route === 'login' )           $controller->showLogin();
        if ( $route === 'authenticate' )    $controller->authenticate();
        if ( $route === 'register' )        $controller->register();
        if ( $route === 'forgot-password' ) $controller->forgotPassword();
        if ( $route === 'send-reset' )      $controller->sendReset();
        if ( $route === 'reset-password' )  $controller->showResetForm();
        if ( $route === 'update-password' ) $controller->updatePassword();
        break;

    case 'swimmers':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        ( new AuthController() )->index();
        break;

    // Módulo coaches (vistas de Martina — KAN-36)
    case 'coaches':
    case 'coaches/create':
    case 'coaches/store':
        require_once __DIR__ . '/../app/controllers/CoachController.php';
        $controller = new CoachController();
        if ( $route === 'coaches' )        $controller->index();
        if ( $route === 'coaches/create' ) $controller->create();
        if ( $route === 'coaches/store' )  $controller->store();
        break;

    // Admin (panel)
    case 'admin-coaches':
    case 'admin-store-coach':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $admin = new AdminController();
        if ( $route === 'admin-coaches' )     $admin->coaches();
        if ( $route === 'admin-store-coach' ) $admin->storeCoach();
        break;

    case 'admin-lessons':
    case 'admin-store-lesson':
    case 'admin-update-lesson':
    case 'admin-delete-lesson':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $admin = new AdminController();
        if ( $route === 'admin-lessons' )       $admin->lessons();
        if ( $route === 'admin-store-lesson' )  $admin->storeLesson();
        if ( $route === 'admin-update-lesson' ) $admin->updateLesson();
        if ( $route === 'admin-delete-lesson' ) $admin->deleteLesson();
        break;

    // Coach (panel)
    case 'coach-profile':
    case 'coach-update-profile':
    case 'coach-update-password':
    case 'coach-students':
        require_once __DIR__ . '/../app/controllers/CoachController.php';
        $coach = new CoachController();
        if ( $route === 'coach-profile' )          $coach->profile();
        if ( $route === 'coach-update-profile' )   $coach->updateProfile();
        if ( $route === 'coach-update-password' )  $coach->updatePassword();
        if ( $route === 'coach-students' )         $coach->students();
        break;

    // Swimmer (panel)
    case 'swimmer-profile':
    case 'swimmer-update-profile':
    case 'swimmer-lessons':
    case 'swimmer-enroll':
        require_once __DIR__ . '/../app/controllers/SwimmerController.php';
        $swimmer = new SwimmerController();
        if ( $route === 'swimmer-profile' )         $swimmer->profile();
        if ( $route === 'swimmer-update-profile' )  $swimmer->updateProfile();
        if ( $route === 'swimmer-lessons' )         $swimmer->lessons();
        if ( $route === 'swimmer-enroll' )          $swimmer->enroll();
        break;

    case 'logout':
        $_SESSION = [];
        session_destroy();
        header( 'Location: ?url=home&logout=1' );
        exit;

    default:
        http_response_code( 404 );
        echo 'Error 404: La página "' . htmlspecialchars( $route ) . '" no existe en este sistema.';
        break;
}

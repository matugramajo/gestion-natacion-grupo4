<?php

/**
 * EL ENRUTADOR ( ROUTER ) - Front Controller Pattern
 * Los require_once de controladores y clases del core se eliminaron
 * gracias al autoloader registrado en app/core/Autoloader.php.
 */

$route = $_GET['url'] ?? 'home';

switch ( $route ) {

    case 'home':
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
        ( new AuthController() )->index();
        break;

    // Admin (panel)
    case 'admin-coaches':
    case 'admin-store-coach':
    case 'admin-edit-coach':
    case 'admin-update-coach':
    case 'admin-delete-coach':
    case 'admin-delete-swimmer':
        $admin = new AdminController();
        if ( $route === 'admin-coaches' )        $admin->coaches();
        if ( $route === 'admin-store-coach' )    $admin->storeCoach();
        if ( $route === 'admin-edit-coach' )     $admin->editCoach();
        if ( $route === 'admin-update-coach' )   $admin->updateCoach();
        if ( $route === 'admin-delete-coach' )   $admin->deleteCoach();
        if ( $route === 'admin-delete-swimmer' ) $admin->deleteSwimmer();
        break;

    case 'admin-lessons':
    case 'admin-store-lesson':
    case 'admin-update-lesson':
    case 'admin-delete-lesson':
        $admin = new AdminController();
        if ( $route === 'admin-lessons' )       $admin->lessons();
        if ( $route === 'admin-store-lesson' )  $admin->storeLesson();
        if ( $route === 'admin-update-lesson' ) $admin->updateLesson();
        if ( $route === 'admin-delete-lesson' ) $admin->deleteLesson();
        break;

    // KAN-36: Módulo de alta de profesores (restaurado de eb2f7c9)
    case 'coaches':
    case 'coaches/create':
    case 'coaches/store':
        $controller = new CoachController();
        if ( $route === 'coaches' )         $controller->index();
        if ( $route === 'coaches/create' )  $controller->create();
        if ( $route === 'coaches/store' )   $controller->store();
        break;

    // Coach (panel)
    case 'coach-profile':
    case 'coach-update-profile':
    case 'coach-update-password':
    case 'coach-students':
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

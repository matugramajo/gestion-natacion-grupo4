<?php
// app/core/BaseController.php

require_once __DIR__ . '/Role.php';

class BaseController {

    public function __construct() {
        // Iniciamos sesión en el constructor base para que esté disponible en todos lados
        if ( session_status() === PHP_SESSION_NONE ) {
            session_start();
        }
    }

    protected function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $this->json('error', 'Sesión expirada', '?url=login');
            } else {
                header('Location: ?url=login');
                exit;
            }
        }
    }

    protected function checkRole( array $allowedRoles ) {
        $this->checkAuth();
        $roleId = (int) ( $_SESSION['role_id'] ?? 0 );
        if ( !in_array( $roleId, $allowedRoles, true ) ) {
            if ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' ) {
                $this->json( 'error', 'No tenés permiso para esta acción.' );
            }
            http_response_code( 403 );
            $this->render( 'errors/forbidden.view', [ 'titulo' => 'Acceso denegado' ] );
            exit;
        }
    }

    protected function currentRoleId(): int {
        return (int) ( $_SESSION['role_id'] ?? 0 );
    }
    
    /**
    * @param string $view  Nombre del archivo ( ej: 'usuarios/register' )
    * @param array  $data  Diccionario de datos para la vista
    */
    protected function render( $view, $data = [] ) {
        // Extraemos el array: [ 'alerta' => '...' ] se vuelve la variable $alerta
        extract( $data );

        $path = __DIR__ . '/../views/' . $view . '.php';

        if ( file_exists( $path ) ) {
            require_once $path;
        } else {
            die( "Error: La vista '{$view}' no existe. Verificá la carpeta views." );
        }
    }

    protected function json( $status, $message, $redirect = null ) {
        header( 'Content-Type: application/json' );
        echo json_encode( [
        'status'   => $status,
        'message'  => $message,
        'redirect' => $redirect ?? Env::get('APP_URL')
        ], JSON_UNESCAPED_SLASHES );
        exit;
        // Importante para cortar la ejecución aquí
    }
}
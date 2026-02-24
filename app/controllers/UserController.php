<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Swimmer.php';

class UserController extends BaseController {
    private $userModel;
    private $swimmerModel;
    private $pdo;

    public function __construct() {
        /** * Usamos 'global $pdo' porque la conexión se crea en otro archivo ( ej. index o database ).
        * Sin esto, el controlador no tendría acceso al objeto de conexión PDO para pasárselo a los modelos.
        * Es una forma de 'inyectar' la base de datos sin volver a conectarse en cada clase.
        */
        global $pdo;
        $this->pdo = $pdo;

        // Inicializamos los modelos pasándoles la conexión única
        $this->userModel = new User( $pdo );
        $this->swimmerModel = new Swimmer( $pdo );
    }

    // --- SECCIÓN: VISTAS Y LISTADOS ---

    /**
    * Lista todos los nadadores registrados.
    * Ideal para mostrar cómo se consumen datos con JOINs desde el modelo.
    */

    public function index() {
        $this->checkAuth();
        // Seguridad: si no hay sesión, al login.

        $swimmers = $this->swimmerModel->getAll();
        $this->render( 'users/index', [ 'swimmers' => $swimmers ] );
    }

    public function showLogin() {
        $this->render( 'users/login' );
    }

    public function showRegister() {
        $this->render( 'users/register', [ 'title' => 'Inscripción de Alumnos' ] );
    }

    public function forgotPassword() {
        $this->render( 'users/forgot-password', [ 'title' => 'Recuperar Contraseña' ] );
    }

    // --- SECCIÓN: PROCESAMIENTO DE DATOS ( POST ) ---

    /**
    * Punto de entrada para el registro de nuevos alumnos.
    * Aquí separamos la validación de la lógica de negocio.
    */

    public function register() {
        if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
            return $this->showRegister();
        }

        // 1. Recolección y Sanitización ( Evitamos espacios vacíos y basura )
        $fields = [
            'first_name' => trim( $_POST[ 'nombre' ] ?? '' ),
            'last_name'  => trim( $_POST[ 'apellido' ] ?? '' ),
            'email'      => trim( $_POST[ 'email' ] ?? '' ),
            'password'   => $_POST[ 'password' ] ?? '',
            'phone'      => trim( $_POST[ 'telefono' ] ?? '' )
        ];

        // 2. Validaciones Críticas ( Uso de 'Early Returns' para evitar anidación de IFs )
        if ( $this->hasEmptyFields( $fields ) ) {
            return $this->json( 'warning', 'Faltan datos obligatorios.' );
        }

        if ( !filter_var( $fields[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {
            return $this->json( 'error', 'El email ingresado no es válido.' );
        }

        if ( strlen( $fields[ 'password' ] ) < 6 ) {
            return $this->json( 'warning', 'La contraseña es muy corta (mín. 6 caracteres).' );
        }

        // 3. Pasamos a la ejecución de la lógica
        return $this->executeRegistration( $fields );
    }

    /**
    * Lógica de inscripción con Transacción SQL.
    * Enseñamos que si algo falla en el medio, no debe quedar basura en la DB.
    */

    private function executeRegistration( $f ) {
        try {
            // Verificamos si el mail ya existe usando el nuevo método unificado
            if ( $this->userModel->findByEmail( $f[ 'email' ] ) ) {
                return $this->json( 'user_exists', 'Ya tienes una cuenta registrada.', '?url=login' );
            }

            $this->pdo->beginTransaction();

            // Creamos las credenciales ( Tabla: users )
            $userId = $this->userModel->create( [
                'email'    => $f[ 'email' ],
                'password' => $f[ 'password' ],
                'role_id'  => 3 // Alumno
            ] );

            if ( !$userId ) throw new Exception( 'Error al crear credenciales.' );

            // Creamos el perfil ( Tabla: swimmers )
            // Inyectamos el ID recién creado en el array de datos
            $f[ 'user_id' ] = $userId;
            $this->swimmerModel->create( $f );

            $this->pdo->commit();

            return $this->json( 'success', '¡Registro completado!', '?url=login' );

        } catch ( Exception $e ) {
            if ( $this->pdo->inTransaction() ) $this->pdo->rollBack();
            return $this->json( 'error', 'No se pudo completar: ' . $e->getMessage() );
        }
    }

    /**
    * Procesa la autenticación de usuarios.
    */

    public function authenticate() {
        if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
            return $this->json( 'error', 'Acceso no permitido.' );
        }

        $email = trim( $_POST[ 'email' ] ?? '' );
        $pass  = $_POST[ 'password' ] ?? '';

        $user = $this->userModel->login( $email, $pass );

        if ( $user ) {
            session_start();
            $_SESSION[ 'user_id' ] = $user[ 'id' ];
            $_SESSION[ 'role_id' ] = $user[ 'role_id' ];
            $_SESSION[ 'email' ]   = $user[ 'email' ];

            return $this->json( 'success', '¡Bienvenido al sistema!', '?url=home' );
        }

        return $this->json( 'error', 'Credenciales incorrectas.' );
    }

    // --- SECCIÓN: RECUPERACIÓN DE CONTRASEÑA ---

    public function sendReset() {
        $email = $_POST[ 'email' ] ?? '';

        if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            return $this->json( 'error', 'Email inválido.' );
        }

        $user = $this->userModel->findByEmail( $email );

        if ( $user ) {
            $token = bin2hex( random_bytes( 32 ) );
            $expires = date( 'Y-m-d H:i:s', strtotime( '+1 hour' ) );

            $this->userModel->savePasswordToken( $email, $token, $expires );

            // Llamada al servicio de mail ( suponiendo que existe )
            // MailService::sendRecovery( $email, $token );
        }

        return $this->json( 'success', 'Si el correo existe, recibirás un enlace.', '?url=login' );
    }

    /**
    * Helper para validación de campos vacíos.
    */

    private function hasEmptyFields( $f ) {
        return empty( $f[ 'first_name' ] ) || empty( $f[ 'last_name' ] ) || empty( $f[ 'email' ] ) || empty( $f[ 'password' ] );
    }
}
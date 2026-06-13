<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Swimmer.php';

class AuthController extends BaseController {
    private $userModel;
    private $swimmerModel;
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;

        $this->userModel = new Auth( $pdo );
        $this->swimmerModel = new Swimmer( $pdo );
    }

    public function index() {
        $this->checkRole( [ Role::ADMIN, Role::COACH ] );

        $swimmers = $this->swimmerModel->getAll();
        $this->render( 'auth/index.view', [ 'swimmers' => $swimmers ] );
    }

    public function showLogin() {
        $this->render( 'auth/login.view' );
    }

    public function showRegister() {
        $this->render( 'auth/register.view', [ 'title' => 'Inscripción de Alumnos' ] );
    }

    public function forgotPassword() {
        $this->render( 'auth/forgot-password.view', [ 'title' => 'Recuperar Contraseña' ] );
    }

    public function register() {
        if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
            return $this->showRegister();
        }

        $fields = [
            'first_name'    => trim( $_POST[ 'first_name' ] ?? '' ),
            'last_name'     => trim( $_POST[ 'last_name' ] ?? '' ),
            'email'          => trim( $_POST[ 'email' ] ?? '' ),
            'password'       => $_POST[ 'password' ] ?? '',
            'phone'          => trim( $_POST[ 'phone' ] ?? '' ),
            'birth_date'     => trim( $_POST[ 'birth_date' ] ?? '' ),
            'profile_image'  => 'default-profile.png'
        ];

        if ( $this->hasEmptyFields( $fields ) ) {
            return $this->json( 'warning', 'Faltan datos obligatorios.' );
        }

        if ( !filter_var( $fields[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {
            return $this->json( 'error', 'El email ingresado no es válido.' );
        }

        if ( !$this->isValidBirthDate( $fields[ 'birth_date' ] ) ) {
            return $this->json( 'warning', 'La fecha de nacimiento no es válida.' );
        }

        $confirmPassword = $_POST[ 'confirm_password' ] ?? '';
        if ( $fields[ 'password' ] !== $confirmPassword ) {
            return $this->json( 'warning', 'Las contraseñas no coinciden.' );
        }

        if ( strlen( $fields[ 'password' ] ) < 6 ) {
            return $this->json( 'warning', 'La contraseña es muy corta (mín. 6 caracteres).' );
        }

        $tempFile = null;
        if ( isset( $_FILES[ 'profile_image' ] ) && $_FILES[ 'profile_image' ][ 'error' ] === UPLOAD_ERR_OK ) {
            $uploadDir = __DIR__ . '/../../public/img/uploads/profiles/swimmers/';

            if ( !is_dir( $uploadDir ) ) {
                mkdir( $uploadDir, 0755, true );
            }

            $extension = strtolower( pathinfo( $_FILES[ 'profile_image' ][ 'name' ], PATHINFO_EXTENSION ) );
            $allowed = [ 'jpg', 'jpeg', 'png', 'gif' ];

            $finfo = finfo_open( FILEINFO_MIME_TYPE );
            $mimeType = finfo_file( $finfo, $_FILES['profile_image']['tmp_name'] );
            finfo_close( $finfo );

            $allowedMimes = [ 'image/jpeg', 'image/png', 'image/gif' ];

            if ( in_array( $extension, $allowed ) && in_array( $mimeType, $allowedMimes ) ) {

                $initial = strtolower( substr( $fields[ 'first_name' ], 0, 1 ) );

                $lastName = strtolower( str_replace( ' ', '', $fields[ 'last_name' ] ) );

                $randomNumber = rand( 1000, 9999 );

                $newFileName = 'swimmer_' . $initial . $lastName . '_' . $randomNumber . '.' . $extension;
                $absolutePath = $uploadDir . $newFileName;

                if ( move_uploaded_file( $_FILES[ 'profile_image' ][ 'tmp_name' ], $absolutePath ) ) {
                    $fields[ 'profile_image' ] = $newFileName;
                    $tempFile = $absolutePath;
                }
            }
        }

        return $this->executeRegistration( $fields, $tempFile );
    }

    private function executeRegistration( $f, $tempFile = null ) {

        try {
            if ( $this->userModel->findByEmail( $f[ 'email' ] ) ) {
                if ( $tempFile && file_exists( $tempFile ) ) {
                    unlink( $tempFile );

                }
                $baseUrl = rtrim( Env::get( 'APP_URL' ), '/' );

                $loginUrl = $baseUrl . '/?url=login';

                return $this->json( 'user_exists', 'Ya tienes una cuenta registrada.', Env::get( 'APP_URL' ) . '/?url=login' );
            }

            $this->pdo->beginTransaction();

            $authId = $this->userModel->create( [
                'email'    => $f[ 'email' ],
                'password' => $f[ 'password' ],
                'role_id'  => 3 
            ] );

            if ( !$authId ) throw new Exception( 'Error al crear credenciales.' );

            $f[ 'auth_id' ] = $authId;
            $this->swimmerModel->create( $f );

            $this->pdo->commit();

            $baseUrl = rtrim( Env::get( 'APP_URL' ), '/' );

            if ( empty( $baseUrl ) ) {
                $baseUrl = 'http://localhost/gestion-natacion-grupo4';
            }

            $landingUrl = $baseUrl . '/?url=home';

            return $this->json( 'success', '¡Registro completado!', $landingUrl );

        } catch ( Exception $e ) {
            if ( $this->pdo->inTransaction() ) $this->pdo->rollBack();
            if ( $tempFile && file_exists( $tempFile ) ) {
                unlink( $tempFile );
            }
            ;
            return $this->json( 'error', 'No se pudo completar: ' . $e->getMessage() );
        }
    }

    public function authenticate() {
        if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
            return $this->json( 'error', 'Acceso no permitido.' );
        }

        $email = trim( $_POST[ 'email' ] ?? '' );
        $pass  = $_POST[ 'password' ] ?? '';

        $user = $this->userModel->login( $email, $pass );

        if ( $user ) {
            session_regenerate_id( true );
            $_SESSION['user_id']       = $user['id'];
            $_SESSION['role_id']       = $user['role_id'];
            $_SESSION['role_name']     = $user['role_name'] ?? Role::name( (int) $user['role_id'] );
            $_SESSION['email']         = $user['email'];
            $_SESSION['first_name']    = $user['first_name'];
            $_SESSION['last_name']     = $user['last_name'] ?? '';
            $_SESSION['profile_image'] = $user['profile_image'];

            return $this->json( 'success', '¡Bienvenido ' . $user['first_name'] . '!', Env::get( 'APP_URL' ) . '/?url=home' );
        }

        return $this->json( 'error', 'Credenciales incorrectas.' );
    }

    public function sendReset() {
        $email = $_POST[ 'email' ] ?? '';

        if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            return $this->json( 'error', 'Email inválido.' );
        }

        $user = $this->userModel->findByEmail( $email );

        if ( $user ) {
            $token = bin2hex( random_bytes( 32 ) );
            $expires = date( 'Y-m-d H:i:s', strtotime( '+1 hour' ) );

            $tokenSaved = $this->userModel->savePasswordToken( $email, $token, $expires );

            if ( !$tokenSaved ) {
                return $this->json( 'error', 'No se pudo generar el token de recuperación.' );
            }

            require_once __DIR__ . '/../services/MailService.php';
            $mailService = new MailService();

            $enviado = $mailService->sendEmailResetPassword( $email, $token );

            if ( !$enviado ) {
                return $this->json( 'error', 'El servidor de correo falló.' );
            }
        }

        return $this->json( 'success', 'Si el correo existe, recibirás un enlace de recuperación.', Env::get( 'APP_URL' ) . '/?url=login' );
    }

    public function showResetForm() {
    $token = $_GET[ 'token' ] ?? '';

    if ( empty( $token ) ) {
        die( 'Error: El token de recuperación ha expirado o es inválido.' );
    }

    $resetRequest = $this->userModel->validateToken( $token );

    if ( !$resetRequest ) {
        die( 'Error: El token de recuperación ha expirado o es inválido.' );
    }

    $this->render( 'auth/reset-password.view', [
        'title' => 'Restablecer Contraseña',
        'token' => $token
    ] );
}

    public function updatePassword() {
        $token    = $_POST[ 'token' ] ?? '';
        $password = $_POST[ 'password' ] ?? '';
        $confirm  = $_POST[ 'confirm_password' ] ?? '';

        if ( empty( $token ) || strlen( $password ) < 6 ) {
            return $this->json( 'warning', 'La contraseña debe tener al menos 6 caracteres.' );
        }
        if ( $password !== $confirm ) {
            return $this->json( 'warning', 'Las contraseñas no coinciden.' );
        }

        $resetRequest = $this->userModel->validateToken( $token );

        if ( $resetRequest ) {
            $email = $resetRequest[ 'email' ];
            $hashedPassword = password_hash( $password, PASSWORD_BCRYPT );

            try {
                $this->pdo->beginTransaction();

                $this->userModel->updatePasswordByEmail( $email, $hashedPassword );
                $this->userModel->deleteToken( $token );

                $this->pdo->commit();
                return $this->json( 'success', '¡Contraseña actualizada con éxito!', Env::get( 'APP_URL' ) . '/?url=login' );

            } catch ( Exception $e ) {
                if ( $this->pdo->inTransaction() ) $this->pdo->rollBack();
                return $this->json( 'error', 'No se pudo actualizar la contraseña.' );
            }
        }

        return $this->json( 'error', 'El enlace es inválido o ha expirado.' );
    }

    private function hasEmptyFields( $f ) {
        return empty( $f[ 'first_name' ] )
            || empty( $f[ 'last_name' ] )
            || empty( $f[ 'email' ] )
            || empty( $f[ 'password' ] )
            || empty( $f[ 'birth_date' ] );
    }

    private function isValidBirthDate( string $birthDate ): bool {
        $date = \DateTime::createFromFormat( 'Y-m-d', $birthDate );
        if ( !$date || $date->format( 'Y-m-d' ) !== $birthDate ) {
            return false;
        }

        $today = new \DateTime( 'today' );
        if ( $date > $today ) {
            return false;
        }

        return true;
    }
}
<?php

require_once __DIR__ . '/../core/BaseController.php';
if ( !class_exists('Coach') )   require_once __DIR__ . '/../models/Coach.php';
if ( !class_exists('Auth') )    require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Booking.php';

class CoachController extends BaseController {
    private $coachModel;
    private $authModel;
    private $profileModel;
    private $lessonModel;
    private $bookingModel;
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo          = $pdo;
        $this->coachModel   = new Coach( $pdo );
        $this->authModel    = new Auth( $pdo );
        $this->profileModel = new Profile( $pdo );
        $this->lessonModel  = new Lesson( $pdo );
        $this->bookingModel = new Booking( $pdo );
    }

    public function profile() {
        $this->checkRole( [ Role::COACH ] );
        $authId = (int) $_SESSION['user_id'];
        $this->render( 'coach/profile.view', [
            'titulo'  => 'Mi Perfil',
            'profile' => $this->profileModel->findByAuthId( $authId ),
            'coach'   => $this->coachModel->findByAuthId( $authId ),
        ] );
    }

    public function updateProfile() {
        $this->checkRole( [ Role::COACH ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $authId = (int) $_SESSION['user_id'];
        $data = [
            'first_name'    => trim( $_POST['first_name'] ?? '' ),
            'last_name'     => trim( $_POST['last_name'] ?? '' ),
            'phone'         => trim( $_POST['phone'] ?? '' ),
            'address'       => trim( $_POST['address'] ?? '' ),
            'birth_date'    => trim( $_POST['birth_date'] ?? '' ) ?: null,
            'profile_image' => $_SESSION['profile_image'] ?? 'default-profile.png',
        ];

        if ( empty( $data['first_name'] ) || empty( $data['last_name'] ) ) {
            return $this->json( 'warning', 'Nombre y apellido son obligatorios.' );
        }

        if ( $this->profileModel->update( $authId, $data ) ) {
            $_SESSION['first_name'] = $data['first_name'];
            return $this->json( 'success', 'Perfil actualizado.', '?url=coach-profile' );
        }
        return $this->json( 'error', 'No se pudo actualizar el perfil.' );
    }

    public function updatePassword() {
        $this->checkRole( [ Role::COACH ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ( strlen( $new ) < 6 ) {
            return $this->json( 'warning', 'La nueva contraseña debe tener al menos 6 caracteres.' );
        }
        if ( $new !== $confirm ) {
            return $this->json( 'warning', 'Las contraseñas no coinciden.' );
        }

        $user = $this->authModel->findByEmail( $_SESSION['email'] );
        if ( !$user || !password_verify( $current, $user['password'] ) ) {
            return $this->json( 'error', 'La contraseña actual es incorrecta.' );
        }

        $hash = password_hash( $new, PASSWORD_BCRYPT );
        if ( $this->authModel->updatePasswordByEmail( $_SESSION['email'], $hash ) ) {
            return $this->json( 'success', 'Contraseña actualizada.', '?url=coach-profile' );
        }
        return $this->json( 'error', 'No se pudo actualizar la contraseña.' );
    }

    public function students() {
        $this->checkRole( [ Role::COACH ] );
        $coach = $this->coachModel->findByAuthId( (int) $_SESSION['user_id'] );
        $lessons = $coach ? $this->lessonModel->getByCoachId( (int) $coach['id'] ) : [];

        $lessonId = (int) ( $_GET['lesson_id'] ?? 0 );
        $students = $lessonId ? $this->bookingModel->getStudentsByLesson( $lessonId ) : [];

        $this->render( 'coach/students.view', [
            'titulo'   => 'Mis Alumnos',
            'lessons'  => $lessons,
            'lessonId' => $lessonId,
            'students' => $students,
        ] );
    }

    // -------------------------------------------------------------------------
    // KAN-36: Módulo de profesores (alta desde panel, restaurado de eb2f7c9)
    // -------------------------------------------------------------------------

    /**
     * Lista todos los coaches.
     */
    public function index() {
        $this->checkAuth();
        $coaches = $this->coachModel->getAll();
        $this->render( 'coaches/index.view', [ 'coaches' => $coaches ] );
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create() {
        $this->checkAuth();
        $specialties = $this->coachModel->getSpecialties();
        $this->render( 'coaches/create.view', [ 'specialties' => $specialties ] );
    }

    /**
     * Procesa el formulario y crea el coach.
     */
    public function store() {
        $this->checkAuth();

        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $fields = [
            'first_name'   => trim( $_POST['first_name'] ?? '' ),
            'last_name'    => trim( $_POST['last_name']  ?? '' ),
            'email'        => trim( $_POST['email']       ?? '' ),
            'phone'        => trim( $_POST['phone']       ?? '' ),
            'specialty_id' => (int) ( $_POST['specialty_id'] ?? 0 ),
        ];

        if ( empty( $fields['first_name'] ) || empty( $fields['last_name'] ) || empty( $fields['email'] ) ) {
            return $this->json( 'warning', 'Faltan datos obligatorios.' );
        }

        if ( !filter_var( $fields['email'], FILTER_VALIDATE_EMAIL ) ) {
            return $this->json( 'error', 'El email ingresado no es válido.' );
        }

        if ( $this->authModel->findByEmail( $fields['email'] ) ) {
            return $this->json( 'user_exists', 'Ya existe una cuenta con ese email.' );
        }

        // Generamos contraseña provisoria aleatoria
        $tempPassword = bin2hex( random_bytes( 4 ) );

        try {
            $this->pdo->beginTransaction();

            $authId = $this->authModel->create([
                'email'    => $fields['email'],
                'password' => $tempPassword,
                'role_id'  => 2 // Coach
            ]);

            if ( !$authId ) throw new Exception( 'Error al crear credenciales.' );

            $fields['auth_id'] = $authId;
            $coachOk = $this->coachModel->create( $fields );

            if ( !$coachOk ) throw new Exception( 'Error al guardar el perfil del coach.' );

            $this->pdo->commit();

            // Enviamos email con la contraseña provisoria
            require_once __DIR__ . '/../services/MailService.php';
            $mailService = new MailService();
            $mailService->sendWelcomeCoach( $fields['email'], $fields['first_name'], $tempPassword );

            return $this->json( 'success', 'Profesor creado con éxito!', Env::get('APP_URL') . '/?url=coaches' );

        } catch ( Exception $e ) {
            if ( $this->pdo->inTransaction() ) $this->pdo->rollBack();
            return $this->json( 'error', 'No se pudo completar: ' . $e->getMessage() );
        }
    }
}

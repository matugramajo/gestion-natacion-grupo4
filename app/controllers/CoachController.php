<?php

require_once __DIR__ . '/../core/BaseController.php';
if ( !class_exists('Coach') ) {
    require_once __DIR__ . '/../models/Coach.php';
}
if ( !class_exists('Auth') ) {
    require_once __DIR__ . '/../models/Auth.php';
}

class CoachController extends BaseController {
    private $coachModel;
    private $authModel;
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo        = $pdo;
        $this->coachModel = new Coach( $pdo );
        $this->authModel  = new Auth( $pdo );
    }

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
<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/Coach.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Swimmer.php';

class AdminController extends BaseController {
    private $pdo;
    private $userModel;
    private $profileModel;
    private $coachModel;
    private $lessonModel;
    private $swimmerModel;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        $this->userModel    = new Auth( $pdo );
        $this->profileModel = new Profile( $pdo );
        $this->coachModel   = new Coach( $pdo );
        $this->lessonModel  = new Lesson( $pdo );
        $this->swimmerModel = new Swimmer( $pdo );
    }

    public function coaches() {
        $this->checkRole( [ Role::ADMIN ] );
        $this->render( 'admin/coaches.view', [
            'titulo'      => 'Gestión de Coaches',
            'coaches'     => $this->coachModel->getAll(),
            'specialties' => $this->coachModel->getSpecialties(),
        ] );
    }

    public function storeCoach() {
        $this->checkRole( [ Role::ADMIN ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $fields = [
            'first_name'   => trim( $_POST['first_name'] ?? '' ),
            'last_name'    => trim( $_POST['last_name'] ?? '' ),
            'email'        => trim( $_POST['email'] ?? '' ),
            'phone'        => trim( $_POST['phone'] ?? '' ),
            'specialty_id' => (int) ( $_POST['specialty_id'] ?? 0 ),
        ];

        if ( empty( $fields['first_name'] ) || empty( $fields['last_name'] ) || empty( $fields['email'] ) ) {
            return $this->json( 'warning', 'Completá nombre, apellido y email.' );
        }
        if ( !filter_var( $fields['email'], FILTER_VALIDATE_EMAIL ) ) {
            return $this->json( 'error', 'Email inválido.' );
        }
        if ( $this->userModel->findByEmail( $fields['email'] ) ) {
            return $this->json( 'warning', 'Ese email ya está registrado.' );
        }

        $tempPassword = bin2hex( random_bytes( 4 ) );

        try {
            $this->pdo->beginTransaction();

            $authId = $this->userModel->create( [
                'email'    => $fields['email'],
                'password' => $tempPassword,
                'role_id'  => Role::COACH,
            ] );
            if ( !$authId ) throw new Exception( 'No se pudo crear el usuario.' );

            $profileOk = $this->profileModel->create( [
                'auth_id'    => $authId,
                'first_name' => $fields['first_name'],
                'last_name'  => $fields['last_name'],
                'phone'      => $fields['phone'],
            ] );
            if ( !$profileOk ) throw new Exception( 'No se pudo crear el perfil.' );

            if ( !$this->coachModel->createForAuth( $authId, $fields['specialty_id'] ) ) {
                throw new Exception( 'No se pudo crear el coach.' );
            }

            $this->pdo->commit();

            require_once __DIR__ . '/../services/MailService.php';
            $mail = new MailService();
            $mailSent = $mail->sendCoachWelcome( $fields['email'], $fields['first_name'], $tempPassword );

            $message = $mailSent
                ? 'Coach creado. Se envió la contraseña provisoria por email.'
                : "Coach creado. No se pudo enviar el email — contraseña provisoria: {$tempPassword}";

            return $this->json( 'success', $message, '?url=admin-coaches' );

        } catch ( Exception $e ) {
            if ( $this->pdo->inTransaction() ) $this->pdo->rollBack();
            return $this->json( 'error', $e->getMessage() );
        }
    }

    public function lessons() {
        $this->checkRole( [ Role::ADMIN ] );
        $this->render( 'admin/lessons.view', [
            'titulo'  => 'Gestión de Clases',
            'lessons' => $this->lessonModel->getAll(),
            'coaches' => $this->coachModel->getAll(),
            'levels'  => $this->lessonModel->getLevels(),
        ] );
    }

    public function storeLesson() {
        $this->checkRole( [ Role::ADMIN ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $data = $this->parseLessonInput();
        if ( !$data ) {
            return $this->json( 'warning', 'Completá todos los campos de la clase.' );
        }

        if ( $this->lessonModel->create( $data ) ) {
            return $this->json( 'success', 'Clase creada correctamente.', '?url=admin-lessons' );
        }
        return $this->json( 'error', 'No se pudo crear la clase.' );
    }

    public function updateLesson() {
        $this->checkRole( [ Role::ADMIN ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $id = (int) ( $_POST['id'] ?? 0 );
        $data = $this->parseLessonInput();
        if ( !$id || !$data ) {
            return $this->json( 'warning', 'Datos de clase inválidos.' );
        }

        if ( $this->lessonModel->update( $id, $data ) ) {
            return $this->json( 'success', 'Clase actualizada.', '?url=admin-lessons' );
        }
        return $this->json( 'error', 'No se pudo actualizar la clase.' );
    }

    public function deleteLesson() {
        $this->checkRole( [ Role::ADMIN ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $id = (int) ( $_POST['id'] ?? 0 );
        if ( !$id ) {
            return $this->json( 'warning', 'Clase no válida.' );
        }

        if ( $this->lessonModel->softDelete( $id ) ) {
            return $this->json( 'success', 'Clase eliminada.', '?url=admin-lessons' );
        }
        return $this->json( 'error', 'No se pudo eliminar la clase.' );
    }

    private function parseLessonInput(): ?array {
        $coachId  = (int) ( $_POST['coach_id'] ?? 0 );
        $levelId  = (int) ( $_POST['level_id'] ?? 0 );
        $day      = $_POST['day_of_week'] ?? '';
        $start    = $_POST['start_time'] ?? '';
        $end      = $_POST['end_time'] ?? '';
        $capacity = (int) ( $_POST['capacity'] ?? 0 );

        $validDays = [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];
        if ( !$coachId || !$levelId || !in_array( $day, $validDays, true ) || !$start || !$end || $capacity < 1 ) {
            return null;
        }
        if ( strtotime( $end ) <= strtotime( $start ) ) {
            return null;
        }

        return [
            'coach_id'     => $coachId,
            'level_id'     => $levelId,
            'day_of_week'  => $day,
            'start_time'   => $start,
            'end_time'     => $end,
            'capacity'     => $capacity,
        ];
    }

    public function editCoach() {
    $this->checkRole( [ Role::ADMIN ] );
    $id = (int) ( $_GET['id'] ?? 0 );
    if ( !$id ) {
        header( 'Location: ?url=admin-coaches' );
        exit;
    }
    $coaches = $this->coachModel->getAll();
    $coach   = array_filter( $coaches, fn($c) => (int) $c['id'] === $id );
    $coach   = reset( $coach );
    if ( !$coach ) {
        header( 'Location: ?url=admin-coaches' );
        exit;
    }
    $this->render( 'admin/coaches.view', [
        'titulo'      => 'Editar Coach',
        'coaches'     => $coaches,
        'specialties' => $this->coachModel->getSpecialties(),
        'editCoach'   => $coach,
    ] );
    }

    public function updateCoach() {
        $this->checkRole( [ Role::ADMIN ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $id   = (int) ( $_POST['id'] ?? 0 );
        $data = [
            'first_name'   => trim( $_POST['first_name'] ?? '' ),
            'last_name'    => trim( $_POST['last_name']  ?? '' ),
            'phone'        => trim( $_POST['phone']       ?? '' ),
            'specialty_id' => (int) ( $_POST['specialty_id'] ?? 0 ),
        ];

        if ( !$id || empty( $data['first_name'] ) || empty( $data['last_name'] ) ) {
            return $this->json( 'warning', 'Datos inválidos.' );
        }

        if ( $this->coachModel->update( $id, $data ) ) {
            return $this->json( 'success', 'Coach actualizado.', '?url=admin-coaches' );
        }
        return $this->json( 'error', 'No se pudo actualizar.' );
    }

    public function deleteCoach() {
        $this->checkRole( [ Role::ADMIN ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $id = (int) ( $_POST['id'] ?? 0 );
        if ( !$id ) {
            return $this->json( 'warning', 'Coach no válido.' );
        }

        if ( $this->coachModel->softDelete( $id ) ) {
            return $this->json( 'success', 'Coach eliminado.', '?url=admin-coaches' );
        }
        return $this->json( 'error', 'No se pudo eliminar.' );
    }

    public function deleteSwimmer() {
        $this->checkRole( [ Role::ADMIN ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $id = (int) ( $_POST['id'] ?? 0 );
        if ( !$id ) {
            return $this->json( 'warning', 'Nadador no válido.', Env::get( 'APP_URL' ) . '/?url=swimmers' );
        }

        if ( $this->swimmerModel->softDelete( $id ) ) {
            return $this->json( 'success', 'Nadador eliminado.', Env::get( 'APP_URL' ) . '/?url=swimmers' );
        }
        return $this->json( 'error', 'No se pudo eliminar al nadador.', Env::get( 'APP_URL' ) . '/?url=swimmers' );
    }
}

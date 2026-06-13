<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Coach.php';
require_once __DIR__ . '/../models/Auth.php';
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

    // --- Panel del coach ---

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
}

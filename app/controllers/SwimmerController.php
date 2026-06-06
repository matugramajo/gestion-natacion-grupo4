<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/Swimmer.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Booking.php';

class SwimmerController extends BaseController {
    private $pdo;
    private $profileModel;
    private $swimmerModel;
    private $lessonModel;
    private $bookingModel;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        $this->profileModel = new Profile( $pdo );
        $this->swimmerModel = new Swimmer( $pdo );
        $this->lessonModel  = new Lesson( $pdo );
        $this->bookingModel = new Booking( $pdo );
    }

    public function profile() {
        $this->checkRole( [ Role::SWIMMER ] );
        $authId = (int) $_SESSION['user_id'];
        $this->render( 'swimmer/profile.view', [
            'titulo'  => 'Mi Perfil',
            'profile' => $this->profileModel->findByAuthId( $authId ),
        ] );
    }

    public function updateProfile() {
        $this->checkRole( [ Role::SWIMMER ] );
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
            return $this->json( 'success', 'Perfil actualizado.', '?url=swimmer-profile' );
        }
        return $this->json( 'error', 'No se pudo actualizar el perfil.' );
    }

    public function lessons() {
        $this->checkRole( [ Role::SWIMMER ] );
        $swimmer = $this->swimmerModel->findByAuthId( (int) $_SESSION['user_id'] );
        $lessons = $this->lessonModel->getAll();
        $myBookings = $swimmer ? $this->bookingModel->getBySwimmerId( (int) $swimmer['id'] ) : [];
        $enrolledIds = array_column( $myBookings, 'lesson_id' );

        $this->render( 'swimmer/lessons.view', [
            'titulo'      => 'Clases Disponibles',
            'lessons'     => $lessons,
            'enrolledIds' => $enrolledIds,
            'myBookings'  => $myBookings,
        ] );
    }

    public function enroll() {
        $this->checkRole( [ Role::SWIMMER ] );
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return $this->json( 'error', 'Método no permitido.' );
        }

        $lessonId = (int) ( $_POST['lesson_id'] ?? 0 );
        $swimmer  = $this->swimmerModel->findByAuthId( (int) $_SESSION['user_id'] );

        if ( !$swimmer || !$lessonId ) {
            return $this->json( 'warning', 'Datos inválidos.' );
        }

        $lesson = $this->lessonModel->findById( $lessonId );
        if ( !$lesson ) {
            return $this->json( 'error', 'La clase no existe.' );
        }

        if ( $this->bookingModel->isEnrolled( (int) $swimmer['id'], $lessonId ) ) {
            return $this->json( 'warning', 'Ya estás inscripto en esta clase.' );
        }

        $enrolled = $this->bookingModel->countByLesson( $lessonId );
        if ( $enrolled >= (int) $lesson['capacity'] ) {
            return $this->json( 'warning', 'No hay cupos disponibles.' );
        }

        if ( $this->bookingModel->enroll( (int) $swimmer['id'], $lessonId ) ) {
            return $this->json( 'success', '¡Inscripción confirmada!', '?url=swimmer-lessons' );
        }
        return $this->json( 'error', 'No se pudo completar la inscripción.' );
    }
}

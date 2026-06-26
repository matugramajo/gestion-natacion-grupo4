<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Swimmer.php';
require_once __DIR__ . '/../models/Coach.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Profile.php';

class HomeController extends BaseController {
    private $swimmerModel;
    private $coachModel;
    private $lessonModel;
    private $bookingModel;
    private $profileModel;

    public function __construct() {
        global $pdo;
        $this->swimmerModel = new Swimmer( $pdo );
        $this->coachModel   = new Coach( $pdo );
        $this->lessonModel  = new Lesson( $pdo );
        $this->bookingModel = new Booking( $pdo );
        $this->profileModel = new Profile( $pdo );
    }

    public function index() {
        $this->checkAuth();

        $roleId = $this->currentRoleId();
        $stats  = [
            'swimmers' => $this->swimmerModel->countActive(),
            'coaches'  => $this->coachModel->countActive(),
            'lessons'  => $this->lessonModel->countActive(),
            'bookings' => $this->bookingModel->countActive(),
        ];

        $coachLessons = [];
        $myBookings   = [];
        if ( $roleId === Role::COACH ) {
            $coach = $this->coachModel->findByAuthId( (int) $_SESSION['user_id'] );
            if ( $coach ) {
                $coachLessons = $this->lessonModel->getByCoachId( (int) $coach['id'] );
            }
        }
        if ( $roleId === Role::SWIMMER ) {
            $swimmer = $this->swimmerModel->findByAuthId( (int) $_SESSION['user_id'] );
            if ( $swimmer ) {
                $myBookings = $this->bookingModel->getBySwimmerId( (int) $swimmer['id'] );
            }
        }

        $this->render( 'home.view', [
            'titulo'       => 'Dashboard - SwimManager',
            'roleId'       => $roleId,
            'stats'        => $stats,
            'coachLessons' => $coachLessons,
            'myBookings'   => $myBookings,
            'firstName'    => $_SESSION['first_name'] ?? 'Usuario',
        ] );
    }

    public function landing() {
        global $pdo;
        $lessonModel = new Lesson( $pdo );
        $this->render( 'landing.view', [
            'schedule' => $lessonModel->getPublicSchedule(),
        ] );
    }
}

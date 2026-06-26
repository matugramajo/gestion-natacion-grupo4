<?php

class Booking {
    private $db;

    public function __construct( $pdo ) {
        $this->db = $pdo;
    }

    public function getBySwimmerId( int $swimmerId ) {
        $sql = "SELECT b.*, l.day_of_week, l.start_time, l.end_time, lv.name AS level_name,
                       p.first_name AS coach_first_name, p.last_name AS coach_last_name
                FROM bookings b
                INNER JOIN lessons l ON b.lesson_id = l.id
                INNER JOIN levels lv ON l.level_id = lv.id
                LEFT JOIN coaches c ON l.coach_id = c.id
                LEFT JOIN profiles p ON c.auth_id = p.auth_id
                WHERE b.swimmer_id = ? AND b.status = 'Confirmed' AND b.deleted_at IS NULL
                ORDER BY FIELD(l.day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), l.start_time";
        $stmt = $this->db->prepare( $sql );
        $stmt->execute( [ $swimmerId ] );
        return $stmt->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getStudentsByLesson( int $lessonId ) {
        $sql = "SELECT p.first_name, p.last_name, p.phone, a.email, b.created_at
                FROM bookings b
                INNER JOIN swimmers s ON b.swimmer_id = s.id
                INNER JOIN profiles p ON s.auth_id = p.auth_id
                INNER JOIN auth a ON s.auth_id = a.id
                WHERE b.lesson_id = ? AND b.status = 'Confirmed' AND b.deleted_at IS NULL
                ORDER BY p.last_name, p.first_name";
        $stmt = $this->db->prepare( $sql );
        $stmt->execute( [ $lessonId ] );
        return $stmt->fetchAll( PDO::FETCH_ASSOC );
    }

    public function countByLesson( int $lessonId ): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM bookings WHERE lesson_id = ? AND status = 'Confirmed' AND deleted_at IS NULL"
        );
        $stmt->execute( [ $lessonId ] );
        return (int) $stmt->fetchColumn();
    }

    public function isEnrolled( int $swimmerId, int $lessonId ): bool {
        $stmt = $this->db->prepare(
            "SELECT id FROM bookings WHERE swimmer_id = ? AND lesson_id = ? AND status = 'Confirmed' AND deleted_at IS NULL LIMIT 1"
        );
        $stmt->execute( [ $swimmerId, $lessonId ] );
        return (bool) $stmt->fetch();
    }

    public function enroll( int $swimmerId, int $lessonId ) {
        $stmt = $this->db->prepare(
            "INSERT INTO bookings (swimmer_id, lesson_id, status) VALUES (?, ?, 'Confirmed')"
        );
        return $stmt->execute( [ $swimmerId, $lessonId ] );
    }

    public function countActive(): int {
        return (int) $this->db->query(
            "SELECT COUNT(*) FROM bookings WHERE status = 'Confirmed' AND deleted_at IS NULL"
        )->fetchColumn();
    }
}

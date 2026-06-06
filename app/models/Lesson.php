<?php

class Lesson {
    private $db;

    private const DAY_LABELS = [
        'Monday'    => 'Lunes',
        'Tuesday'   => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday'  => 'Jueves',
        'Friday'    => 'Viernes',
        'Saturday'  => 'Sábado',
    ];

    public function __construct( $pdo ) {
        $this->db = $pdo;
    }

    public static function dayLabel( string $day ): string {
        return self::DAY_LABELS[ $day ] ?? $day;
    }

    public function getAll() {
        $sql = "SELECT l.*, lv.name AS level_name,
                       p.first_name AS coach_first_name, p.last_name AS coach_last_name,
                       (SELECT COUNT(*) FROM bookings b
                        WHERE b.lesson_id = l.id AND b.status = 'Confirmed' AND b.deleted_at IS NULL) AS enrolled
                FROM lessons l
                INNER JOIN levels lv ON l.level_id = lv.id
                LEFT JOIN coaches c ON l.coach_id = c.id
                LEFT JOIN profiles p ON c.auth_id = p.auth_id
                WHERE l.deleted_at IS NULL
                ORDER BY FIELD(l.day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), l.start_time";
        return $this->db->query( $sql )->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getPublicSchedule() {
        return $this->getAll();
    }

    public function getByCoachId( int $coachId ) {
        $sql = "SELECT l.*, lv.name AS level_name,
                       (SELECT COUNT(*) FROM bookings b
                        WHERE b.lesson_id = l.id AND b.status = 'Confirmed' AND b.deleted_at IS NULL) AS enrolled
                FROM lessons l
                INNER JOIN levels lv ON l.level_id = lv.id
                WHERE l.coach_id = ? AND l.deleted_at IS NULL
                ORDER BY FIELD(l.day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), l.start_time";
        $stmt = $this->db->prepare( $sql );
        $stmt->execute( [ $coachId ] );
        return $stmt->fetchAll( PDO::FETCH_ASSOC );
    }

    public function findById( int $id ) {
        $stmt = $this->db->prepare( 'SELECT * FROM lessons WHERE id = ? AND deleted_at IS NULL LIMIT 1' );
        $stmt->execute( [ $id ] );
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    public function create( array $data ) {
        $sql = "INSERT INTO lessons (coach_id, level_id, day_of_week, start_time, end_time, capacity)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare( $sql );
        return $stmt->execute( [
            $data['coach_id'],
            $data['level_id'],
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time'],
            $data['capacity'],
        ] );
    }

    public function update( int $id, array $data ) {
        $sql = "UPDATE lessons SET coach_id = ?, level_id = ?, day_of_week = ?,
                start_time = ?, end_time = ?, capacity = ? WHERE id = ? AND deleted_at IS NULL";
        $stmt = $this->db->prepare( $sql );
        return $stmt->execute( [
            $data['coach_id'],
            $data['level_id'],
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time'],
            $data['capacity'],
            $id,
        ] );
    }

    public function softDelete( int $id ) {
        $stmt = $this->db->prepare( 'UPDATE lessons SET deleted_at = NOW() WHERE id = ?' );
        return $stmt->execute( [ $id ] );
    }

    public function countActive(): int {
        return (int) $this->db->query( "SELECT COUNT(*) FROM lessons WHERE deleted_at IS NULL" )->fetchColumn();
    }

    public function getLevels() {
        return $this->db->query( 'SELECT id, name FROM levels ORDER BY id' )->fetchAll( PDO::FETCH_ASSOC );
    }
}

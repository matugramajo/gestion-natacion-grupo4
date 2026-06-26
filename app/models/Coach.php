<?php

class Coach {
    private $db;

    public function __construct( $pdo ) {
        $this->db = $pdo;
    }

    public function getAll() {
        $sql = "SELECT c.id, c.auth_id, c.specialty_id,
                       p.first_name, p.last_name, p.phone, p.profile_image,
                       a.email,
                       s.name AS specialty,
                       s.name AS specialty_name
                FROM coaches c
                INNER JOIN auth a ON c.auth_id = a.id
                INNER JOIN profiles p ON c.auth_id = p.auth_id
                LEFT JOIN specialties s ON c.specialty_id = s.id
                WHERE c.deleted_at IS NULL AND a.deleted_at IS NULL
                ORDER BY p.last_name, p.first_name";
        return $this->db->query( $sql )->fetchAll( PDO::FETCH_ASSOC );
    }

    public function findByAuthId( int $authId ) {
        $stmt = $this->db->prepare(
            "SELECT c.*, sp.name AS specialty_name
             FROM coaches c
             INNER JOIN specialties sp ON c.specialty_id = sp.id
             WHERE c.auth_id = ? AND c.deleted_at IS NULL LIMIT 1"
        );
        $stmt->execute( [ $authId ] );
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    public function getSpecialties() {
        return $this->db->query( 'SELECT id, name FROM specialties ORDER BY name' )->fetchAll( PDO::FETCH_ASSOC );
    }

    public function create( array $data ) {
        $sqlProfile = "INSERT INTO profiles (auth_id, first_name, last_name, phone, profile_image)
                       VALUES (?, ?, ?, ?, ?)";
        $stmtProfile = $this->db->prepare( $sqlProfile );
        $profileOk = $stmtProfile->execute([
            $data['auth_id'],
            $data['first_name'],
            $data['last_name'],
            $data['phone'] ?? null,
            $data['profile_image'] ?? 'default-profile.png',
        ]);
        if ( !$profileOk ) return false;

        $stmtCoach = $this->db->prepare( 'INSERT INTO coaches (auth_id, specialty_id) VALUES (?, ?)' );
        return $stmtCoach->execute( [ $data['auth_id'], $data['specialty_id'] ] );
    }

    public function createForAuth( int $authId, int $specialtyId ) {
        $stmt = $this->db->prepare( 'INSERT INTO coaches (auth_id, specialty_id) VALUES (?, ?)' );
        return $stmt->execute( [ $authId, $specialtyId ] );
    }

    public function countActive(): int {
        return (int) $this->db->query( "SELECT COUNT(*) FROM coaches WHERE deleted_at IS NULL" )->fetchColumn();
    }

    public function update( int $coachId, array $data ): bool {
    $this->db->prepare(
        "UPDATE profiles SET first_name=?, last_name=?, phone=?, updated_at=NOW()
         WHERE auth_id = (SELECT auth_id FROM coaches WHERE id=? LIMIT 1)"
    )->execute([ $data['first_name'], $data['last_name'], $data['phone'] ?? null, $coachId ]);

    return (bool) $this->db->prepare(
        "UPDATE coaches SET specialty_id=?, updated_at=NOW() WHERE id=?"
    )->execute([ $data['specialty_id'], $coachId ]);
    }

    public function softDelete( int $coachId ): bool {
        $authId = $this->db->prepare(
            "SELECT auth_id FROM coaches WHERE id=? LIMIT 1"
        );
        $authId->execute([ $coachId ]);
        $row = $authId->fetch( PDO::FETCH_ASSOC );
        if ( !$row ) return false;

        $this->db->prepare(
            "UPDATE coaches SET deleted_at=NOW() WHERE id=?"
        )->execute([ $coachId ]);

        $this->db->prepare(
            "UPDATE auth SET deleted_at=NOW() WHERE id=?"
        )->execute([ $row['auth_id'] ]);

        return true;
    }
}

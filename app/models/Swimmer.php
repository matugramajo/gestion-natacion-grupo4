<?php

class Swimmer {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAll() {
        $sql = "SELECT s.id, s.auth_id, s.created_at, p.first_name, p.last_name, p.phone,
                       p.address, p.profile_image, p.birth_date, a.email
                FROM swimmers s
                INNER JOIN auth a ON s.auth_id = a.id
                INNER JOIN profiles p ON s.auth_id = p.auth_id
                WHERE s.deleted_at IS NULL AND a.deleted_at IS NULL";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        $sqlProfile = "INSERT INTO profiles (auth_id, first_name, last_name, phone, address, profile_image, birth_date)
               VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtProfile = $this->db->prepare( $sqlProfile );
        $profileOk = $stmtProfile->execute([
            $data['auth_id'],
            $data['first_name'],
            $data['last_name'],
            $data['phone']         ?? null,
            $data['address']       ?? null,
            $data['profile_image'] ?? 'default-profile.png',
            $data['birth_date']    ?? null,
        ]);

        if ( !$profileOk ) return false;
        $stmtSwimmer = $this->db->prepare( "INSERT INTO swimmers (auth_id) VALUES (?)" );
        return $stmtSwimmer->execute( [ $data['auth_id'] ] );
    }

    public function findByAuthId( int $authId ) {
        $stmt = $this->db->prepare(
            'SELECT s.* FROM swimmers s WHERE s.auth_id = ? AND s.deleted_at IS NULL LIMIT 1'
        );
        $stmt->execute( [ $authId ] );
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    public function countActive(): int {
        $sql = "SELECT COUNT(*) FROM swimmers WHERE deleted_at IS NULL";
        return (int) $this->db->query( $sql )->fetchColumn();
    }

    public function softDelete( int $swimmerId ): bool {
        $stmt = $this->db->prepare(
            "SELECT auth_id FROM swimmers WHERE id = ? LIMIT 1"
        );
        $stmt->execute( [ $swimmerId ] );
        $row = $stmt->fetch( PDO::FETCH_ASSOC );
        if ( !$row ) return false;

        $this->db->prepare(
            "UPDATE swimmers SET deleted_at = NOW() WHERE id = ?"
        )->execute( [ $swimmerId ] );

        $this->db->prepare(
            "UPDATE auth SET deleted_at = NOW() WHERE id = ?"
        )->execute( [ $row['auth_id'] ] );

        return true;
    }
}
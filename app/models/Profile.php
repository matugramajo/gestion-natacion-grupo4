<?php

class Profile {
    private $db;

    public function __construct( $pdo ) {
        $this->db = $pdo;
    }

    public function findByAuthId( int $authId ) {
        $stmt = $this->db->prepare( 'SELECT * FROM profiles WHERE auth_id = ? AND deleted_at IS NULL LIMIT 1' );
        $stmt->execute( [ $authId ] );
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    public function create( array $data ) {
        $sql = "INSERT INTO profiles (auth_id, first_name, last_name, phone, address, profile_image, birth_date)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare( $sql );
        return $stmt->execute( [
            $data['auth_id'],
            $data['first_name'],
            $data['last_name'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['profile_image'] ?? 'default-profile.png',
            $data['birth_date'] ?? null,
        ] );
    }

    public function update( int $authId, array $data ) {
        $sql = "UPDATE profiles SET first_name = ?, last_name = ?, phone = ?, address = ?,
                birth_date = ?, profile_image = ? WHERE auth_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->prepare( $sql );
        return $stmt->execute( [
            $data['first_name'],
            $data['last_name'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['birth_date'] ?? null,
            $data['profile_image'] ?? 'default-profile.png',
            $authId,
        ] );
    }
}

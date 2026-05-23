<?php

class Swimmer {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Obtiene todos los nadadores con sus correos electrónicos e imagen de perfil.
     */
    public function getAll() {
        // Agregamos s.profile_image a la consulta
        $sql = "SELECT s.id, s.auth_id, s.created_at,p.first_name, p.last_name, p.phone, p.profile_image, p.birth_date,a.email
        FROM swimmers s
        INNER JOIN auth a     ON s.auth_id = a.id
        INNER JOIN profiles p ON s.auth_id = p.auth_id
        WHERE s.deleted_at IS NULL AND a.deleted_at IS NULL";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta los datos personales vinculados a un user_id, incluyendo la imagen.
     * @param array $data ['user_id', 'first_name', 'last_name', 'phone', 'profile_image']
     */
    public function create(array $data) {
        // Agregamos profile_image al INSERT
        $sqlProfile = "INSERT INTO profiles (auth_id, first_name, last_name, phone, profile_image)
               VALUES (?, ?, ?, ?, ?)";
        $stmtProfile = $this->db->prepare( $sqlProfile );
        $profileOk = $stmtProfile->execute([
            $data['auth_id'],
            $data['first_name'],
            $data['last_name'],
            $data['phone']         ?? null,
            $data['profile_image'] ?? 'default-profile.png',
        ]);

        if ( !$profileOk ) return false;
        $stmtSwimmer = $this->db->prepare( "INSERT INTO swimmers (auth_id) VALUES (?)" );
        return $stmtSwimmer->execute( [ $data['auth_id'] ] );
    }
}
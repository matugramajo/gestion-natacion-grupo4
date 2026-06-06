<?php

class Coach {
    private $db;

    public function __construct( $pdo ) {
        $this->db = $pdo;
    }

    /**
     * Obtiene todos los coaches con sus datos de perfil y especialidad.
     */
    public function getAll() {
        $sql = "SELECT c.id, c.auth_id, c.specialty_id,
                       p.first_name, p.last_name, p.phone, p.profile_image,
                       a.email,
                       s.name AS specialty
                FROM coaches c
                INNER JOIN auth a        ON c.auth_id = a.id
                INNER JOIN profiles p    ON c.auth_id = p.auth_id
                LEFT JOIN specialties s  ON c.specialty_id = s.id
                WHERE c.deleted_at IS NULL AND a.deleted_at IS NULL";

        return $this->db->query( $sql )->fetchAll( PDO::FETCH_ASSOC );
    }

    /**
     * Obtiene todas las especialidades para el select del formulario.
     */
    public function getSpecialties() {
        return $this->db->query( "SELECT * FROM specialties ORDER BY name" )->fetchAll( PDO::FETCH_ASSOC );
    }

    /**
     * Crea el perfil del coach: inserta en profiles y coaches.
     * Se llama dentro de una transacción iniciada en el controlador.
     */
    public function create( array $data ) {
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

        $sqlCoach = "INSERT INTO coaches (auth_id, specialty_id) VALUES (?, ?)";
        $stmtCoach = $this->db->prepare( $sqlCoach );
        return $stmtCoach->execute([ $data['auth_id'], $data['specialty_id'] ]);
    }
}
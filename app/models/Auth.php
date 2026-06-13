<?php

class Auth {
    private $db;

    public function __construct( $pdo ) {
        $this->db = $pdo;
    }

    public function findByEmail( $email ) {
        $stmt = $this->db->prepare( 'SELECT * FROM auth WHERE email = ? AND deleted_at IS NULL LIMIT 1' );
        $stmt->execute( [ $email ] );
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    public function create( array $data ) {
        $hash = password_hash( $data[ 'password' ], PASSWORD_BCRYPT );
        $roleId = $data[ 'role_id' ] ?? 3;

        $stmt = $this->db->prepare( 'INSERT INTO auth (email, password, role_id) VALUES (?, ?, ?)' );

        if ( $stmt->execute( [ $data[ 'email' ], $hash, $roleId ] ) ) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function login( $email, $password ) {
        $sql = "SELECT a.*, p.first_name, p.last_name, p.profile_image, r.role_name
                FROM auth a
                LEFT JOIN profiles p ON a.id = p.auth_id
                INNER JOIN roles r ON a.role_id = r.id
                WHERE a.email = ? AND a.deleted_at IS NULL
                LIMIT 1";

        $stmt = $this->db->prepare( $sql );
        $stmt->execute( [ $email ] );
        $user = $stmt->fetch( PDO::FETCH_ASSOC );

        if ( $user && password_verify( $password, $user['password'] ) ) {
            $user['first_name']    = $user['first_name'] ?? 'Usuario';
            $user['profile_image'] = $user['profile_image'] ?? 'default-profile.png';
            return $user;
        }
        return false;
    }

    public function updatePasswordByEmail( $email, $hashedPassword ) {
        $stmt = $this->db->prepare( 'UPDATE auth SET password = ? WHERE email = ?' );
        return $stmt->execute( [ $hashedPassword, $email ] );
    }

    public function savePasswordToken( $email, $token, $expires ) {
        try {
            $stmtDel = $this->db->prepare( 'DELETE FROM password_resets WHERE email = ?' );
            $stmtDel->execute( [ $email ] );

            $stmtIns = $this->db->prepare( 'INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)' );
            return $stmtIns->execute( [ $email, $token, $expires ] );

        } catch ( PDOException $e ) {
            error_log( 'Error en savePasswordToken: ' . $e->getMessage() );
            return false;
        }
    }

    public function validateToken( $token ) {
        $stmt = $this->db->prepare( 'SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1' );
        $stmt->execute( [ $token ] );
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    public function deleteToken( $token ) {
        $stmt = $this->db->prepare( 'DELETE FROM password_resets WHERE token = ?' );
        return $stmt->execute( [ $token ] );
    }
}
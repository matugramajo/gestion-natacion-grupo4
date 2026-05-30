<?php
// app/config/db.php

/**
 * Usamos getenv para mayor seguridad y flexibilidad.
 * Si no encuentra la variable de entorno, usamos un valor por defecto (fallback).
 */

$host    = getenv('DB_HOST')    ?: 'localhost';
$port    = getenv('DB_PORT')    ?: '3306';
$db      = getenv('DB_NAME')    ?: ''; 
$user    = getenv('DB_USER')    ?: 'root';
$pass    = getenv('DB_PASS')    ?: '';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

     try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     error_log($e->getMessage());
     die("Error de conexión a la base de datos.");
}


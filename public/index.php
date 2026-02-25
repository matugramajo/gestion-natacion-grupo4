<?php
session_start();
require_once __DIR__ . '/../app/core/Env.php';

try {
    // Apuntamos a la raíz donde vive el archivo .env
    Env::load(__DIR__ . '/../.env');
} catch (Exception $e) {
    die("Error crítico de configuración: " . $e->getMessage());
} 


// Ahora sí, cargamos la base de datos
require_once __DIR__ . '/../app/config/db.php';

// Punto de entrada único (Single Entry Point)
require_once __DIR__ . '/router.php';
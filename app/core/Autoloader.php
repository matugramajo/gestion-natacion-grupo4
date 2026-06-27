<?php
// app/core/Autoloader.php

/**
 * Autoloader PSR-4 manual para el proyecto.
 *
 * Registra un autoloader con spl_autoload_register que mapea nombres de clase
 * a archivos PHP según la siguiente convención:
 *
 *   NombreClase  →  app/<directorio>/NombreClase.php
 *
 * El orden de búsqueda es: core → models → controllers → services → libs
 * Si la clase no se encuentra en ninguno de esos directorios, se ignora
 * silenciosamente (PHP lanzará su propio error si la clase sigue faltando).
 */

spl_autoload_register(function (string $className): void {

    // Directorios donde buscar, en orden de prioridad
    $directories = [
        __DIR__ . '/',                   // core/
        __DIR__ . '/../models/',         // models/
        __DIR__ . '/../controllers/',    // controllers/
        __DIR__ . '/../services/',       // services/
    ];

    foreach ($directories as $dir) {
        $file = $dir . $className . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

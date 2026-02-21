<?php
// app/controllers/HomeController.php
class HomeController {
    public function index() {
        $titulo = "Panel de Control - Escuela de Natación";
        // Aquí se cargaría la vista
        require_once __DIR__ . '/../views/home.view.php';
    }
}
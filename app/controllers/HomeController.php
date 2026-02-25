<?php
// app/controllers/HomeController.php

require_once __DIR__ . '/../core/BaseController.php';

class HomeController extends BaseController {
    /**
     * Muestra el panel principal.
     * Ahora usa el motor de renderizado heredado de BaseController
     * para mantener la coherencia en todo el proyecto.
     */
    public function index() {
        // Verificamos si el usuario está logueado antes de mostrar el panel
        $this->checkAuth();

        $data = [
            'title' => "Dashboard - Swimming School",
            'user'  => $_SESSION['email'] ?? 'Guest'
        ];
        
        // El método render busca automáticamente en /views/ y permite pasar datos
        $this->render('home.view', $data);
    }
}
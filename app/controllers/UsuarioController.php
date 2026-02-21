<?php

class UsuarioController {
    public function index() {
        $titulo = 'Seguridad y Usuarios';
        require_once __DIR__ . '/../views/usuarios/index.view.php';
    }
}
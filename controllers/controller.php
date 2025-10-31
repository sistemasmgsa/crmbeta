<?php

class Controller {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Cargar modelos
        foreach (glob(__DIR__ . "/../models/*.php") as $filename) {
            require_once $filename;
        }
    }

    public function view($view, $data = []) {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "Vista no encontrada: " . $viewFile;
        }
    }

    public function checkAuth() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . SITE_URL . 'index.php?controller=login&action=index');
            exit();
        }
    }

    public function isAdmin() {
        if ($_SESSION['usuario']['id_perfil'] != 1) {
            header('Location: ' . SITE_URL . 'index.php?controller=dashboard&action=index');
            exit();
        }
    }
}

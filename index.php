<?php
// Cargar configuración
require_once 'config/config.php';
require_once 'config/database.php';

// Iniciar sesión
session_start();

// Enrutamiento
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'login';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerFile = 'controllers/' . $controller . '_controller.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerName = ucfirst($controller) . 'Controller';
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "Acción no encontrada";
    }
} else {
    echo "Controlador no encontrado";
}

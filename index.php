<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'controllers/controller.php';

// Cargar modelos
spl_autoload_register(function ($class_name) {
    $model_path = 'models/' . strtolower($class_name) . '.php';
    if (file_exists($model_path)) {
        require_once $model_path;
    }
});

$controller_name = $_GET['controller'] ?? 'login';
$action_name = $_GET['action'] ?? 'index';

$controller_class = ucfirst($controller_name) . 'Controller';
$controller_file = 'controllers/' . $controller_name . '_controller.php';

if (file_exists($controller_file)) {
    require_once $controller_file;
    $controller = new $controller_class();
    if (method_exists($controller, $action_name)) {
        $controller->$action_name();
    } else {
        echo "Acción no encontrada";
    }
} else {
    echo "Controlador no encontrado";
}

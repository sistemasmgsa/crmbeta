<?php
// Cargar configuración
require_once 'config/config.php';

try {
    // Conexión sin especificar la base de datos para poder crearla
    $conn = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recrear la base de datos para un estado limpio
    $conn->exec("DROP DATABASE IF EXISTS " . DB_NAME);
    $conn->exec("CREATE DATABASE " . DB_NAME . " DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $conn->exec("USE " . DB_NAME);
    echo "Base de datos '" . DB_NAME . "' recreada.\n";

    // Ejecutar el script de la estructura de la base de datos y datos iniciales
    $sql = file_get_contents('db/database.sql');
    // Remove DELIMITER statements
    $sql = str_replace('DELIMITER $$', '', $sql);
    $sql = str_replace('DELIMITER ;', '', $sql);

    $queries = explode('$$', $sql);

    foreach ($queries as $query) {
        if (!empty(trim($query))) {
            $conn->exec($query);
        }
    }
    echo "Script 'database.sql' ejecutado correctamente.\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

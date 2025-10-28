<?php
// Cargar configuración
require_once '../config/config.php';

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
    $sql_structure = file_get_contents('database.sql');
    $conn->exec($sql_structure);
    echo "Script 'database.sql' ejecutado correctamente.\n";

    // Ejecutar el script de los procedimientos almacenados
    $sql_procedures = file_get_contents('stored_procedures.sql');

    // 1. Quitar los comandos DELIMITER
    $sql_procedures = preg_replace('/DELIMITER\s*\S+/s', '', $sql_procedures);

    // 2. Dividir el script en procedimientos individuales usando '$$' como separador
    $procedures = explode('$$', $sql_procedures);

    // 3. Ejecutar cada procedimiento
    foreach ($procedures as $procedure) {
        $procedure = trim($procedure);
        if (!empty($procedure)) {
            $conn->exec($procedure);
        }
    }

    echo "Script 'stored_procedures.sql' ejecutado correctamente.\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

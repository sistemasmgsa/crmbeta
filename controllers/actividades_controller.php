<?php
require_once 'controller.php';

class ActividadesController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    // Devuelve las actividades de un cliente en formato JSON
    public function listarPorCliente() {
        if (isset($_GET['id_cliente'])) {
            $database = new Database();
            $db = $database->getConnection();
            $actividad = new Actividad($db);
            $actividad->id_cliente = $_GET['id_cliente'];

            $stmt = $actividad->listarPorCliente();
            $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($actividades);
        }
    }

    // Crea una nueva actividad
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $actividad = new Actividad($db);

            $actividad->id_cliente = $_POST['id_cliente'] ?? null;
            $actividad->id_contacto = $_POST['id_contacto'] ?? null;
            $actividad->id_oportunidad = $_POST['id_oportunidad'] ?? null;
            $actividad->id_usuario = $_SESSION['usuario']['id_usuario'];
            $actividad->tipo_actividad = $_POST['tipo_actividad'];
            $actividad->asunto = $_POST['asunto'];
            $actividad->descripcion = $_POST['descripcion'];
            $actividad->fecha_actividad = $_POST['fecha_actividad'];

            if ($actividad->crear()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear la actividad.']);
            }
        }
    }
}

<?php
require_once 'controller.php';

class OportunidadesController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $oportunidad = new Oportunidad($db);

        $stmt = $oportunidad->listar();
        $oportunidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Organizar oportunidades por etapa para el Kanban
        $data['oportunidades_por_etapa'] = [
            'Calificación' => [],
            'Propuesta' => [],
            'Negociación' => [],
            'Ganada' => [],
            'Perdida' => []
        ];

        foreach ($oportunidades as $op) {
            $data['oportunidades_por_etapa'][$op['etapa']][] = $op;
        }

        $data['titulo'] = 'Oportunidades';
        $this->view('oportunidades/index', $data);
    }

    // Devuelve las oportunidades de un cliente en formato JSON
    public function listarPorCliente() {
        if (isset($_GET['id_cliente'])) {
            $database = new Database();
            $db = $database->getConnection();
            $oportunidad = new Oportunidad($db);
            $oportunidad->id_cliente = $_GET['id_cliente'];

            $stmt = $oportunidad->listarPorCliente();
            $oportunidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($oportunidades);
        }
    }

    public function crear() {
        $database = new Database();
        $db = $database->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $oportunidad = new Oportunidad($db);
            $oportunidad->id_cliente = $_POST['id_cliente'];
            $oportunidad->nombre_oportunidad = $_POST['nombre_oportunidad'];
            $oportunidad->valor_estimado = $_POST['valor_estimado'];
            $oportunidad->fecha_cierre = $_POST['fecha_cierre'];
            $oportunidad->etapa = $_POST['etapa'];

            if ($oportunidad->crear()) {
                header('Location: ' . SITE_URL . 'index.php?controller=oportunidades&action=index');
                exit();
            } else {
                $data['error'] = "Error al crear la oportunidad.";
            }
        }

        // Cargar clientes para el dropdown
        $cliente = new Cliente($db);
        $stmt_clientes = $cliente->listar();
        $data['clientes'] = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

        $data['titulo'] = 'Crear Oportunidad';
        $this->view('oportunidades/crear', $data);
    }

    public function editar() {
        $database = new Database();
        $db = $database->getConnection();
        $oportunidad = new Oportunidad($db);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $oportunidad->id_oportunidad = $_POST['id_oportunidad'];
            $oportunidad->id_cliente = $_POST['id_cliente'];
            $oportunidad->nombre_oportunidad = $_POST['nombre_oportunidad'];
            $oportunidad->valor_estimado = $_POST['valor_estimado'];
            $oportunidad->fecha_cierre = $_POST['fecha_cierre'];
            $oportunidad->etapa = $_POST['etapa'];

            if ($oportunidad->actualizar()) {
                header('Location: ' . SITE_URL . 'index.php?controller=oportunidades&action=index');
                exit();
            } else {
                $data['error'] = "Error al actualizar la oportunidad.";
            }
        }

        $oportunidad->id_oportunidad = $_GET['id'];
        $stmt = $oportunidad->obtener();
        $data['oportunidad'] = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Cargar clientes para el dropdown
        $cliente = new Cliente($db);
        $stmt_clientes = $cliente->listar();
        $data['clientes'] = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

        $data['titulo'] = 'Editar Oportunidad';
        $this->view('oportunidades/editar', $data);
    }

    public function eliminar() {
        $database = new Database();
        $db = $database->getConnection();
        $oportunidad = new Oportunidad($db);
        $oportunidad->id_oportunidad = $_GET['id'];

        if ($oportunidad->eliminar()) {
            header('Location: ' . SITE_URL . 'index.php?controller=oportunidades&action=index');
            exit();
        } else {
            header('Location: ' . SITE_URL . 'index.php?controller=oportunidades&action=index');
            exit();
        }
    }

    // Para actualizar la etapa desde el Kanban
    public function actualizarEtapa() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $oportunidad = new Oportunidad($db);

            $oportunidad->id_oportunidad = $_POST['id_oportunidad'];
            $oportunidad->etapa = $_POST['etapa'];

            if ($oportunidad->actualizarEtapa()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la etapa.']);
            }
        }
    }
}

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

        // Filtros
        $anio = $_GET['anio'] ?? date('Y');
        $mes = $_GET['mes'] ?? date('m');
        $etapa = $_GET['etapa'] ?? 'Todos';
        $id_usuario = null;
        if (isset($_SESSION['usuario']['id_perfil']) && $_SESSION['usuario']['id_perfil'] == 1 && isset($_GET['id_usuario'])) {
            $id_usuario = $_GET['id_usuario'];
        } else if (isset($_SESSION['usuario']['id_usuario'])) {
            $id_usuario = $_SESSION['usuario']['id_usuario'];
        }


        $stmt = $oportunidad->listar($anio, $mes, $etapa, $id_usuario);
        $oportunidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

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

        // Cargar usuarios para el filtro si es administrador
        if (isset($_SESSION['usuario']['id_perfil']) && $_SESSION['usuario']['id_perfil'] == 1) {
            $usuario = new Usuario($db);
            $stmt_usuarios = $usuario->listar();
            $data['usuarios'] = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);
            $stmt_usuarios->closeCursor();
        } else {
            $data['usuarios'] = [];
        }

        $data['titulo'] = 'Oportunidades';
        $data['anio'] = $anio;
        $data['mes'] = $mes;
        $data['etapa'] = $etapa;
        $data['id_usuario_seleccionado'] = $id_usuario;

        $this->view('oportunidades/index', $data);
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
            $oportunidad->usuario_creacion_id = $_SESSION['usuario']['id_usuario'];

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

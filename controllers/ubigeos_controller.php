<?php
require_once 'controller.php';
require_once 'models/ubigeo.php';

class UbigeosController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $ubigeo = new Ubigeo($db);

        $stmt = $ubigeo->listar();
        $data['ubigeos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data['titulo'] = 'Ubigeos';
        $this->view('ubigeos/index', $data);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $ubigeo = new Ubigeo($db);

            $ubigeo->departamento = $_POST['departamento'];
            $ubigeo->provincia = $_POST['provincia'];
            $ubigeo->distrito = $_POST['distrito'];

            if ($ubigeo->crear()) {
                header('Location: ' . SITE_URL . 'index.php?controller=ubigeos&action=index');
                exit();
            } else {
                $data['error'] = "Error al crear el ubigeo.";
                $this->view('ubigeos/crear', $data);
            }
        } else {
            $data['titulo'] = 'Crear Ubigeo';
            $this->view('ubigeos/crear', $data);
        }
    }

    public function editar() {
        $database = new Database();
        $db = $database->getConnection();
        $ubigeo = new Ubigeo($db);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ubigeo->id_ubigeo = $_POST['id_ubigeo'];
            $ubigeo->departamento = $_POST['departamento'];
            $ubigeo->provincia = $_POST['provincia'];
            $ubigeo->distrito = $_POST['distrito'];

            if ($ubigeo->actualizar()) {
                header('Location: ' . SITE_URL . 'index.php?controller=ubigeos&action=index');
                exit();
            } else {
                $data['error'] = "Error al actualizar el ubigeo.";
                $this->view('ubigeos/editar', $data);
            }
        } else {
            $ubigeo->id_ubigeo = $_GET['id'];
            $stmt = $ubigeo->obtener();
            $data['ubigeo'] = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $data['titulo'] = 'Editar Ubigeo';
            $this->view('ubigeos/editar', $data);
        }
    }

    public function eliminar() {
        $database = new Database();
        $db = $database->getConnection();
        $ubigeo = new Ubigeo($db);
        $ubigeo->id_ubigeo = $_GET['id'];

        if ($ubigeo->eliminar()) {
            header('Location: ' . SITE_URL . 'index.php?controller=ubigeos&action=index');
            exit();
        } else {
            header('Location: ' . SITE_URL . 'index.php?controller=ubigeos&action=index');
            exit();
        }
    }
}

<?php
require_once 'controller.php';

class PerfilesController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->isAdmin();
    }

    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $perfil = new Perfil($db);
        $stmt = $perfil->listar();
        $data['perfiles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('perfiles/index', $data);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $perfil = new Perfil($db);
            $perfil->nombre_perfil = $_POST['nombre_perfil'];
            if ($perfil->crear()) {
                header('Location: ' . SITE_URL . 'index.php?controller=perfiles&action=index');
                exit();
            } else {
                $data['error'] = "Error al crear el perfil.";
                $this->view('perfiles/crear', $data);
            }
        } else {
            $this->view('perfiles/crear');
        }
    }

    public function editar() {
        $database = new Database();
        $db = $database->getConnection();
        $perfil = new Perfil($db);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $perfil->id_perfil = $_POST['id_perfil'];
            $perfil->nombre_perfil = $_POST['nombre_perfil'];
            if ($perfil->actualizar()) {
                header('Location: ' . SITE_URL . 'index.php?controller=perfiles&action=index');
                exit();
            } else {
                $data['error'] = "Error al actualizar el perfil.";
                $this->view('perfiles/editar', $data);
            }
        } else {
            $perfil->id_perfil = $_GET['id'];
            $stmt = $perfil->obtener();
            $data['perfil'] = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->view('perfiles/editar', $data);
        }
    }

    public function eliminar() {
        $database = new Database();
        $db = $database->getConnection();
        $perfil = new Perfil($db);
        $perfil->id_perfil = $_GET['id'];
        if ($perfil->eliminar()) {
            header('Location: ' . SITE_URL . 'index.php?controller=perfiles&action=index');
            exit();
        } else {
            // Manejar error
            header('Location: ' . SITE_URL . 'index.php?controller=perfiles&action=index');
            exit();
        }
    }
}

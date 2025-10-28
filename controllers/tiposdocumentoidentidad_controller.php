<?php
require_once 'controller.php';
require_once 'models/tipodocumentoidentidad.php';

class TiposDocumentoIdentidadController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $tipodocumento = new TipoDocumentoIdentidad($db);

        $stmt = $tipodocumento->listar();
        $data['tiposdocumento'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data['titulo'] = 'Tipos de Documento de Identidad';
        $this->view('tiposdocumentoidentidad/index', $data);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $tipodocumento = new TipoDocumentoIdentidad($db);

            $tipodocumento->nombre_documento = $_POST['nombre_documento'];

            if ($tipodocumento->crear()) {
                header('Location: ' . SITE_URL . 'index.php?controller=tiposdocumentoidentidad&action=index');
                exit();
            } else {
                $data['error'] = "Error al crear el tipo de documento.";
                $this->view('tiposdocumentoidentidad/crear', $data);
            }
        } else {
            $data['titulo'] = 'Crear Tipo de Documento';
            $this->view('tiposdocumentoidentidad/crear', $data);
        }
    }

    public function editar() {
        $database = new Database();
        $db = $database->getConnection();
        $tipodocumento = new TipoDocumentoIdentidad($db);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tipodocumento->id_tipo_documento = $_POST['id_tipo_documento'];
            $tipodocumento->nombre_documento = $_POST['nombre_documento'];

            if ($tipodocumento->actualizar()) {
                header('Location: ' . SITE_URL . 'index.php?controller=tiposdocumentoidentidad&action=index');
                exit();
            } else {
                $data['error'] = "Error al actualizar el tipo de documento.";
                $this->view('tiposdocumentoidentidad/editar', $data);
            }
        } else {
            $tipodocumento->id_tipo_documento = $_GET['id'];
            $stmt = $tipodocumento->obtener();
            $data['tipodocumento'] = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $data['titulo'] = 'Editar Tipo de Documento';
            $this->view('tiposdocumentoidentidad/editar', $data);
        }
    }

    public function eliminar() {
        $database = new Database();
        $db = $database->getConnection();
        $tipodocumento = new TipoDocumentoIdentidad($db);
        $tipodocumento->id_tipo_documento = $_GET['id'];

        if ($tipodocumento->eliminar()) {
            header('Location: ' . SITE_URL . 'index.php?controller=tiposdocumentoidentidad&action=index');
            exit();
        } else {
            header('Location: ' . SITE_URL . 'index.php?controller=tiposdocumentoidentidad&action=index');
            exit();
        }
    }
}

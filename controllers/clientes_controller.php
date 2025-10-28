<?php
require_once 'controller.php';

class ClientesController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $cliente = new Cliente($db);

        $stmt = $cliente->listar();
        $data['clientes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data['titulo'] = 'Clientes';
        $this->view('clientes/index', $data);
    }

    public function crear() {
        $database = new Database();
        $db = $database->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cliente = new Cliente($db);

            $cliente->nombre_cliente = $_POST['nombre_cliente'];
            $cliente->direccion_cliente = $_POST['direccion_cliente'];
            $cliente->telefono_cliente = $_POST['telefono_cliente'];
            $cliente->website_cliente = $_POST['website_cliente'];
            $cliente->id_tipo_documento = $_POST['id_tipo_documento'];
            $cliente->numero_documento = $_POST['numero_documento'];
            $cliente->id_ubigeo = $_POST['id_ubigeo'];
            $cliente->correo_electronico = $_POST['correo_electronico'];
            $cliente->observaciones = $_POST['observaciones'];

            if ($cliente->crear()) {
                header('Location: ' . SITE_URL . 'index.php?controller=clientes&action=index');
                exit();
            } else {
                $data['error'] = "Error al crear el cliente.";
                $this->view('clientes/crear', $data);
            }
        } else {
            $tipodocumento = new TipoDocumentoIdentidad($db);
            $stmt = $tipodocumento->listar();
            $data['tipos_documento'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $ubigeo = new Ubigeo($db);
            $stmt = $ubigeo->listar();
            $ubigeos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data['ubigeos'] = $ubigeos;

            // Obtener listas únicas para los combos
            $data['departamentos'] = array_unique(array_column($ubigeos, 'departamento'));
            $data['provincias'] = array_unique(array_column($ubigeos, 'provincia'));
            $data['distritos'] = array_unique(array_column($ubigeos, 'distrito'));

            $data['titulo'] = 'Crear Cliente';
            $this->view('clientes/crear', $data);
        }
    }

    public function editar() {
        $database = new Database();
        $db = $database->getConnection();
        $cliente = new Cliente($db);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cliente->id_cliente = $_POST['id_cliente'];
            $cliente->nombre_cliente = $_POST['nombre_cliente'];
            $cliente->direccion_cliente = $_POST['direccion_cliente'];
            $cliente->telefono_cliente = $_POST['telefono_cliente'];
            $cliente->website_cliente = $_POST['website_cliente'];
            $cliente->id_tipo_documento = $_POST['id_tipo_documento'];
            $cliente->numero_documento = $_POST['numero_documento'];
            $cliente->id_ubigeo = $_POST['id_ubigeo'];
            $cliente->correo_electronico = $_POST['correo_electronico'];
            $cliente->observaciones = $_POST['observaciones'];

            if ($cliente->actualizar()) {
                header('Location: '. SITE_URL . 'index.php?controller=clientes&action=index');
                exit();
            } else {
                $data['error'] = "Error al actualizar el cliente.";
                $this->view('clientes/editar', $data);
            }
        } else {
            $cliente->id_cliente = $_GET['id'];
            $stmt = $cliente->obtener();
            $data['cliente'] = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $tipodocumento = new TipoDocumentoIdentidad($db);
            $stmt = $tipodocumento->listar();
            $data['tipos_documento'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $ubigeo = new Ubigeo($db);
            $stmt = $ubigeo->listar();
            $ubigeos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data['ubigeos'] = $ubigeos;

            // Obtener listas únicas para los combos
            $data['departamentos'] = array_unique(array_column($ubigeos, 'departamento'));
            $data['provincias'] = array_unique(array_column($ubigeos, 'provincia'));
            $data['distritos'] = array_unique(array_column($ubigeos, 'distrito'));

            $data['titulo'] = 'Editar Cliente';
            $this->view('clientes/editar', $data);
        }
    }

    public function eliminar() {
        $database = new Database();
        $db = $database->getConnection();
        $cliente = new Cliente($db);
        $cliente->id_cliente = $_GET['id'];

        if ($cliente->eliminar()) {
            header('Location: ' . SITE_URL . 'index.php?controller=clientes&action=index');
            exit();
        } else {
            // Manejar error (por ejemplo, mostrar un mensaje)
            header('Location: ' . SITE_URL . 'index.php?controller=clientes&action=index');
            exit();
        }
    }
}

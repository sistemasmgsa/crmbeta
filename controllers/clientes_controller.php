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

        $resultado = $cliente->crear();

        if ($resultado['resultado'] == 1) {
            // ✅ Cliente creado con éxito
            header('Location: ' . SITE_URL . 'index.php?controller=clientes&action=index');
            exit();
        } else {
            // ⚠️ Mostrar mensaje y quedarse en el formulario
            $data['error'] = $resultado['mensaje'];
            $data['form_data'] = $_POST; // para mantener los valores ingresados

            // Cargar listas (igual que en la parte else original)
            $tipodocumento = new TipoDocumentoIdentidad($db);
            $stmt = $tipodocumento->listar();
            $data['tipos_documento'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $ubigeo = new Ubigeo($db);
            $stmt = $ubigeo->listar();
            $ubigeos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data['ubigeos'] = $ubigeos;
            $data['departamentos'] = array_unique(array_column($ubigeos, 'departamento'));
            $data['provincias'] = array_unique(array_column($ubigeos, 'provincia'));
            $data['distritos'] = array_unique(array_column($ubigeos, 'distrito'));

            $data['titulo'] = 'Crear Cliente';
            $this->view('clientes/crear', $data);
        }
    } else {
        // Carga inicial del formulario
        $tipodocumento = new TipoDocumentoIdentidad($db);
        $stmt = $tipodocumento->listar();
        $data['tipos_documento'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $ubigeo = new Ubigeo($db);
        $stmt = $ubigeo->listar();
        $ubigeos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data['ubigeos'] = $ubigeos;
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

        $resultado = $cliente->actualizar();

        if ($resultado && $resultado['resultado'] == 1) {
            header('Location: ' . SITE_URL . 'index.php?controller=clientes&action=index');
            exit();
        } else {
            $data['error'] = $resultado ? $resultado['mensaje'] : 'Error al actualizar el cliente.';
            
            // Cargar datos necesarios para mantener el formulario activo
            $stmt = $cliente->obtener();
            $data['cliente'] = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $tipodocumento = new TipoDocumentoIdentidad($db);
            $stmt = $tipodocumento->listar();
            $data['tipos_documento'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $ubigeo = new Ubigeo($db);
            $stmt = $ubigeo->listar();
            $data['ubigeos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data['departamentos'] = array_unique(array_column($data['ubigeos'], 'departamento'));
            $data['provincias'] = array_unique(array_column($data['ubigeos'], 'provincia'));
            $data['distritos'] = array_unique(array_column($data['ubigeos'], 'distrito'));

            $data['titulo'] = 'Editar Cliente';
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
        $data['ubigeos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data['departamentos'] = array_unique(array_column($data['ubigeos'], 'departamento'));
        $data['provincias'] = array_unique(array_column($data['ubigeos'], 'provincia'));
        $data['distritos'] = array_unique(array_column($data['ubigeos'], 'distrito'));

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

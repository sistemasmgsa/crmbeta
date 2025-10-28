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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Database();
            $db = $database->getConnection();
            $cliente = new Cliente($db);

            $cliente->nombre_cliente = $_POST['nombre_cliente'];
            $cliente->direccion_cliente = $_POST['direccion_cliente'];
            $cliente->telefono_cliente = $_POST['telefono_cliente'];
            $cliente->website_cliente = $_POST['website_cliente'];

            if ($cliente->crear()) {
                header('Location: ' . SITE_URL . 'index.php?controller=clientes&action=index');
                exit();
            } else {
                $data['error'] = "Error al crear el cliente.";
                $this->view('clientes/crear', $data);
            }
        } else {
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

            if ($cliente->actualizar()) {
                header('Location: ' . SITE_URL . 'index.php?controller=clientes&action=index');
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

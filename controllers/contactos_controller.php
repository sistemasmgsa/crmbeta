<?php
require_once 'controller.php';

class ContactosController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    // Devuelve los contactos de un cliente en formato JSON para usar con AJAX
    public function listarPorCliente() {
        if (isset($_GET['id_cliente'])) {
            $database = new Database();
            $db = $database->getConnection();
            $contacto = new Contacto($db);
            $contacto->id_cliente = $_GET['id_cliente'];

            $stmt = $contacto->listarPorCliente();
            $contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($contactos);
        }
    }

    // Las siguientes funciones CRUD también devolverán JSON para una futura implementación con modales
    public function crear() {
        $database = new Database();
        $db = $database->getConnection();
        $contacto = new Contacto($db);

        $contacto->id_cliente = $_POST['id_cliente'];
        $contacto->nombre_contacto = $_POST['nombre_contacto'];
        $contacto->cargo_contacto = $_POST['cargo_contacto'];
        $contacto->correo_contacto = $_POST['correo_contacto'];
        $contacto->telefono_contacto = $_POST['telefono_contacto'];

        if ($contacto->crear()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el contacto.']);
        }
    }

    public function obtener() {
        $database = new Database();
        $db = $database->getConnection();
        $contacto = new Contacto($db);
        $contacto->id_contacto = $_GET['id_contacto'];
        $stmt = $contacto->obtener();
        $datos_contacto = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($datos_contacto);
    }

    public function actualizar() {
        $database = new Database();
        $db = $database->getConnection();
        $contacto = new Contacto($db);

        $contacto->id_contacto = $_POST['id_contacto'];
        $contacto->nombre_contacto = $_POST['nombre_contacto'];
        $contacto->cargo_contacto = $_POST['cargo_contacto'];
        $contacto->correo_contacto = $_POST['correo_contacto'];
        $contacto->telefono_contacto = $_POST['telefono_contacto'];

        if ($contacto->actualizar()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el contacto.']);
        }
    }

    public function eliminar() {
        $database = new Database();
        $db = $database->getConnection();
        $contacto = new Contacto($db);
        $contacto->id_contacto = $_POST['id_contacto'];

        if ($contacto->eliminar()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el contacto.']);
        }
    }
}

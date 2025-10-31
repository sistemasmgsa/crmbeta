<?php
require_once 'controller.php';
require_once 'models/usuario.php';

class CalendarioController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

public function index() {
    $database = new Database();
    $db = $database->getConnection();

    // ✅ Todos los usuarios pueden ver todas las actividades
    $usuario = new Usuario($db);
    $stmt_usuarios = $usuario->listar();
    $data['usuarios'] = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);
    $stmt_usuarios->closeCursor();

    // ✅ No filtramos por usuario
    $data['id_usuario_seleccionado'] = '';
    $id_usuario_filtro = null;

    // ✅ Llamamos al procedimiento que lista todas las actividades (sin filtro)
    $query = "CALL sp_actividades_listar_por_usuario(NULL)";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $data['actividades'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    $data['titulo'] = 'Calendario';
    $this->view('calendario/index', $data);
}

}

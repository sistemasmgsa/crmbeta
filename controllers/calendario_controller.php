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

        $data['usuarios'] = [];
        $data['id_usuario_seleccionado'] = null;
        $id_usuario_filtro = null;

        if ($_SESSION['usuario']['nombre_perfil'] === 'Administrador') {
            $usuario = new Usuario($db);
            $stmt_usuarios = $usuario->listar();
            $data['usuarios'] = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);
            $stmt_usuarios->closeCursor();

            if (isset($_GET['id_usuario']) && $_GET['id_usuario'] !== '') {
                $id_usuario_filtro = $_GET['id_usuario'];
                $data['id_usuario_seleccionado'] = $id_usuario_filtro;
            } else {
                $data['id_usuario_seleccionado'] = '';
            }
        } else {
            $id_usuario_filtro = $_SESSION['usuario']['id_usuario'];
        }

        $query = "CALL sp_actividades_listar_por_usuario(:id_usuario)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario_filtro, $id_usuario_filtro ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->execute();

        $data['actividades'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $data['titulo'] = 'Calendario';
        $this->view('calendario/index', $data);
    }
}

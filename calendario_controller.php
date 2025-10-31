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

        // ✅ Verificamos si el usuario logueado es Administrador (id_perfil = 1)
        if ($_SESSION['usuario']['id_perfil'] == 1) {
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
            // Si no es administrador, solo ve sus propias actividades
            $id_usuario_filtro = $_SESSION['usuario']['id_usuario'];
        }

        // ✅ Llamamos al procedimiento correcto
        $query = "CALL sp_actividades_listar_por_usuario(:id_usuario)";
        $stmt = $db->prepare($query);

        // ✅ Si el administrador no seleccionó usuario, mandamos NULL
        if ($id_usuario_filtro) {
            $stmt->bindParam(':id_usuario', $id_usuario_filtro, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':id_usuario', null, PDO::PARAM_NULL);
        }

        $stmt->execute();
        $data['actividades'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $data['titulo'] = 'Calendario';
        $this->view('calendario/index', $data);
    }
}

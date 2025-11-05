<?php
require_once 'controller.php';

class UsuariosController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->isAdmin();
    }

    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $usuario = new Usuario($db);
        $stmt = $usuario->listar();
        $data['usuarios'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('usuarios/index', $data);
    }

    public function crear() {
        $database = new Database();
        $db = $database->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario($db);
            $usuario->nombre_usuario = $_POST['nombre_usuario'];
            $usuario->apellido_usuario = $_POST['apellido_usuario'];
            $usuario->correo_usuario = $_POST['correo_usuario'];
            $usuario->clave_usuario = password_hash($_POST['clave_usuario'], PASSWORD_DEFAULT);
            $usuario->id_perfil = $_POST['id_perfil'];

            if ($usuario->crear()) {
                header('Location: ' . SITE_URL . 'index.php?controller=usuarios&action=index');
                exit();
            } else {
                $data['error'] = "Error al crear el usuario.";
            }
        }

        $perfil = new Perfil($db);
        $stmt = $perfil->listar();
        $data['perfiles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('usuarios/crear', $data);
    }



    public function editar() {
        $database = new Database();
        $db = $database->getConnection();
        $usuario = new Usuario($db);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario->id_usuario = $_POST['id_usuario'];
            $usuario->nombre_usuario = $_POST['nombre_usuario'];
            $usuario->apellido_usuario = $_POST['apellido_usuario'];
            $usuario->correo_usuario = $_POST['correo_usuario'];
            $usuario->id_perfil = $_POST['id_perfil'];

            // Solo actualizar la contraseña si se ha introducido una nueva
            if (!empty($_POST['clave_usuario'])) {
                $usuario->clave_usuario = password_hash($_POST['clave_usuario'], PASSWORD_DEFAULT);
            } else {
                // Obtener la contraseña actual para no sobreescribirla
                $stmt_usuario = $usuario->obtener();
                $usuario_actual = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
                $stmt_usuario->closeCursor();
                $usuario->clave_usuario = $usuario_actual['clave_usuario'];
            }

            if ($usuario->actualizar()) {
                header('Location: ' . SITE_URL . 'index.php?controller=usuarios&action=index');
                exit();
            } else {
                $data['error'] = "Error al actualizar el usuario.";
            }
        }

        $usuario->id_usuario = $_GET['id'];
        $stmt = $usuario->obtener();


        $data['usuario'] = $stmt->fetch(PDO::FETCH_ASSOC);


        $stmt->closeCursor();
        

        $perfil = new Perfil($db);
        $stmt_perfiles = $perfil->listar();
        $data['perfiles'] = $stmt_perfiles->fetchAll(PDO::FETCH_ASSOC);

        $this->view('usuarios/editar', $data);
    }





    public function eliminar() {
        $database = new Database();
        $db = $database->getConnection();
        $usuario = new Usuario($db);
        $usuario->id_usuario = $_GET['id'];

        if ($usuario->eliminar()) {
            header('Location: ' . SITE_URL . 'index.php?controller=usuarios&action=index');
            exit();
        } else {
            // Manejar error
            header('Location: ' . SITE_URL . 'index.php?controller=usuarios&action=index');
            exit();
        }
    }
}

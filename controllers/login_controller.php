<?php
require_once 'controller.php';
require_once __DIR__ . '/../util/email.php';

class LoginController extends Controller {

    public function index() {
        if (isset($_SESSION['usuario'])) {
            header('Location: ' . SITE_URL . 'index.php?controller=dashboard&action=index');
            exit();
        }
        $this->view('login/index');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
            $secretKey = '6Lf2h_srAAAAAGkTLCt3MExbYmdff01p5RlTSjQR';

            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
            $responseData = json_decode($verify);

            if (!$responseData->success) {
                $data['error'] = "Verificación reCAPTCHA fallida. Intente nuevamente.";
                $this->view('login/index', $data);
                exit;
            }

            $usuario = new Usuario($this->db);
            $usuario->correo_usuario = $_POST['correo'];

            // login() ahora devuelve un array, no un PDOStatement
            $row = $usuario->login();

            if ($row) {
                $clave_hash = $row['clave_usuario'];

                if (password_verify($_POST['clave'], $clave_hash)) {
                    // generarCodigoVerificacion() devuelve el código directamente
                    $codigo = $usuario->generarCodigoVerificacion($row['id_usuario']);

                    // Enviar correo con el código
                    $asunto = 'Código de Verificación';
                    $cuerpo = "Su código de verificación es: <b>$codigo</b>";
                    enviar_correo($row['correo_usuario'], $asunto, $cuerpo);

                    // Guardar en sesión
                    $_SESSION['id_usuario_verificar'] = $row['id_usuario'];
                    header('Location: ' . SITE_URL . 'index.php?controller=login&action=verify_code');
                    exit();
                } else {
                    $data['error'] = "La contraseña es incorrecta.";
                    $this->view('login/index', $data);
                }
            } else {
                $data['error'] = "El correo electrónico no existe.";
                $this->view('login/index', $data);
            }
        }
    }

    public function verify_code() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION['id_usuario_verificar'])) {
                $usuario = new Usuario($this->db);
                $resultado = $usuario->verificarCodigo($_SESSION['id_usuario_verificar'], $_POST['codigo']);

                if ($resultado) {
                    $_SESSION['usuario'] = $resultado;
                    $usuario->invalidarCodigo($_SESSION['id_usuario_verificar']);
                    unset($_SESSION['id_usuario_verificar']);
                    header('Location: ' . SITE_URL . 'index.php?controller=dashboard&action=index');
                    exit();
                } else {
                    $data['error'] = "El código de verificación es incorrecto o ha expirado.";
                    $this->view('login/verify_code', $data);
                }
            }
        } else {
            if (!isset($_SESSION['id_usuario_verificar'])) {
                header('Location: ' . SITE_URL . 'index.php?controller=login&action=index');
                exit();
            }
            $this->view('login/verify_code');
        }
    }

    public function logout() {
        session_destroy();
        header('Location: '. SITE_URL . 'index.php?controller=login&action=index');
        exit();
    }
}

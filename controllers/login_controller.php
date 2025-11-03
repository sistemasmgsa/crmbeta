<?php
require_once 'controller.php';

class LoginController extends Controller {

    public function index() {
        // Si ya hay una sesión activa, redirigir al dashboard
        if (isset($_SESSION['usuario'])) {
            header('Location: ' . SITE_URL . 'index.php?controller=dashboard&action=index');
            exit();
        }
        $this->view('login/index');
    }

     public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // 🔹 1. Verificar reCAPTCHA antes de todo
            $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
            $secretKey = '6Lf2h_srAAAAAGkTLCt3MExbYmdff01p5RlTSjQR'; // <-- pon tu clave secreta aquí

            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
            $responseData = json_decode($verify);

            if (!$responseData->success) {
                $data['error'] = "Verificación reCAPTCHA fallida. Intente nuevamente.";
                $this->view('login/index', $data);
                exit;
            }

            $usuario = new Usuario($this->db);

            $usuario->correo_usuario = $_POST['correo'];
            $stmt = $usuario->login();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $clave_hash = $row['clave_usuario'];

                if (password_verify($_POST['clave'], $clave_hash)) {
                    $_SESSION['usuario'] = $row;
                    header('Location: ' . SITE_URL . 'index.php?controller=dashboard&action=index');
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

    public function logout() {
        session_destroy();
        header('Location: '. SITE_URL . 'index.php?controller=login&action=index');
        exit();
    }
}

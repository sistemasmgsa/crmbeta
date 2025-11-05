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



                // Enviar correo con diseño HTML moderno
                $asunto = 'Codigo de verificacion para inicio de sesion CRM';

                $logo_url = SITE_URL . 'assets/img/logo.png'; // Cambia luego por tu logo real

                $cuerpo = '
                <!DOCTYPE html>
                <html lang="es">
                <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Codigo de Verificacion</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 40px auto;
                        background-color: #fff;
                        border-radius: 8px;
                        overflow: hidden;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                    }
                    .header {
                        background-color: #8B0000; /* Dark Red */
                        color: white;
                        text-align: center;
                        padding: 20px;
                        font-size: 20px;
                        font-weight: bold;
                    }
                    .logo {
                        text-align: center;
                        margin-top: 20px;
                    }
                    .logo img {
                        width: 150px;
                        height: auto;
                    }
                    .body {
                        padding: 30px;
                        color: #333;
                        line-height: 1.6;
                    }
                    .code-box {
                        text-align: center;
                        background-color: #f3f3f3;
                        padding: 15px;
                        border-radius: 6px;
                        font-size: 26px;
                        font-weight: bold;
                        letter-spacing: 3px;
                        margin: 25px 0;
                        color: #000;
                    }
                    .footer {
                        text-align: center;
                        font-size: 12px;
                        color: #777;
                        border-top: 1px solid #eaeaea;
                        padding: 15px;
                        background-color: #fafafa;
                    }
                </style>
                </head>
                <body>
                    <div class="container">
                        <div class="logo">
                            <img src="' . $logo_url . '" alt="Logo Advisor CRM">
                        </div>

                        <div style="
                            background-color: #8B0000;
                            color: white;
                            text-align: center;
                            padding: 20px;
                            font-size: 20px;
                            font-weight: bold;
                        ">
                            Verificacion de inicio de sesion
                        </div>

                        <div class="body">
                            <p>Hola <b>' . htmlspecialchars($row['nombre_usuario'] ?? 'Usuario') . '</b>,</p>
                            <p>Se ha solicitado un inicio de sesion en tu cuenta. Utiliza el siguiente codigo para completar el proceso:</p>
                            <div class="code-box">' . $codigo . '</div>
                            <p>Este codigo expirara en <b>10 minutos</b>.</p>
                            <p>Si no has solicitado este codigo, por favor cambia tu clave inmediatamente y contacta al equipo de soporte.</p>
                        </div>

                        <div class="footer">
                            Copyright ' . date("Y") . ' - Advisor CRM. Todos los derechos reservados.
                        </div>
                    </div>
                </body>
                </html>
                ';

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

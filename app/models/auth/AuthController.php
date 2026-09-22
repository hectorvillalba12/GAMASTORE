<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class AuthController {

    private $usuario;

    public function __construct() {
        $db            = (new Database())->connect();
        $this->usuario = new Usuario($db);
    }

    // VALIDAR CONTRASEÑA SEGURA: mínimo 8 caracteres, 1 mayúscula y 1 carácter especial
    private function passwordSegura($password) {
        return preg_match('/^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>_\-+=~`\[\];\'\/\\\\]).{8,}$/', $password);
    }

    public function showLogin() {
        include __DIR__ . '/Views/login.php';
    }

    public function login() {

        $email    = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->usuario->buscarPorEmail($email);

        if ($user && $user['estado'] === 'inactivo') {
            header("Location: index.php?action=login&msg=usuario_inactivo");
            exit();
        }

        if ($user) {

            // HASH
            if (password_verify($password, $user['password'])) {
                $_SESSION['usuario'] = $this->usuario->obtener($user['id_usuario']);
                header("Location: index.php?action=dashboard");
                exit();
            }

            // MD5 (migración a bcrypt)
            elseif ($password === $user['password'] || md5($password) === $user['password']) {
                $nuevoHash = password_hash($password, PASSWORD_DEFAULT);
                $this->usuario->actualizarPassword($user['id_usuario'], $nuevoHash);
                $_SESSION['usuario'] = $this->usuario->obtener($user['id_usuario']);
                header("Location: index.php?action=dashboard");
                exit();
            }
        }

        header("Location: index.php?action=login&msg=error_login");
        exit();
    }

    // FORM OLVIDE CONTRASEÑA
    public function forgotPassword() {
        require __DIR__ . '/Views/forgot.php';
    }

    // ENVIAR EMAIL CON TOKEN
    public function sendReset() {

        $email = $_POST['email'];
        $user  = $this->usuario->buscarPorEmail($email);

        if ($user) {

            $token  = bin2hex(random_bytes(50));
            $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $this->usuario->guardarToken($user['id_usuario'], $token, $expira);

            $link = "http://localhost/gamastorefinal/public/index.php?action=resetForm&token=$token";

            require __DIR__ . '/../../../vendor/autoload.php';

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = Env::get('MAIL_HOST', 'smtp.gmail.com');
                $mail->SMTPAuth   = true;
                $mail->Username   = Env::get('MAIL_USERNAME');
                $mail->Password   = Env::get('MAIL_PASSWORD');
                $mail->SMTPSecure = Env::get('MAIL_ENCRYPTION', 'tls');
                $mail->Port       = (int) Env::get('MAIL_PORT', 587);

                $mail->setFrom(Env::get('MAIL_USERNAME'), Env::get('MAIL_FROM_NAME', 'GAMASTORE'));
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Recuperar contraseña';
                $mail->Body    = "
                    <h3>Recuperación de contraseña</h3>
                    <p>Hacé click en el siguiente enlace:</p>
                    <a href='$link'>$link</a>
                    <p>Expira en 1 hora</p>
                ";

                $mail->send();
                header("Location: index.php?action=forgot&msg=enviado");
                exit();

            } catch (Exception $e) {
                header("Location: index.php?action=forgot&msg=error");
                exit();
            }

        } else {
            header("Location: index.php?action=forgot&msg=no_encontrado");
            exit();
        }
    }

    // FORM RESET
    public function resetForm() {
        include __DIR__ . '/Views/reset.php';
    }

    // GUARDAR NUEVA PASSWORD
    public function resetPassword() {

        $token    = $_POST['token'];
        $password = $_POST['password'];

        if (!$this->passwordSegura($password)) {
            header("Location: index.php?action=resetForm&token=$token&error=password_debil");
            exit();
        }

        $user = $this->usuario->buscarPorToken($token);

        if ($user) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $this->usuario->actualizarPassword($user['id_usuario'], $hash);
            $this->usuario->limpiarToken($user['id_usuario']);
            header("Location: index.php?action=login&msg=ok");
            exit();
        } else {
            header("Location: index.php?action=login&msg=error");
            exit();
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
    }

    public function showRegister() {
        include __DIR__ . '/Views/register.php';
    }

    public function register() {

        $email     = trim($_POST['email']             ?? '');
        $password  = $_POST['password']               ?? '';
        $confirmar = $_POST['confirmar_password']     ?? '';
        $rol       = trim($_POST['rol']               ?? '');

        if (empty($email) || empty($password) || empty($confirmar) || empty($rol)) {
            header("Location: index.php?action=register&error=campos_requeridos");
            exit();
        }

        if ($password !== $confirmar) {
            header("Location: index.php?action=register&error=passwords_no_coinciden");
            exit();
        }

        if (!$this->passwordSegura($password)) {
            header("Location: index.php?action=register&error=password_debil");
            exit();
        }

        if ($this->usuario->buscarPorEmail($email)) {
            header("Location: index.php?action=register&error=email_duplicado");
            exit();
        }

        $hash   = password_hash($password, PASSWORD_DEFAULT);
        $estado = ($rol === 'empleado') ? 'inactivo' : 'activo';

        $this->usuario->registrar($email, $hash, $rol, $estado);

        header("Location: index.php?action=login&msg=registro_ok");
        exit();
    }
}
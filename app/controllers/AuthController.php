<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class AuthController {

    public function showLogin() {
        include '../app/views/auth/login.php';
    }

    public function login() {

        $db = new Database();
        $conn = $db->connect();

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM usuario WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $user['estado'] === 'inactivo') {
            header("Location: index.php?action=login&msg=usuario_inactivo");
            exit();
        }

        if ($user) {

            // HASH
            if (password_verify($password, $user['password'])) {

                $stmt2 = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
                $stmt2->execute([$user['id_usuario']]);
                $_SESSION['usuario'] = $stmt2->fetch(PDO::FETCH_ASSOC);

                header("Location: index.php?action=dashboard");
                exit();
            }

            // MD5
            elseif ($password === $user['password'] || md5($password) === $user['password']) {

                $nuevoHash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("UPDATE usuario SET password=? WHERE id_usuario=?");
                $stmt->execute([$nuevoHash, $user['id_usuario']]);

                $stmt2 = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
                $stmt2->execute([$user['id_usuario']]);
                $_SESSION['usuario'] = $stmt2->fetch(PDO::FETCH_ASSOC);

                header("Location: index.php?action=dashboard");
                exit();
            }
        }

        header("Location: index.php?action=login&msg=error_login");
        exit();
    }

    // FORM OLVIDE CONTRASEÑA
    public function forgotPassword() {
        require '../app/views/auth/forgot.php';
    }

    // ENVIAR EMAIL CON TOKEN
    public function sendReset() {

        $db = new Database();
        $conn = $db->connect();

        $email = $_POST['email'];

        $stmt = $conn->prepare("SELECT * FROM usuario WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {

            $token = bin2hex(random_bytes(50));
            $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $stmt = $conn->prepare("UPDATE usuario SET reset_token=?, token_expira=? WHERE id_usuario=?");
            $stmt->execute([$token, $expira, $user['id_usuario']]);

            $link = "http://localhost/gamastorefinal/public/index.php?action=resetForm&token=$token";

            require '../vendor/autoload.php';

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'villalbahector257@gmail.com';
                $mail->Password = 'asnh pmmf wfst igbj';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('villalbahector257@gmail.com', 'GAMASTORE');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Recuperar contraseña';
                $mail->Body = "
                    <h3>Recuperación de contraseña</h3>
                    <p>Hacé click en el siguiente enlace:</p>
                    <a href='$link'>$link</a>
                    <p>Expira en 1 hora</p>
                ";

                $mail->send();
                echo "Correo enviado correctamente";

            } catch (Exception $e) {
                echo "Error al enviar correo: {$mail->ErrorInfo}";
            }

        } else {
            echo "Email no encontrado";
        }
    }

    // FORM RESET
    public function resetForm() {
        include '../app/views/auth/reset.php';
    }

    // GUARDAR NUEVA PASSWORD
    public function resetPassword() {

        $db = new Database();
        $conn = $db->connect();

        $token = $_POST['token'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("
            SELECT * FROM usuario 
            WHERE reset_token = ? 
            AND token_expira > NOW()
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                UPDATE usuario 
                SET password = ?, reset_token = NULL, token_expira = NULL 
                WHERE id_usuario = ?
            ");

            if ($stmt->execute([$hash, $user['id_usuario']])) {
                header("Location: index.php?action=login&msg=ok");
                exit();
            } else {
                echo "Error al actualizar contraseña";
            }

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
        include '../app/views/auth/register.php';
    }

    public function register() {

        $db   = new Database();
        $conn = $db->connect();

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

        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            header("Location: index.php?action=register&error=email_duplicado");
            exit();
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuario (email, password, rol, perfil_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $hash, $rol, 1]);

        header("Location: index.php?action=login&msg=registro_ok");
        exit();
    }
}
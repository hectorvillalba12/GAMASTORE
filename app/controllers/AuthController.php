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

        if ($user) {

            // HASH
            if (password_verify($password, $user['password'])) {
                $_SESSION['usuario'] = $user;
                header("Location: index.php?action=dashboard");
                exit();
            }

            // MD5
            elseif ($password === $user['password'] || md5($password) === $user['password']) {

                $nuevoHash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("UPDATE usuario SET password=? WHERE id_usuario=?");
                $stmt->execute([$nuevoHash, $user['id_usuario']]);

                $_SESSION['usuario'] = $user;
                header("Location: index.php?action=dashboard");
                exit();
            }
        }

        echo "Error login";
    }

    //FORM OLVIDE CONTRASEÑA
    public function forgotPassword() {
        require '../app/views/auth/forgot.php';
    }

    //  ENVIAR EMAIL CON TOKEN
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

            // LINK DE RECUPERACIÓN
            $link = "http://localhost/gamastorefinal/public/index.php?action=resetForm&token=$token";

            //PHPMailer
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

    // Buscar usuario con token válido
    $stmt = $conn->prepare("
        SELECT * FROM usuario 
        WHERE reset_token = ? 
        AND token_expira > NOW()
    ");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {

        // HASH SEGURO
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Guardar nueva contraseña
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
}
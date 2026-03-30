<?php
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

            // Contraseña encriptada
            if (password_verify($password, $user['password'])) {
                $_SESSION['usuario'] = $user;
                header("Location: index.php?action=productos");
                exit();
            }

            // Contraseña en texto plano
            if ($password === $user['password']) {

                $nuevoHash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("UPDATE usuario SET password=? WHERE id_usuario=?");
                $stmt->execute([$nuevoHash, $user['id_usuarios']]);

                $_SESSION['usuario'] = $user;
                header("Location: index.php?action=productos");
                exit();
            }
        }

        // 🔴 ESTE VA FUERA DEL IF
        echo "Error login";
    }
    // Mostrar formulario "olvidé contraseña"
    public function forgotPassword() {
        include '../app/views/auth/forgot.php';
    }

// Enviar token
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

            header("Location: index.php?action=resetForm&token=$token");
            exit();
        }else {
            echo "Email no encontrado";
        }
    }

// Mostrar formulario nueva contraseña
    public function resetForm() {
        include '../app/views/auth/reset.php';
    }   

// Guardar nueva contraseña
    public function resetPassword() {

        $db = new Database();
        $conn = $db->connect();

        $token = $_POST['token'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM usuario WHERE reset_token=? AND token_expira > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {

            $nuevoHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE usuario SET password=?, reset_token=NULL, token_expira=NULL WHERE id_usuario=?");
            $stmt->execute([$nuevoHash, $user['id_usuario']]);

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
}
<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Cliente.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ClienteController {

    private $db;
    private $persona;
    private $cliente;

    public function __construct() {
        $database      = new Database();
        $this->db      = $database->connect();
        $this->persona = new Persona($this->db);
        $this->cliente = new Cliente($this->db);
    }

    public function index() {
        Auth::verificarModulo('clientes');
        $clientes          = $this->cliente->listar();
        $clientesInactivos = $this->cliente->listarInactivos();
        require __DIR__ . '/../views/clientes/index.php';
    }

    public function create() {
        Auth::verificarModulo('clientes');
        require __DIR__ . '/../views/clientes/create.php';
    }

    public function store() {
        Auth::verificarModulo('clientes');
        $nombre   = trim($_POST['nombre']   ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $dni      = trim($_POST['dni']      ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email    = trim($_POST['email']    ?? '');

        if ($this->persona->existeDni($dni)) {
            header("Location: index.php?action=create_cliente&error=dni_duplicado");
            exit();
        }

        if (!empty($email) && $this->persona->existeEmail($email)) {
            header("Location: index.php?action=create_cliente&error=email_duplicado");
            exit();
        }

        $id_persona = $this->persona->crear([
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'dni'      => $dni,
            'telefono' => $telefono,
            'email'    => $email
        ]);

        $this->cliente->crear($id_persona, null);

        if (!empty($email)) {
            $this->enviarEmailVerificacion($email, $nombre);
        }

        header("Location: index.php?action=clientes&ok=1");
        exit();
    }

    public function edit($id = null) {
        Auth::verificarModulo('clientes');
        $id = $id ?? $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?action=clientes");
            exit();
        }
        $cliente = $this->cliente->obtener($id);
        if (!$cliente) {
            header("Location: index.php?action=clientes");
            exit();
        }
        require __DIR__ . '/../views/clientes/edit.php';
    }

    public function update() {
        Auth::verificarModulo('clientes');
        $id_persona = $_POST['id_persona'];
        $id_cliente = $_POST['id_cliente'];
        $dni        = trim($_POST['dni']      ?? '');
        $email      = trim($_POST['email']    ?? '');
        $nombre     = trim($_POST['nombre']   ?? '');
        $apellido   = trim($_POST['apellido'] ?? '');
        $telefono   = trim($_POST['telefono'] ?? '');

        if ($this->persona->existeDni($dni, $id_persona)) {
            header("Location: index.php?action=edit_cliente&id={$id_cliente}&error=dni_duplicado");
            exit();
        }

        if (!empty($email) && $this->persona->existeEmail($email, $id_persona)) {
            header("Location: index.php?action=edit_cliente&id={$id_cliente}&error=email_duplicado");
            exit();
        }

        $this->persona->actualizar([
            'id'       => $id_persona,
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'dni'      => $dni,
            'telefono' => $telefono,
            'email'    => $email
        ]);

        header("Location: index.php?action=clientes&ok=1");
        exit();
    }

    public function delete() {
        Auth::verificarModulo('clientes');
        $this->cliente->darDeBaja($_GET['id']);
        header("Location: index.php?action=clientes&baja=1");
        exit();
    }

    public function reactivar() {
        Auth::verificarModulo('clientes');
        $this->cliente->reactivar($_GET['id']);
        header("Location: index.php?action=clientes&reactivado=1");
        exit();
    }

    public function verificarEmail() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            header("Location: index.php?action=login&msg=token_invalido");
            exit();
        }
        $ok = $this->cliente->verificarEmail($token);
        if ($ok) {
            header("Location: index.php?action=login&msg=email_verificado");
        } else {
            header("Location: index.php?action=login&msg=token_invalido");
        }
        exit();
    }

    private function enviarEmailVerificacion($email, $nombre) {
        require_once __DIR__ . '/../../vendor/autoload.php';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'villalbahector257@gmail.com';
            $mail->Password   = 'asnh pmmf wfst igbj';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('villalbahector257@gmail.com', 'GAMASTORE');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido/a a GamaStore 👟';
            $mail->Body    = "
                <h3>Hola {$nombre}, tu cuenta fue registrada en GamaStore 👟</h3>
                <p>Te informamos que tu email <strong>{$email}</strong> fue registrado correctamente en nuestro sistema.</p>
                <p>Ya podés acercarte a nuestra tienda y disfrutar de nuestros productos.</p>
                <br>
                <p style='color:#888;font-size:13px;'>Si no solicitaste este registro, ignorá este correo.</p>
            ";
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar email de bienvenida: " . $mail->ErrorInfo);
        }
    }
}
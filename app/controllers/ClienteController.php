<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {

    private $db;
    private $persona;
    private $cliente;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();

        $this->persona = new Persona($this->db);
        $this->cliente = new Cliente($this->db);
    }

    // LISTAR
    public function index() {
        $clientes = $this->cliente->listar();
        require __DIR__ . '/../views/clientes/index.php';
    }

    //  MOSTRAR FORM CREAR
    public function create() {
        require __DIR__ . '/../views/clientes/create.php';
    }

    // GUARDAR
    public function store() {
        $data = [
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'dni' => $_POST['dni'],
            'telefono' => $_POST['telefono'],
            'email' => $_POST['email']
        ];

        $id_persona = $this->persona->crear($data);
        $this->cliente->crear($id_persona);

        header("Location: index.php?action=clientes");
        exit();
    }

    //  EDITAR
    public function edit() {
        $id = $_GET['id'];
        $cliente = $this->cliente->obtener($id);

        require __DIR__ . '/../views/clientes/edit.php';
    }

    // ACTUALIZAR
    public function update() {
        $data = [
            'id' => $_POST['id_persona'],
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'dni' => $_POST['dni'],
            'telefono' => $_POST['telefono'],
            'email' => $_POST['email']
        ];

        $this->persona->actualizar($data);

        header("Location: index.php?action=clientes");
        exit();
    }

    // ELIMINAR
    public function delete() {
        $id = $_GET['id'];
        $this->cliente->eliminar($id);

        header("Location: index.php?action=clientes");
        exit();
    }
}
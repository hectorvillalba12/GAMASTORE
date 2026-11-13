<?php
require_once __DIR__ . '/configuracion.php';

class ConfiguracionController {

    private $configuracion;
    private $db;

    public function __construct() {
        $this->db            = (new Database())->connect();
        $this->configuracion = new Configuracion($this->db);
    }

    // LISTAR marcas y categorias
    public function index() {
        Auth::verificarModulo('configuraciones');

        $marcas     = $this->configuracion->listarMarcas();
        $categorias = $this->configuracion->listarCategorias();
        $talles     = $this->configuracion->listarTalles();   

        require __DIR__ . '/views/index.php';
    }

    // GUARDAR nueva marca
    public function storeMarca() {
        Auth::verificarModulo('configuraciones');

        $nombre = trim($_POST['marcas_disponibles'] ?? '');

        if (empty($nombre)) {
            header("Location: index.php?action=configuraciones&error=marca_vacia");
            exit();
        }

        if ($this->configuracion->existeMarca($nombre)) {
            header("Location: index.php?action=configuraciones&error=marca_duplicada");
            exit();
        }

        $this->configuracion->crearMarca($nombre);

        header("Location: index.php?action=configuraciones&ok_marca=1");
        exit();
    }

    // GUARDAR nueva categoria
    public function storeCategoria() {
        Auth::verificarModulo('configuraciones');

        $nombre = trim($_POST['tipodezapatilla'] ?? '');

        if (empty($nombre)) {
            header("Location: index.php?action=configuraciones&error=categoria_vacia");
            exit();
        }

        if ($this->configuracion->existeCategoria($nombre)) {
            header("Location: index.php?action=configuraciones&error=categoria_duplicada");
            exit();
        }

        $this->configuracion->crearCategoria($nombre);

        header("Location: index.php?action=configuraciones&ok_categoria=1");
        exit();
    }
    
    // GUARDAR nuevo talle
    public function storeTalle() {
        Auth::verificarModulo('configuraciones');

        $nombre = trim($_POST['talles_disponibles'] ?? '');

        if ($nombre === '') {
            header("Location: index.php?action=configuraciones&error=talle_vacio");
            exit();
        }

        // Solo números (con medio talle opcional): 38, 38.5 o 38,5
        if (!preg_match('/^\d{1,2}([.,]5)?$/', $nombre)) {
            header("Location: index.php?action=configuraciones&error=talle_invalido");
            exit();
        }
        $nombre = str_replace(',', '.', $nombre);

        if ($this->configuracion->existeTalle($nombre)) {
            header("Location: index.php?action=configuraciones&error=talle_duplicado");
            exit();
        }

        $this->configuracion->crearTalle($nombre);

        header("Location: index.php?action=configuraciones&ok_talle=1");
        exit();
    }

}
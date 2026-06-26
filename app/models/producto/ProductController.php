<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController {

    private $producto;
    private $db;

    public function __construct() {
        $this->db      = (new Database())->connect();
        $this->producto = new Product($this->db);
    }

    public function index() {
        Auth::verificarModulo('productos');
        $productos          = $this->producto->listar();
        $productosInactivos = $this->producto->listarInactivos();
        require __DIR__ . '/../views/productos/index.php';
    }

    public function create() {
        Auth::verificarModulo('productos');
        $marcas     = $this->db->query("SELECT * FROM marca ORDER BY marcas_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $talles     = $this->db->query("SELECT * FROM talle ORDER BY talles_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $colores    = $this->db->query("SELECT * FROM color ORDER BY colores_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $categorias = $this->db->query("SELECT * FROM categoria ORDER BY tipodezapatilla ASC")->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/productos/create.php';
    }

    public function store() {
        Auth::verificarModulo('productos');
        $nombre    = trim($_POST['nombre'] ?? '');
        $tipo      = trim($_POST['tipodezapatillas'] ?? '');
        $precio    = $_POST['precio'] ?? '';
        $talle     = $_POST['talle_id_talle'] ?? '';
        $marca     = $_POST['marca_id_marca'] ?? '';
        $color     = $_POST['color_id_color'] ?? '';
        $categoria = $_POST['categoria_id_categoria'] ?? '';

        if (empty($nombre) || empty($tipo) || empty($precio) || empty($talle) || empty($marca) || empty($color) || empty($categoria)) {
            header("Location: index.php?action=crear&error=campos_requeridos");
            exit();
        }

        if (!is_numeric($precio) || $precio <= 0) {
            header("Location: index.php?action=crear&error=precio_invalido");
            exit();
        }

        $data = [
            'nombre'           => $nombre,
            'tipodezapatillas' => $tipo,
            'precio'           => $precio,
            'talle'            => $talle,
            'marca'            => $marca,
            'color'            => $color,
            'categoria'        => $categoria
        ];

        $id_producto = $this->producto->crear($data);

        $stmt = $this->db->prepare("
            INSERT INTO inventario (stock_actual, stock_maximo, stock_minimo, ubicacion, producto_id_producto)
            VALUES (0, 0, 0, '', :id_producto)
        ");
        $stmt->execute(['id_producto' => $id_producto]);

        header("Location: index.php?action=productos&ok=1");
        exit();
    }

    public function edit() {
        Auth::verificarModulo('productos');
        $id       = $_GET['id'];
        $producto = $this->producto->obtener($id);

        if (!$producto) {
            header("Location: index.php?action=productos");
            exit();
        }

        $marcas     = $this->db->query("SELECT * FROM marca ORDER BY marcas_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $talles     = $this->db->query("SELECT * FROM talle ORDER BY talles_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $colores    = $this->db->query("SELECT * FROM color ORDER BY colores_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $categorias = $this->db->query("SELECT * FROM categoria ORDER BY tipodezapatilla ASC")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/productos/edit.php';
    }

    public function update() {
        Auth::verificarModulo('productos');
        $data = [
            'id'        => $_POST['id_producto'],
            'nombre'    => trim($_POST['nombre'] ?? ''),
            'tipo'      => trim($_POST['tipodezapatillas'] ?? ''),
            'precio'    => $_POST['precio'] ?? '',
            'talle'     => $_POST['talle_id_talle'] ?? '',
            'marca'     => $_POST['marca_id_marca'] ?? '',
            'color'     => $_POST['color_id_color'] ?? '',
            'categoria' => $_POST['categoria_id_categoria'] ?? ''
        ];

        $this->producto->actualizar($data);
        header("Location: index.php?action=productos&ok=1");
        exit();
    }

    public function exportExcel() {
        Auth::verificarModulo('productos');
        $productos = $this->db->query("SELECT * FROM producto WHERE activo = 1")->fetchAll();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=productos.xls");

        echo "ID\tNombre\tTipo\tPrecio\n";
        foreach ($productos as $p) {
            echo $p['id_producto'] . "\t" . $p['nombre'] . "\t" . $p['tipodezapatillas'] . "\t" . $p['precio'] . "\n";
        }
        exit();
    }

    public function delete() {
        Auth::verificarModulo('productos');
        $id = $_GET['id'];
        $this->producto->darDeBaja($id);
        header("Location: index.php?action=productos&baja=1");
        exit();
    }

    public function reactivar() {
        Auth::verificarModulo('productos');
        $id = $_GET['id'];
        $this->producto->reactivar($id);
        header("Location: index.php?action=productos&reactivado=1");
        exit();
    }
}
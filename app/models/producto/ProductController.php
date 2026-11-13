<?php
require_once __DIR__ . '/Product.php';

class ProductController {

    private $producto;
    private $db;

    // Carpeta física donde se guardan las imágenes: public/uploads/productos/
    private $dirImagenes;
    // Tamaño máximo de imagen: 2 MB
    private $maxBytes = 2097152;

    public function __construct() {
        $this->db      = (new Database())->connect();
        $this->producto = new Product($this->db);
        $this->dirImagenes = __DIR__ . '/../../../public/uploads/productos/';
    }

    // Sube la imagen. Devuelve ['nombre' => archivo|null, 'error' => código|null]
    private function subirImagen($archivo) {
        // No se eligió ningún archivo
        if (!isset($archivo) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
            return ['nombre' => null, 'error' => null];
        }

        // Archivo demasiado grande
        if ($archivo['error'] === UPLOAD_ERR_INI_SIZE
            || $archivo['error'] === UPLOAD_ERR_FORM_SIZE
            || $archivo['size'] > $this->maxBytes) {
            return ['nombre' => null, 'error' => 'imagen_grande'];
        }

        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            return ['nombre' => null, 'error' => 'imagen_invalida'];
        }

        // Validar que realmente sea una imagen (por contenido, no por extensión)
        $permitidos = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']);

        if (!isset($permitidos[$mime]) || @getimagesize($archivo['tmp_name']) === false) {
            return ['nombre' => null, 'error' => 'imagen_invalida'];
        }

        if (!is_dir($this->dirImagenes)) {
            mkdir($this->dirImagenes, 0755, true);
        }

        // Nombre aleatorio para evitar choques y nombres peligrosos
        $nombre = 'prod_' . bin2hex(random_bytes(8)) . '.' . $permitidos[$mime];

        if (!move_uploaded_file($archivo['tmp_name'], $this->dirImagenes . $nombre)) {
            return ['nombre' => null, 'error' => 'imagen_invalida'];
        }

        return ['nombre' => $nombre, 'error' => null];
    }

    // Borra una imagen del disco (si existe)
    private function eliminarImagen($nombre) {
        if (empty($nombre)) return;
        $ruta = $this->dirImagenes . basename($nombre);
        if (is_file($ruta)) {
            @unlink($ruta);
        }
    }

    public function index() {
        Auth::verificarModulo('productos');

        $filtroMarca = $_GET['marca'] ?? '';
        $filtroTalle = $_GET['talle'] ?? '';

        $marcas = $this->db->query("SELECT * FROM marca ORDER BY marcas_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $talles = $this->db->query("SELECT * FROM talle ORDER BY talles_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);

        // PAGINACIÓN
        $porPagina      = 10;
        $totalProductos = $this->producto->contarActivos($filtroMarca, $filtroTalle);
        $totalPaginas   = max(1, (int)ceil($totalProductos / $porPagina));

        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($pagina < 1) $pagina = 1;
        if ($pagina > $totalPaginas) $pagina = $totalPaginas;

        $offset = ($pagina - 1) * $porPagina;

        $desde = $totalProductos > 0 ? $offset + 1 : 0;
        $hasta = min($offset + $porPagina, $totalProductos);

        $productos          = $this->producto->listar($filtroMarca, $filtroTalle, $porPagina, $offset);
        $productosInactivos = $this->producto->listarInactivos();

        require __DIR__ . '/views/index.php';
    }

    public function create() {
        Auth::verificarModulo('productos');
        $talles     = $this->db->query("SELECT * FROM talle ORDER BY talles_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $marcas     = $this->db->query("SELECT * FROM marca ORDER BY marcas_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $categorias = $this->db->query("SELECT * FROM categoria ORDER BY tipodezapatilla ASC")->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/views/create.php';
    }

    public function store() {
        Auth::verificarModulo('productos');

        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tipo        = trim($_POST['tipodezapatillas'] ?? '');
        $precio      = $_POST['precio'] ?? '';
        $talle       = $_POST['talle_id_talle'] ?? '';
        $marca       = $_POST['marca_id_marca'] ?? '';
        $categoria   = $_POST['categoria_id_categoria'] ?? '';

        // Validación de campos requeridos
        if (empty($nombre) || empty($tipo) || empty($precio) || empty($talle) || empty($marca) || empty($categoria)) {
            header("Location: index.php?action=crear&error=campos_requeridos");
            exit();
        }

        // Validación de formato/rango del precio
        if (!is_numeric($precio) || $precio <= 0) {
            header("Location: index.php?action=crear&error=precio_invalido");
            exit();
        }

        // Validación de duplicados (mismo nombre + talle + marca)
        if ($this->producto->existeDuplicado($nombre, $talle, $marca)) {
            header("Location: index.php?action=crear&error=producto_duplicado");
            exit();
        }

        // Imagen obligatoria al crear
        $img = $this->subirImagen($_FILES['imagen'] ?? null);
        if ($img['error']) {
            header("Location: index.php?action=crear&error=" . $img['error']);
            exit();
        }
        if (empty($img['nombre'])) {
            header("Location: index.php?action=crear&error=imagen_requerida");
            exit();
        }

        $data = [
            'nombre'           => $nombre,
            'descripcion'      => $descripcion,
            'imagen'           => $img['nombre'],
            'tipodezapatillas' => $tipo,
            'precio'           => $precio,
            'talle'            => $talle,
            'marca'            => $marca,
            'categoria'        => $categoria
        ];

        $id_producto = $this->producto->crear($data);

        // Cada producto nuevo genera automáticamente su fila de inventario en 0
        $stmt = $this->db->prepare("
            INSERT INTO inventario (stock_actual, stock_maximo, stock_minimo, ubicacion, producto_id_producto)
            VALUES (0, 0, 0, '', :id_producto)
        ");
        $stmt->execute(['id_producto' => $id_producto]);

        // AUDITORÍA: se registra la creación con los datos nuevos
        Auditoria::registrar('producto', $id_producto, 'crear', null, $data);

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

        $talles     = $this->db->query("SELECT * FROM talle ORDER BY talles_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $marcas     = $this->db->query("SELECT * FROM marca ORDER BY marcas_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);
        $categorias = $this->db->query("SELECT * FROM categoria ORDER BY tipodezapatilla ASC")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/views/edit.php';
    }

    public function update() {
        Auth::verificarModulo('productos');

        $id          = $_POST['id_producto'];
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tipo        = trim($_POST['tipodezapatillas'] ?? '');
        $precio      = $_POST['precio'] ?? '';
        $talle       = $_POST['talle_id_talle'] ?? '';
        $marca       = $_POST['marca_id_marca'] ?? '';
        $categoria   = $_POST['categoria_id_categoria'] ?? '';

        if (empty($nombre) || empty($tipo) || empty($precio) || empty($talle) || empty($marca) || empty($categoria)) {
            header("Location: index.php?action=editar&id={$id}&error=campos_requeridos");
            exit();
        }

        if (!is_numeric($precio) || $precio <= 0) {
            header("Location: index.php?action=editar&id={$id}&error=precio_invalido");
            exit();
        }

        // Validación de duplicados, excluyendo el propio producto
        if ($this->producto->existeDuplicado($nombre, $talle, $marca, $id)) {
            header("Location: index.php?action=editar&id={$id}&error=producto_duplicado");
            exit();
        }

        // AUDITORÍA: se guarda el estado ANTES de aplicar los cambios
        $datosAnteriores = $this->producto->obtener($id);

        // Imagen: si se sube una nueva se reemplaza, si no se conserva la actual
        $img = $this->subirImagen($_FILES['imagen'] ?? null);
        if ($img['error']) {
            header("Location: index.php?action=editar&id={$id}&error=" . $img['error']);
            exit();
        }
        $imagenFinal = $img['nombre'] ?? ($datosAnteriores['imagen'] ?? null);

        $data = [
            'id'          => $id,
            'nombre'      => $nombre,
            'descripcion' => $descripcion,
            'imagen'      => $imagenFinal,
            'tipo'        => $tipo,
            'precio'      => $precio,
            'talle'       => $talle,
            'marca'       => $marca,
            'categoria'   => $categoria
        ];

        $this->producto->actualizar($data);

        // Si se reemplazó la imagen, se borra la anterior del disco
        if (!empty($img['nombre']) && !empty($datosAnteriores['imagen'])) {
            $this->eliminarImagen($datosAnteriores['imagen']);
        }

        // AUDITORÍA: se registra la edición con los datos anteriores y nuevos
        Auditoria::registrar('producto', $id, 'editar', $datosAnteriores, $data);

        header("Location: index.php?action=productos&ok=1");
        exit();
    }

    public function exportExcel() {
        Auth::verificarModulo('productos');
        $productos = $this->db->query("SELECT * FROM producto WHERE activo = 1")->fetchAll();

        require_once __DIR__ . '/../../../vendor/autoload.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Productos');

        $texto = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;
        $numero = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;

        foreach (['ID', 'Nombre', 'Tipo', 'Precio'] as $i => $titulo) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValueExplicit($col . '1', $titulo, $texto);
        }
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $fila = 2;
        foreach ($productos as $p) {
            $sheet->setCellValueExplicit('A' . $fila, (float)$p['id_producto'], $numero);
            $sheet->setCellValueExplicit('B' . $fila, (string)$p['nombre'], $texto);
            $sheet->setCellValueExplicit('C' . $fila, (string)$p['tipodezapatillas'], $texto);
            $sheet->setCellValueExplicit('D' . $fila, (float)$p['precio'], $numero);
            $sheet->getStyle('D' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
            $fila++;
        }

        foreach (['A', 'B', 'C', 'D'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="productos.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    public function delete() {
        Auth::verificarModulo('productos');
        $id = $_GET['id'];

        $datosAnteriores = $this->producto->obtener($id);
        $this->producto->darDeBaja($id);

        // AUDITORÍA: se registra la baja lógica
        Auditoria::registrar('producto', $id, 'baja', $datosAnteriores, ['activo' => 0]);

        header("Location: index.php?action=productos&baja=1");
        exit();
    }

    public function reactivar() {
        Auth::verificarModulo('productos');
        $id = $_GET['id'];

        $datosAnteriores = $this->producto->obtener($id);
        $this->producto->reactivar($id);

        // AUDITORÍA: se registra la reactivación
        Auditoria::registrar('producto', $id, 'reactivar', $datosAnteriores, ['activo' => 1]);

        header("Location: index.php?action=productos&reactivado=1");
        exit();
    }

}
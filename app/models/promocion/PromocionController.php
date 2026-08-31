<?php
require_once __DIR__ . '/promocion.php';

class PromocionController {

    private $promocion;
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->promocion = new Promocion($this->db);
    }

    public function index() {
        Auth::verificarModulo('promociones');
        $promociones = $this->promocion->listar();
        require __DIR__ . '/views/index.php';
    }

    public function create() {
        Auth::verificarModulo('promociones');
        $productos  = $this->db->query("SELECT id_producto, nombre FROM producto WHERE activo = 1 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
        $categorias = $this->db->query("SELECT id_categoria, tipodezapatilla FROM categoria ORDER BY tipodezapatilla ASC")->fetchAll(PDO::FETCH_ASSOC);
        $clientes   = $this->db->query("
            SELECT c.id_cliente, CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo
            FROM cliente c JOIN persona p ON c.persona_idpersona = p.id_persona
            ORDER BY p.nombre ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/views/create.php';
    }

    public function store() {
        Auth::verificarModulo('promociones');

        $nombre         = trim($_POST['nombre'] ?? '');
        $descripcion    = trim($_POST['descripcion'] ?? '');
        $tipo_descuento = $_POST['tipo_descuento'] ?? '';
        $valor          = $_POST['valor'] ?? '';
        $fecha_inicio   = $_POST['fecha_inicio'] ?? '';
        $fecha_fin      = $_POST['fecha_fin'] ?? '';
        $ambito         = $_POST['ambito'] ?? ''; // 'producto' o 'categoria'
        $productos_ids  = $_POST['productos'] ?? [];
        $categoria_id   = $_POST['categoria_id'] ?? '';
        $clientes_ids   = $_POST['clientes'] ?? [];

        // Validaciones de campos requeridos
        if (empty($nombre) || empty($tipo_descuento) || empty($valor) || empty($fecha_inicio) || empty($fecha_fin)) {
            header("Location: index.php?action=promociones_crear&error=campos_requeridos");
            exit();
        }

        // Validación del valor según tipo de descuento
        if ($tipo_descuento === 'porcentaje' && ($valor <= 0 || $valor > 100)) {
            header("Location: index.php?action=promociones_crear&error=porcentaje_invalido");
            exit();
        }
        if ($tipo_descuento === 'monto_fijo' && $valor <= 0) {
            header("Location: index.php?action=promociones_crear&error=monto_invalido");
            exit();
        }

        // Validación de fechas
        if (strtotime($fecha_fin) < strtotime($fecha_inicio)) {
            header("Location: index.php?action=promociones_crear&error=fechas_invalidas");
            exit();
        }

        // Validación de ámbito: tiene que aplicar a algo
        if ($ambito === 'producto' && empty($productos_ids)) {
            header("Location: index.php?action=promociones_crear&error=ambito_requerido");
            exit();
        }
        if ($ambito === 'categoria' && empty($categoria_id)) {
            header("Location: index.php?action=promociones_crear&error=ambito_requerido");
            exit();
        }

        $data = [
            'nombre'         => $nombre,
            'descripcion'    => $descripcion,
            'tipo_descuento' => $tipo_descuento,
            'valor'          => $valor,
            'fecha_inicio'   => $fecha_inicio,
            'fecha_fin'      => $fecha_fin,
        ];

        $id_promocion = $this->promocion->crear($data);

        if ($ambito === 'categoria') {
            $this->promocion->asignarProductosPorCategoria($id_promocion, $categoria_id);
        } else {
            $this->promocion->asignarProductos($id_promocion, $productos_ids);
        }

        $this->promocion->asignarClientes($id_promocion, $clientes_ids);

        header("Location: index.php?action=promociones&ok=1");
        exit();
    }

    public function edit() {
        Auth::verificarModulo('promociones');
        $id = $_GET['id'];
        $promo = $this->promocion->obtener($id);

        if (!$promo) {
            header("Location: index.php?action=promociones");
            exit();
        }

        $productos  = $this->db->query("SELECT id_producto, nombre FROM producto WHERE activo = 1 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
        $categorias = $this->db->query("SELECT id_categoria, tipodezapatilla FROM categoria ORDER BY tipodezapatilla ASC")->fetchAll(PDO::FETCH_ASSOC);
        $clientes   = $this->db->query("
            SELECT c.id_cliente, CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo
            FROM cliente c JOIN persona p ON c.persona_idpersona = p.id_persona
            ORDER BY p.nombre ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $productosAsignados = array_column($this->promocion->obtenerProductosAsignados($id), 'id_producto');
        $clientesAsignados  = array_column($this->promocion->obtenerClientesAsignados($id), 'id_cliente');

        require __DIR__ . '/views/edit.php';
    }

    public function update() {
        Auth::verificarModulo('promociones');

        $id             = $_POST['id_promocion'];
        $nombre         = trim($_POST['nombre'] ?? '');
        $descripcion    = trim($_POST['descripcion'] ?? '');
        $tipo_descuento = $_POST['tipo_descuento'] ?? '';
        $valor          = $_POST['valor'] ?? '';
        $fecha_inicio   = $_POST['fecha_inicio'] ?? '';
        $fecha_fin      = $_POST['fecha_fin'] ?? '';
        $ambito         = $_POST['ambito'] ?? '';
        $productos_ids  = $_POST['productos'] ?? [];
        $categoria_id   = $_POST['categoria_id'] ?? '';
        $clientes_ids   = $_POST['clientes'] ?? [];

        if (empty($nombre) || empty($tipo_descuento) || empty($valor) || empty($fecha_inicio) || empty($fecha_fin)) {
            header("Location: index.php?action=promociones_editar&id={$id}&error=campos_requeridos");
            exit();
        }

        if ($tipo_descuento === 'porcentaje' && ($valor <= 0 || $valor > 100)) {
            header("Location: index.php?action=promociones_editar&id={$id}&error=porcentaje_invalido");
            exit();
        }
        if ($tipo_descuento === 'monto_fijo' && $valor <= 0) {
            header("Location: index.php?action=promociones_editar&id={$id}&error=monto_invalido");
            exit();
        }

        if (strtotime($fecha_fin) < strtotime($fecha_inicio)) {
            header("Location: index.php?action=promociones_editar&id={$id}&error=fechas_invalidas");
            exit();
        }

        $data = [
            'id'             => $id,
            'nombre'         => $nombre,
            'descripcion'    => $descripcion,
            'tipo_descuento' => $tipo_descuento,
            'valor'          => $valor,
            'fecha_inicio'   => $fecha_inicio,
            'fecha_fin'      => $fecha_fin,
        ];

        $this->promocion->actualizar($data);

        if ($ambito === 'categoria' && !empty($categoria_id)) {
            $this->promocion->asignarProductosPorCategoria($id, $categoria_id);
        } else {
            $this->promocion->asignarProductos($id, $productos_ids);
        }

        $this->promocion->asignarClientes($id, $clientes_ids);

        header("Location: index.php?action=promociones&ok=1");
        exit();
    }

    public function delete() {
        Auth::verificarModulo('promociones');
        $this->promocion->darDeBaja($_GET['id']);
        header("Location: index.php?action=promociones&baja=1");
        exit();
    }

    public function reactivar() {
        Auth::verificarModulo('promociones');
        $this->promocion->reactivar($_GET['id']);
        header("Location: index.php?action=promociones&reactivado=1");
        exit();
    }
}
<?php
require_once __DIR__ . '/reporte.php';

class ReporteController {

    private $reporte;

    public function __construct() {
        $db = (new Database())->connect();
        $this->reporte = new Reporte($db);
    }

    // Helper: toma el rango de fechas del GET, o usa "últimos 30 días" por defecto
    private function obtenerRango() {
        $hasta = $_GET['hasta'] ?? date('Y-m-d');
        $desde = $_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'));
        return [$desde, $hasta];
    }

    public function index() {
        Auth::verificarModulo('reportes');
        [$desde, $hasta] = $this->obtenerRango();

        $totales      = $this->reporte->totalesVentas($desde, $hasta);
        $ventas       = $this->reporte->ventasPorPeriodo($desde, $hasta, 'dia');
        $porCategoria = $this->reporte->ventasPorCategoria($desde, $hasta);
        $productos    = $this->reporte->productosMasVendidos($desde, $hasta, 10);
        $stock        = $this->reporte->stockBajo();
        $clientes     = $this->reporte->clientesFrecuentes($desde, $hasta, 10);
        $promociones  = $this->reporte->promocionesAplicadas($desde, $hasta);

        require __DIR__ . '/views/index.php';
    }

    public function ventas() {
        Auth::verificarModulo('reportes');
        [$desde, $hasta] = $this->obtenerRango();
        $agrupacion = $_GET['agrupacion'] ?? 'dia';

        $totales      = $this->reporte->totalesVentas($desde, $hasta);
        $ventas       = $this->reporte->ventasPorPeriodo($desde, $hasta, $agrupacion);
        $porCategoria = $this->reporte->ventasPorCategoria($desde, $hasta);

        require __DIR__ . '/views/ventas.php';
    }

    public function productos() {
        Auth::verificarModulo('reportes');
        [$desde, $hasta] = $this->obtenerRango();
        $productos = $this->reporte->productosMasVendidos($desde, $hasta, 15);
        require __DIR__ . '/views/productos.php';
    }

    public function stock() {
        Auth::verificarModulo('reportes');
        $productos = $this->reporte->stockBajo();
        require __DIR__ . '/views/stock.php';
    }

    public function clientes() {
        Auth::verificarModulo('reportes');
        [$desde, $hasta] = $this->obtenerRango();
        $clientes = $this->reporte->clientesFrecuentes($desde, $hasta, 15);
        $nuevos   = $this->reporte->clientesNuevos($desde, $hasta);
        require __DIR__ . '/views/clientes.php';
    }

    public function promociones() {
        Auth::verificarModulo('reportes');
        [$desde, $hasta] = $this->obtenerRango();
        $promociones = $this->reporte->promocionesAplicadas($desde, $hasta);
        require __DIR__ . '/views/promociones.php';
    }

    // Exporta a Excel el reporte que se esté viendo (mismo patrón que ya usás en Productos)
    public function exportExcel() {
        Auth::verificarModulo('reportes');
        [$desde, $hasta] = $this->obtenerRango();
        $tipo = $_GET['tipo'] ?? 'ventas';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_{$tipo}.xls");

        switch ($tipo) {
            case 'productos':
                echo "Producto\tUnidades vendidas\tTotal facturado\n";
                foreach ($this->reporte->productosMasVendidos($desde, $hasta, 1000) as $p) {
                    echo "{$p['nombre']}\t{$p['unidades_vendidas']}\t{$p['total_facturado']}\n";
                }
                break;

            case 'stock':
                echo "Producto\tCategoría\tStock actual\tStock mínimo\n";
                foreach ($this->reporte->stockBajo() as $p) {
                    echo "{$p['nombre']}\t{$p['categoria']}\t{$p['stock_actual']}\t{$p['stock_minimo']}\n";
                }
                break;

            case 'clientes':
                echo "Cliente\tCantidad de compras\tTotal comprado\n";
                foreach ($this->reporte->clientesFrecuentes($desde, $hasta, 1000) as $c) {
                    echo "{$c['nombre_cliente']}\t{$c['cantidad_compras']}\t{$c['total_comprado']}\n";
                }
                break;

            case 'promociones':
                echo "Promoción\tVeces aplicada\tDescuento total\n";
                foreach ($this->reporte->promocionesAplicadas($desde, $hasta) as $p) {
                    echo "{$p['nombre']}\t{$p['veces_aplicada']}\t{$p['descuento_total']}\n";
                }
                break;

            default: // ventas
                echo "Período\tCantidad de ventas\tTotal\n";
                foreach ($this->reporte->ventasPorPeriodo($desde, $hasta, $_GET['agrupacion'] ?? 'dia') as $v) {
                    echo "{$v['periodo']}\t{$v['cantidad_ventas']}\t{$v['total']}\n";
                }
        }
        exit();
    }

    // Recibe las imágenes de los gráficos (capturadas en el navegador) y arma un Excel real con PhpSpreadsheet
    public function exportGraficosExcel() {
        Auth::verificarModulo('reportes');

        $tipo = $_POST['tipo'] ?? 'reporte';
        $graficos = json_decode($_POST['graficos'] ?? '[]', true);

        if (empty($graficos)) {
            die('No se recibieron gráficos para exportar.');
        }

        require_once __DIR__ . '/../../../vendor/autoload.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $tmpFiles = [];

        foreach ($graficos as $i => $g) {
            $nombreHoja = preg_replace('/[^A-Za-z0-9 ]/', '', $g['titulo']);
            $nombreHoja = mb_substr($nombreHoja, 0, 28) ?: ('Grafico' . ($i + 1));

            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($nombreHoja);

            $sheet->setCellValue('A1', $g['titulo']);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

            $partesImagen = explode(',', $g['imagen']);
            if (count($partesImagen) < 2) continue;

            $binario = base64_decode($partesImagen[1]);
            $tmpPath = sys_get_temp_dir() . '/grafico_' . uniqid() . '.png';
            file_put_contents($tmpPath, $binario);
            $tmpFiles[] = $tmpPath;

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName($g['titulo']);
            $drawing->setPath($tmpPath);
            $drawing->setCoordinates('A3');
            $drawing->setHeight(350);
            $drawing->setWorksheet($sheet);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=graficos_{$tipo}.xlsx");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');

        foreach ($tmpFiles as $f) {
            @unlink($f);
        }

        exit();
    }
}
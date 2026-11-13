<?php
require_once __DIR__ . '/reporte.php';

class ReporteController {

    private $reporte;

    public function __construct() {
        $db = (new Database())->connect();
        $this->reporte = new Reporte($db);
    }

    // Helper: toma el rango de fechas del GET, o usa "últimos 30 días" por defecto
    // (también si vienen vacías, que es lo que pasa al exportar sin haber filtrado)
    private function obtenerRango() {
        $hasta = !empty($_GET['hasta']) ? $_GET['hasta'] : date('Y-m-d');
        $desde = !empty($_GET['desde']) ? $_GET['desde'] : date('Y-m-d', strtotime('-30 days'));
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

    // Exporta a Excel (.xlsx real) el reporte que se esté viendo
    public function exportExcel() {
        Auth::verificarModulo('reportes');
        [$desde, $hasta] = $this->obtenerRango();

        $tiposValidos = ['ventas', 'productos', 'stock', 'clientes', 'promociones'];
        $tipo = $_GET['tipo'] ?? 'ventas';
        if (!in_array($tipo, $tiposValidos, true)) {
            $tipo = 'ventas';
        }

        // Cada reporte define: nombre de hoja, encabezados, tipo de cada columna y filas
        // Tipos de columna: 't' = texto, 'n' = número entero, 'm' = dinero
        switch ($tipo) {
            case 'productos':
                $hoja = 'Productos';
                $encabezados = ['Producto', 'Unidades vendidas', 'Total facturado'];
                $tipos = ['t', 'n', 'm'];
                $filas = [];
                foreach ($this->reporte->productosMasVendidos($desde, $hasta, 1000) as $p) {
                    $filas[] = [$p['nombre'], $p['unidades_vendidas'], $p['total_facturado']];
                }
                break;

            case 'stock':
                $hoja = 'Stock';
                $encabezados = ['Producto', 'Categoría', 'Stock actual', 'Stock mínimo'];
                $tipos = ['t', 't', 'n', 'n'];
                $filas = [];
                foreach ($this->reporte->stockBajo() as $p) {
                    $filas[] = [$p['nombre'], $p['categoria'], $p['stock_actual'], $p['stock_minimo']];
                }
                break;

            case 'clientes':
                $hoja = 'Clientes';
                $encabezados = ['Cliente', 'Cantidad de compras', 'Total comprado'];
                $tipos = ['t', 'n', 'm'];
                $filas = [];
                foreach ($this->reporte->clientesFrecuentes($desde, $hasta, 1000) as $c) {
                    $filas[] = [$c['nombre_cliente'], $c['cantidad_compras'], $c['total_comprado']];
                }
                break;

            case 'promociones':
                $hoja = 'Promociones';
                $encabezados = ['Promoción', 'Veces aplicada', 'Descuento total'];
                $tipos = ['t', 'n', 'm'];
                $filas = [];
                foreach ($this->reporte->promocionesAplicadas($desde, $hasta) as $p) {
                    $filas[] = [$p['nombre'], $p['veces_aplicada'], $p['descuento_total']];
                }
                break;

            default: // ventas
                $hoja = 'Ventas';
                $encabezados = ['Período', 'Cantidad de ventas', 'Total'];
                $tipos = ['t', 'n', 'm'];
                $filas = [];
                foreach ($this->reporte->ventasPorPeriodo($desde, $hasta, $_GET['agrupacion'] ?? 'dia') as $v) {
                    $filas[] = [$v['periodo'], $v['cantidad_ventas'], $v['total']];
                }
        }

        require_once __DIR__ . '/../../../vendor/autoload.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($hoja);

        $texto = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;
        $numero = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;

        // Encabezados
        foreach ($encabezados as $i => $titulo) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValueExplicit($col . '1', $titulo, $texto);
        }
        $ultimaCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($encabezados));
        $sheet->getStyle("A1:{$ultimaCol}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$ultimaCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE9ECEF');

        // Filas de datos
        $fila = 2;
        foreach ($filas as $datos) {
            foreach ($datos as $i => $valor) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
                $celda = $col . $fila;

                if ($tipos[$i] === 't') {
                    $sheet->setCellValueExplicit($celda, (string)$valor, $texto);
                } else {
                    $sheet->setCellValueExplicit($celda, (float)$valor, $numero);
                    $sheet->getStyle($celda)->getNumberFormat()
                        ->setFormatCode($tipos[$i] === 'm' ? '#,##0.00' : '0');
                }
            }
            $fila++;
        }

        // Ancho de columnas automático
        foreach (range(1, count($encabezados)) as $i) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Limpia cualquier salida previa (espacios, BOM, warnings) que corrompería el archivo
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"reporte_{$tipo}.xlsx\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
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
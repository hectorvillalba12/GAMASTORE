<?php
require_once __DIR__ . '/../../ayudantes/Auditoria.php';

class AuditoriaController {

    // LISTAR historial de auditoría 
    public function index() {
        Auth::verificarModulo('auditoria');

        $tabla = $_GET['tabla'] ?? null;

        $movimientos = Auditoria::listar($tabla);
        $tablas      = Auditoria::listarTablas();

        require __DIR__ . '/views/index.php';
    }
}
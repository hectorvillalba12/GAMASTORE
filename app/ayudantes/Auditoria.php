<?php
class Auditoria {

    // Registra una acción en la bitácora de auditoría
    public static function registrar($tabla, $registro_id, $accion, $datos_anteriores = null, $datos_nuevos = null) {
        $db = (new Database())->connect();
        $usuario_id = $_SESSION['usuario']['id_usuario'] ?? null;

        $sql = "INSERT INTO auditoria
                (tabla, registro_id, accion, datos_anteriores, datos_nuevos, usuario_id_usuario, fecha)
                VALUES
                (:tabla, :registro_id, :accion, :datos_anteriores, :datos_nuevos, :usuario_id, NOW())";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            'tabla'             => $tabla,
            'registro_id'       => $registro_id,
            'accion'            => $accion,
            'datos_anteriores'  => $datos_anteriores ? json_encode($datos_anteriores, JSON_UNESCAPED_UNICODE) : null,
            'datos_nuevos'      => $datos_nuevos ? json_encode($datos_nuevos, JSON_UNESCAPED_UNICODE) : null,
            'usuario_id'        => $usuario_id
        ]);
    }

    // Lista el historial de auditoría, opcionalmente filtrado por tabla
    public static function listar($tabla = null) {
        $db  = (new Database())->connect();
        $sql = "SELECT a.*, u.email AS usuario_email
                FROM auditoria a
                LEFT JOIN usuario u ON a.usuario_id_usuario = u.id_usuario";

        if ($tabla) {
            $sql .= " WHERE a.tabla = :tabla";
        }
        $sql .= " ORDER BY a.fecha DESC";

        $stmt = $db->prepare($sql);
        $tabla ? $stmt->execute(['tabla' => $tabla]) : $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
        // Devuelve las tablas que tienen registros de auditoría (para armar el filtro)
    public static function listarTablas() {
        $db  = (new Database())->connect();
        $sql = "SELECT DISTINCT tabla FROM auditoria ORDER BY tabla ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
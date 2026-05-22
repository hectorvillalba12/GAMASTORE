<?php
class Auth {

    public static function verificar() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?action=login&msg=sesion_requerida");
            exit();
        }

        if ($_SESSION['usuario']['rol'] !== 'admin') {
            header("Location: index.php?action=login&msg=acceso_denegado");
            exit();
        }
    }

    public static function verificarModulo($modulo) {
        self::verificar();

        $perfil_id = $_SESSION['usuario']['perfil_id'] ?? null;

        if (!$perfil_id) {
            header("Location: index.php?action=dashboard&msg=sin_perfil");
            exit();
        }

        $db    = (new Database())->connect();
        $sql   = "SELECT COUNT(*) FROM perfil_has_modulo WHERE perfil_id = :perfil_id AND modulo = :modulo";
        $stmt  = $db->prepare($sql);
        $stmt->execute(['perfil_id' => $perfil_id, 'modulo' => $modulo]);
        $tieneAcceso = $stmt->fetchColumn() > 0;

        if (!$tieneAcceso) {
            header("Location: index.php?action=dashboard&msg=acceso_denegado");
            exit();
        }
    }
}
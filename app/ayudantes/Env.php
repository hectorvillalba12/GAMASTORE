<?php

class Env {

    private static $loaded = false;

    public static function load(string $rutaEnv): void {
        if (self::$loaded) {
            return;
        }

        if (!file_exists($rutaEnv)) {
            // No rompemos la app si falta el .env, pero avisamos en el log del servidor.
            error_log("[Env] Archivo .env no encontrado en: $rutaEnv");
            self::$loaded = true;
            return;
        }

        $lineas = file($rutaEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lineas as $linea) {
            $linea = trim($linea);

            // Ignorar comentarios y lineas vacias
            if ($linea === '' || str_starts_with($linea, '#')) {
                continue;
            }

            if (!str_contains($linea, '=')) {
                continue;
            }

            [$clave, $valor] = explode('=', $linea, 2);
            $clave = trim($clave);
            $valor = trim($valor);

            // Quitar comillas simples o dobles que envuelvan el valor
            if (strlen($valor) >= 2) {
                $primero = $valor[0];
                $ultimo  = $valor[strlen($valor) - 1];
                if (($primero === '"' && $ultimo === '"') || ($primero === "'" && $ultimo === "'")) {
                    $valor = substr($valor, 1, -1);
                }
            }

            // Cargar en las 3 fuentes para que getenv(), $_ENV y $_SERVER funcionen
            putenv("$clave=$valor");
            $_ENV[$clave]    = $valor;
            $_SERVER[$clave] = $valor;
        }

        self::$loaded = true;
    }

    public static function get(string $clave, $default = null) {
        $valor = getenv($clave);
        if ($valor === false) {
            return $default;
        }
        return $valor;
    }
}
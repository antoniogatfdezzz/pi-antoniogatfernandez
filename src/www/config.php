<?php


// Modo depuración: TRUE mostrará errores controlados.
define('APP_DEPURACION', false);

// Parámetros de conexión a la base de datos MySQL (ajusta según entorno local).
define('BD_HOST', '127.0.0.1');
define('BD_NOMBRE', 'intra_vb');
define('BD_USUARIO', 'root');
define('BD_PASSWORD', '');

// Zona horaria por defecto.
date_default_timezone_set('Europe/Madrid');

// Codificación interna.
mb_internal_encoding('UTF-8');

// Ruta base del proyecto (calculada dinámicamente para flexibilidad en despliegue).
define('RUTA_BASE', rtrim(str_replace('\\', '/', dirname(__FILE__)), '/'));

// Mensajes genéricos reutilizables.
define('MSJ_ERROR_GENERAL', 'Ha ocurrido un error inesperado. Inténtalo de nuevo más tarde.');

// Iniciar sesión si no está iniciada (MVC simple sin framework externo).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Manejo básico de errores controlados.
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (!APP_DEPURACION) {
        error_log("[ERROR] $errstr en $errfile:$errline");
        return true; // Evita mostrar detalles al usuario.
    }
    return false; // Permite el manejo estándar en modo depuración.
});

set_exception_handler(function ($ex) {
    error_log('[EXCEPCIÓN] ' . $ex->getMessage());
    if (APP_DEPURACION) {
        echo '<pre style="padding:1rem;background:#f8d7da;border:1px solid #f5c2c7;color:#842029;">Excepción capturada: ' . htmlspecialchars($ex->getMessage()) . '</pre>';
    } else {
        echo MSJ_ERROR_GENERAL;
    }
});

?>

<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modelos/modelo_usuario.php';

/**
 * ControladorAutenticacion: gestiona inicio y cierre de sesión.
 */
class ControladorAutenticacion {
    private ModeloUsuario $modeloUsuario;

    public function __construct() {
        $this->modeloUsuario = new ModeloUsuario();
    }

    /**
     * Procesa intento de inicio de sesión.
     */
    public function iniciarSesion(): void {
        $usuario = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($usuario === '' || $password === '') {
            $this->redirigirConMensaje('Credenciales incompletas.', false);
            return;
        }
        $registro = $this->modeloUsuario->obtenerPorUsuario($usuario);
        if ($registro && password_verify($password, $registro['password'])) {
            // Persistir datos mínimos en sesión.
            $_SESSION['usuario_id'] = $registro['id'];
            $_SESSION['usuario_nombre'] = $registro['usuario'];
            $_SESSION['tipo_usuario'] = $registro['tipo_usuario'];

            error_log('[INFO] Inicio de sesión correcto para usuario ID ' . $registro['id'] . ' (tipo=' . $registro['tipo_usuario'] . ')');

            // Redirigir siempre al dashboard correspondiente según rol.
            header('Location: index.php?ruta=dashboard');
            exit;
        }
        $this->redirigirConMensaje('Usuario o contraseña incorrectos.', false);
    }

    /** Cierra la sesión actual. */
    public function cerrarSesion(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: index.php');
        exit;
    }

    /** Devuelve si hay sesión iniciada. */
    public static function autenticado(): bool {
        return isset($_SESSION['usuario_id']);
    }

    private function redirigirConMensaje(string $mensaje, bool $exito): void {
        $_SESSION['flash'] = [
            'mensaje' => $mensaje,
            'exito' => $exito
        ];
        header('Location: index.php');
        exit;
    }
}
?>

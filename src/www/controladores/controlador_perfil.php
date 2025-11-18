<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/controlador_autenticacion.php';
require_once __DIR__ . '/../modelos/modelo_usuario.php';

/**
 * ControladorPerfil: muestra datos básicos del usuario autenticado.
 */
class ControladorPerfil {
    private ModeloUsuario $modeloUsuario;

    public function __construct() {
        if (!ControladorAutenticacion::autenticado()) {
            header('Location: index.php');
            exit;
        }
        $this->modeloUsuario = new ModeloUsuario();
    }

    public function ver(): void {
        $usuarioId = (int)$_SESSION['usuario_id'];
        // Reutilizamos método obtenerPorUsuario buscando por nombre de usuario almacenado en sesión.
        $usuario = $this->modeloUsuario->obtenerPorUsuario($_SESSION['usuario_nombre']);
        $this->render('perfil', ['usuario' => $usuario]);
    }

    private function render(string $vista, array $datos = []): void {
        extract($datos);
        $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
    require __DIR__ . '/../vistas/' . $vista . '.php';
    }
}
?>
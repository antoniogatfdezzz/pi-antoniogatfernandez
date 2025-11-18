<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/controlador_autenticacion.php';
require_once __DIR__ . '/../modelos/modelo_usuario.php';

/**
 * ControladorUsuario: creación de nuevos usuarios (solo administradores).
 */
class ControladorUsuario {
    private ModeloUsuario $modeloUsuario;

    public function __construct() {
        if (!ControladorAutenticacion::autenticado() || ($_SESSION['tipo_usuario'] ?? '') !== 'administrador') {
            header('Location: index.php');
            exit;
        }
        $this->modeloUsuario = new ModeloUsuario();
    }

    /** Muestra formulario de creación */
    public function formularioCrear(): void {
        $this->render('usuario_crear');
    }

    /** Procesa creación */
    public function crear(): void {
        $usuario = trim($_POST['usuario'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $tipo = $_POST['tipo_usuario'] ?? 'administrador';

        if ($usuario === '' || $email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash'] = ['mensaje' => 'Datos inválidos del usuario.', 'exito' => false];
            header('Location: index.php?ruta=usuarios&accion=form');
            exit;
        }

        $id = $this->modeloUsuario->crear($usuario, $email, $password, $tipo);
        $_SESSION['flash'] = ['mensaje' => 'Usuario creado ID ' . $id, 'exito' => true];
        header('Location: index.php?ruta=usuarios&accion=form');
        exit;
    }

    /** Listado de usuarios activos */
    public function listado(): void {
        $usuarios = $this->modeloUsuario->listarTodos();
        $this->render('usuarios_listado', ['usuarios' => $usuarios]);
    }

    /** Eliminar (lógico) un usuario */
    public function eliminar(): void {
        $id = (int)($_POST['id'] ?? 0);
        $token = $_POST['token'] ?? '';
        if (!$this->validarToken($token) || $id <= 0) {
            $_SESSION['flash'] = ['mensaje' => 'Solicitud inválida.', 'exito' => false];
            header('Location: index.php?ruta=usuarios&accion=listar');
            exit;
        }
        // Evitar que el admin se elimine a sí mismo por accidente.
        if ($id === (int)($_SESSION['usuario_id'] ?? 0)) {
            $_SESSION['flash'] = ['mensaje' => 'No puedes eliminar tu propio usuario.', 'exito' => false];
            header('Location: index.php?ruta=usuarios&accion=listar');
            exit;
        }
        $ok = $this->modeloUsuario->eliminarPorId($id);
        $_SESSION['flash'] = ['mensaje' => $ok ? 'Usuario eliminado.' : 'No fue posible eliminar el usuario.', 'exito' => (bool)$ok];
        header('Location: index.php?ruta=usuarios&accion=listar');
        exit;
    }

    private function validarToken(string $token): bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    private function render(string $vista, array $datos = []): void {
        extract($datos);
        $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
        // Generar token CSRF si no existe
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
        }
        $token = $_SESSION['csrf_token'];
    require __DIR__ . '/../vistas/' . $vista . '.php';
    }
}
?>
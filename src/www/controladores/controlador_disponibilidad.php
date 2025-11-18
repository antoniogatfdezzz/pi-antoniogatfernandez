<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/controlador_autenticacion.php';
require_once __DIR__ . '/../modelos/modelo_disponibilidad.php';
require_once __DIR__ . '/../modelos/modelo_usuario.php';
require_once __DIR__ . '/../modelos/modelo_arbitro.php';

/**
 * ControladorDisponibilidad: gestión de disponibilidad del árbitro autenticado.
 */
class ControladorDisponibilidad {
    private ModeloDisponibilidad $modeloDisponibilidad;
    private ModeloUsuario $modeloUsuario;
    private ModeloArbitro $modeloArbitro;

    public function __construct() {
        if (!ControladorAutenticacion::autenticado() || ($_SESSION['tipo_usuario'] ?? '') !== 'arbitro') {
            header('Location: index.php');
            exit;
        }
    $this->modeloDisponibilidad = new ModeloDisponibilidad();
    $this->modeloUsuario = new ModeloUsuario();
    $this->modeloArbitro = new ModeloArbitro();
    }

    /** Muestra la vista con disponibilidad del árbitro */
    public function ver(): void {
        $arbitroId = $this->obtenerArbitroId();
        $disponibilidad = $this->modeloDisponibilidad->obtenerPorArbitro($arbitroId);
        $this->render('disponibilidad', ['disponibilidad' => $disponibilidad]);
    }

    /** Procesa guardado de disponibilidad (POST) */
    public function guardar(): void {
        $arbitroId = $this->obtenerArbitroId();
        $fecha = $_POST['fecha'] ?? '';
        $manana = isset($_POST['manana']) ? 1 : 0;
        $tarde = isset($_POST['tarde']) ? 1 : 0;
        $obs = trim($_POST['observaciones'] ?? '');

        if (!$this->validarFecha($fecha)) {
            $_SESSION['flash'] = ['mensaje' => 'Fecha inválida.', 'exito' => false];
            header('Location: index.php?ruta=disponibilidad');
            exit;
        }
        $this->modeloDisponibilidad->guardar($arbitroId, $fecha, $manana, $tarde, $manana ? $obs : null, $tarde ? $obs : null);
        $_SESSION['flash'] = ['mensaje' => 'Disponibilidad guardada.', 'exito' => true];
        header('Location: index.php?ruta=disponibilidad');
        exit;
    }

    private function obtenerArbitroId(): int {
        $usuarioId = (int)($_SESSION['usuario_id']);
        $arbitro = $this->modeloArbitro->obtenerPorUsuario($usuarioId);
        if (!$arbitro) {
            $_SESSION['flash'] = ['mensaje' => 'Perfil de árbitro no encontrado. Contacte con administración.', 'exito' => false];
            header('Location: index.php');
            exit;
        }
        return (int)$arbitro['id'];
    }

    private function validarFecha(string $fecha): bool {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) === 1;
    }

    private function render(string $vista, array $datos = []): void {
        extract($datos);
        $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
    require __DIR__ . '/../vistas/' . $vista . '.php';
    }
}
?>
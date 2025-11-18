<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/controlador_autenticacion.php';
require_once __DIR__ . '/../modelos/modelo_disponibilidad.php';
require_once __DIR__ . '/../modelos/modelo_arbitro.php';

/**
 * ControladorDisponibilidadAdmin: permite a administradores consultar la disponibilidad de árbitros.
 */
class ControladorDisponibilidadAdmin {
    private ModeloDisponibilidad $modeloDisponibilidad;
    private ModeloArbitro $modeloArbitro;

    public function __construct() {
        if (!ControladorAutenticacion::autenticado() || ($_SESSION['tipo_usuario'] ?? '') !== 'administrador') {
            header('Location: index.php');
            exit;
        }
        $this->modeloDisponibilidad = new ModeloDisponibilidad();
        $this->modeloArbitro = new ModeloArbitro();
    }

    /** Muestra el formulario de consulta */
    public function formulario(): void {
        $hoy = date('Y-m-d');
        $this->render('disponibilidad_arbitros', ['fecha' => $hoy, 'resultados' => []]);
    }

    /** Ejecuta consulta por fecha */
    public function consultar(): void {
        $fecha = $_POST['fecha'] ?? ($_GET['fecha'] ?? '');
        if (!$this->validarFecha($fecha)) {
            $_SESSION['flash'] = ['mensaje' => 'Fecha inválida.', 'exito' => false];
            header('Location: index.php?ruta=disponibilidad_arbitros');
            exit;
        }
        $resultados = $this->modeloDisponibilidad->listarPorFecha($fecha);
        $this->render('disponibilidad_arbitros', ['fecha' => $fecha, 'resultados' => $resultados]);
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
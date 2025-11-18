<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modelos/modelo_partido.php';
require_once __DIR__ . '/../modelos/modelo_arbitro.php';
require_once __DIR__ . '/controlador_autenticacion.php';

/**
 * ControladorPartido: orquestación de CRUD de partidos.
 */
class ControladorPartido {
    private ModeloPartido $modeloPartido;

    public function __construct() {
        $this->modeloPartido = new ModeloPartido();
        if (!ControladorAutenticacion::autenticado()) {
            header('Location: index.php');
            exit;
        }
    }

    /** Lista todos los partidos y carga la vista correspondiente. */
    public function listado(): void {
        $partidos = $this->modeloPartido->obtenerTodos();
        $modeloArbitro = new ModeloArbitro();
        $arbitros = $modeloArbitro->listarTodos();
        $this->render('partidos_listado', ['partidos' => $partidos, 'arbitros' => $arbitros]);
    }

    /** Procesa creación de partido. */
    public function crear(): void {
        $datos = $this->sanearDatos($_POST);
        if ($this->validarDatos($datos)) {
            $this->modeloPartido->crear($datos);
            $_SESSION['flash'] = ['mensaje' => 'Partido creado correctamente.', 'exito' => true];
            header('Location: index.php?ruta=partidos');
            exit;
        }
        $_SESSION['flash'] = ['mensaje' => 'Datos inválidos del partido.', 'exito' => false];
        header('Location: index.php?ruta=partidos');
        exit;
    }

    /** Muestra formulario de edición. */
    public function editar(): void {
        $id = (int)($_GET['id'] ?? 0);
        $partido = $this->modeloPartido->obtenerPorId($id);
        if (!$partido) {
            $_SESSION['flash'] = ['mensaje' => 'Partido no encontrado.', 'exito' => false];
            header('Location: index.php?ruta=partidos');
            exit;
        }
        $modeloArbitro = new ModeloArbitro();
        $arbitros = $modeloArbitro->listarTodos();
        $this->render('partido_formulario', ['partido' => $partido, 'arbitros' => $arbitros]);
    }

    /** Actualiza partido existente. */
    public function actualizar(): void {
        $id = (int)($_POST['id'] ?? 0);
        $datos = $this->sanearDatos($_POST);
        if ($id > 0 && $this->validarDatos($datos)) {
            $this->modeloPartido->actualizar($id, $datos);
            $_SESSION['flash'] = ['mensaje' => 'Partido actualizado correctamente.', 'exito' => true];
        } else {
            $_SESSION['flash'] = ['mensaje' => 'Datos inválidos para actualización.', 'exito' => false];
        }
        header('Location: index.php?ruta=partidos');
        exit;
    }

    /** Elimina partido. */
    public function eliminar(): void {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->modeloPartido->eliminar($id);
            $_SESSION['flash'] = ['mensaje' => 'Partido eliminado.', 'exito' => true];
        } else {
            $_SESSION['flash'] = ['mensaje' => 'ID inválido.', 'exito' => false];
        }
        header('Location: index.php?ruta=partidos');
        exit;
    }

    /** Renderiza una vista HTML. */
    private function render(string $vista, array $datos = []): void {
        extract($datos); // Variables disponibles en la vista.
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
    require __DIR__ . '/../vistas/' . $vista . '.php';
    }

    /** Limpieza básica de datos recibidos. */
    private function sanearDatos(array $d): array {
        $limpios = [];
        foreach ($d as $k => $v) {
            $limpios[$k] = is_string($v) ? trim(htmlspecialchars($v, ENT_QUOTES, 'UTF-8')) : $v;
        }
        // Unificar fecha + hora si vienen separados.
        if (isset($d['fecha_dia'], $d['fecha_hora'])) {
            $limpios['fecha'] = $d['fecha_dia'] . ' ' . $d['fecha_hora'] . ':00';
        }
        // Normalizar IDs de árbitros (vacío => null, numérico => int)
        foreach (['arbitro_principal_id','arbitro_segundo_id','anotador_id','categoria_id'] as $campoId) {
            if (array_key_exists($campoId, $limpios)) {
                $valor = $limpios[$campoId];
                if ($valor === '' || $valor === null) { $limpios[$campoId] = null; }
                else { $limpios[$campoId] = (int)$valor; }
            }
        }
        return $limpios;
    }

    /** Validaciones mínimas (se pueden ampliar). */
    private function validarDatos(array $datos): bool {
        $requeridos = ['equipo_local', 'equipo_visitante', 'categoria_id', 'fecha', 'pabellon_nombre'];
        foreach ($requeridos as $campo) {
            if (empty($datos[$campo])) return false;
        }
        if (strcasecmp($datos['equipo_local'], $datos['equipo_visitante']) === 0) return false;
        return true;
    }
}
?>

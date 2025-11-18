<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controladores/controlador_autenticacion.php';
require_once __DIR__ . '/controladores/controlador_partido.php';
require_once __DIR__ . '/controladores/controlador_usuario.php';
require_once __DIR__ . '/controladores/controlador_disponibilidad.php';
require_once __DIR__ . '/controladores/controlador_perfil.php';

$ruta = $_GET['ruta'] ?? 'login';
$accion = $_POST['accion'] ?? ($_GET['accion'] ?? null);

switch ($ruta) {
    case 'login':
        if ($accion === 'iniciar') {
            (new ControladorAutenticacion())->iniciarSesion();
        } else {
            $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
            require __DIR__ . '/vistas/login.php';
        }
        break;
    case 'dashboard':
        // Mostrar dashboard según tipo de usuario.
        if (!ControladorAutenticacion::autenticado()) { header('Location: index.php'); exit; }
        $tipo = $_SESSION['tipo_usuario'] ?? '';
        $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
        if ($tipo === 'administrador') {
            require __DIR__ . '/vistas/dashboard_admin.php';
        } elseif ($tipo === 'arbitro') {
            require __DIR__ . '/vistas/dashboard_arbitro.php';
        } else {
            require __DIR__ . '/vistas/perfil.php';
        }
        break;
    case 'logout':
        (new ControladorAutenticacion())->cerrarSesion();
        break;
    case 'partidos':
        // CRUD partidos (solo admins)
        if (($_SESSION['tipo_usuario'] ?? '') !== 'administrador') {
            header('Location: index.php'); exit;
        }
        $controlador = new ControladorPartido();
        match ($accion) {
            'crear' => $controlador->crear(),
            'actualizar' => $controlador->actualizar(),
            'eliminar' => $controlador->eliminar(),
            'editar' => $controlador->editar(),
            default => $controlador->listado()
        };
        break;
    case 'usuarios':
        // Gestión de usuarios (solo admins)
        if (($_SESSION['tipo_usuario'] ?? '') !== 'administrador') { header('Location: index.php'); exit; }
        $ctrlUsuario = new ControladorUsuario();
        match ($accion) {
            'crear' => $ctrlUsuario->crear(),
            'form' => $ctrlUsuario->formularioCrear(),
            'eliminar' => $ctrlUsuario->eliminar(),
            default => $ctrlUsuario->listado()
        };
        break;
    case 'disponibilidad_arbitros':
        // Consulta de disponibilidad por administradores
        if (($_SESSION['tipo_usuario'] ?? '') !== 'administrador') { header('Location: index.php'); exit; }
        require_once __DIR__ . '/controladores/controlador_disponibilidad_admin.php';
        $ctrl = new ControladorDisponibilidadAdmin();
        if ($accion === 'consultar') { $ctrl->consultar(); } else { $ctrl->formulario(); }
        break;
    case 'disponibilidad':
        $ctrlDisp = new ControladorDisponibilidad();
        if ($accion === 'guardar') { $ctrlDisp->guardar(); } else { $ctrlDisp->ver(); }
        break;
    case 'perfil':
        (new ControladorPerfil())->ver();
        break;
    case 'mis_partidos':
        // Listado de partidos asignados al árbitro autenticado
        if (!ControladorAutenticacion::autenticado() || ($_SESSION['tipo_usuario'] ?? '') !== 'arbitro') { header('Location: index.php'); exit; }
        $modelo = new ModeloPartido();
        $usuarioId = (int)$_SESSION['usuario_id'];
        // Obtener id de árbitro real desde tabla arbitros
        try {
            $pdoTmp = new PDO('mysql:host=' . BD_HOST . ';dbname=' . BD_NOMBRE . ';charset=utf8mb4', BD_USUARIO, BD_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $stmtA = $pdoTmp->prepare('SELECT id FROM arbitros WHERE usuario_id = :uid LIMIT 1');
            $stmtA->execute([':uid' => $usuarioId]);
            $arbitroId = (int)($stmtA->fetchColumn() ?: 0);
        } catch (Throwable $e) { $arbitroId = 0; }
        $partidos = array_filter($modelo->obtenerTodos(), function($p) use ($arbitroId){
            return in_array($arbitroId, [(int)$p['arbitro_principal_id'], (int)$p['arbitro_segundo_id'], (int)$p['anotador_id']], true);
        });
        foreach ($partidos as &$p) {
            if ((int)$p['arbitro_principal_id'] === $arbitroId) { $p['mi_rol'] = 'Árbitro Principal'; }
            elseif ((int)$p['arbitro_segundo_id'] === $arbitroId) { $p['mi_rol'] = 'Árbitro Segundo'; }
            elseif ((int)$p['anotador_id'] === $arbitroId) { $p['mi_rol'] = 'Anotador'; }
            else { $p['mi_rol'] = '-'; }
        }
    $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
    require __DIR__ . '/vistas/mis_partidos.php';
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo 'Ruta no encontrada.';
}
?>

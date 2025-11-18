# Split-1 — Manual del Programador

Última actualización: 18-11-2025

## Tabla de contenido

- [Arquitectura y tecnologías](#arquitectura-y-tecnologías)
- [Estructura de carpetas](#estructura-de-carpetas)
- [Enrutamiento y flujo de ejecución](#enrutamiento-y-flujo-de-ejecución)
- [Modelos y acceso a datos (PDO)](#modelos-y-acceso-a-datos-pdo)
- [Controladores y validación](#controladores-y-validación)
- [Vistas, CSS y JS](#vistas-css-y-js)
- [Autenticación, sesiones y roles](#autenticación-sesiones-y-roles)
- [Estándares y buenas prácticas](#estándares-y-buenas-prácticas)
- [Cómo añadir nuevas funcionalidades](#cómo-añadir-nuevas-funcionalidades)
- [Depuración y registro de errores](#depuración-y-registro-de-errores)
- [Despliegue y configuración](#despliegue-y-configuración)
- [Roadmap y mejoras futuras](#roadmap-y-mejoras-futuras)

## Arquitectura y tecnologías

- Patrón: MVC (Modelo–Vista–Controlador)
- Lenguaje: PHP 8+ 
- Base de datos: MySQL 8 (UTF8MB4)
- Conexión: PDO con `ATTR_ERRMODE = EXCEPTION`, `ATTR_EMULATE_PREPARES = false`
- Sesiones: nativas de PHP (control básico en `config.php`)
- Front-end: HTML básico + CSS/JS propios

## Estructura de carpetas

```
split-1/
  src/
    sql/                 # SQL de estructura y datos
    www/
      config.php         # Configuración de app y BD
      index.php          # Enrutamiento por query param `ruta`
      controladores/     # Controladores PHP (lógica de orquestación)
      modelos/           # Modelos PHP (acceso a datos)
      vistas/            # Vistas PHP (render de HTML)
      css/               # Estilos (sin inline styles)
      js/                # Scripts (sin inline scripts)
```

Archivos relevantes:
- `config.php`: constantes de configuración, `APP_DEPURACION`, zona horaria, arranque de sesión y manejadores de errores/excepciones.
- `index.php`: switch por `ruta` y `accion`, carga controladores, aplica protección por rol.
- `modelos/modelo_base.php`: factoría de conexión PDO centralizada.
- `modelos/modelo_partido.php`: CRUD de `partidos` + joins con `categorias` y `arbitros`.
- `controladores/controlador_partido.php`: orquestación CRUD, limpieza/validación y render.
- `controladores/controlador_autenticacion.php`: login/logout, persistencia mínima en sesión.

## Enrutamiento y flujo de ejecución

El enrutamiento es intencionalmente simple, mediante `index.php`:

```php
$ruta = $_GET['ruta'] ?? 'login';
$accion = $_POST['accion'] ?? ($_GET['accion'] ?? null);

switch ($ruta) {
  case 'login':
    // Render vista o procesa `iniciar`
    break;
  case 'dashboard':
    // Selecciona dashboard por rol
    break;
  case 'partidos':
    // CRUD admins: crear|actualizar|eliminar|editar|listado
    break;
  // ... otras rutas
}
```

Convenciones:
- `ruta` define el recurso principal.
- `accion` especifica la operación CRUD o evento (ej. `crear`, `actualizar`).
- Control de acceso por rol/estado de sesión antes de ejecutar acciones sensibles.

## Modelos y acceso a datos (PDO)

`ModeloBase` centraliza la conexión:

```php
class ModeloBase {
  protected PDO $conexion;
  protected function crearConexion(): PDO {
    $dsn = 'mysql:host=' . BD_HOST . ';dbname=' . BD_NOMBRE . ';charset=utf8mb4';
    return new PDO($dsn, BD_USUARIO, BD_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]);
  }
}
```

Buenas prácticas aplicadas:
- Consultas preparadas en operaciones con datos de usuario.
- `FETCH_ASSOC` por defecto.
- `utf8mb4` para compatibilidad con caracteres.

Tablas clave (ver `src/sql/bbdd.sql`): `usuarios`, `arbitros`, `categorias`, `partidos`, `disponibilidad_arbitros`.

## Controladores y validación

Ejemplo: `ControladorPartido`.
- Filtra entrada en `sanearDatos()` (trim + `htmlspecialchars`).
- Normaliza fechas (`fecha_dia` + `fecha_hora` → `fecha`).
- Valida campos requeridos y reglas de negocio mínimas (local != visitante).
- Usa mensajes flash via `$_SESSION['flash']` y redirecciones post/redirect/get.

```php
match ($accion) {
  'crear' => $controlador->crear(),
  'actualizar' => $controlador->actualizar(),
  'eliminar' => $controlador->eliminar(),
  'editar' => $controlador->editar(),
  default => $controlador->listado()
};
```

## Vistas, CSS y JS

- Las vistas residen en `src/www/vistas/` y deben contener sólo lógica de presentación.
- No incluir estilos/script en línea: ubicar CSS en `src/www/css/` y JS en `src/www/js/`.
- Usar variables preparadas por el controlador (vía `extract($datos)` en `render()`).

## Autenticación, sesiones y roles

- Inicio de sesión en `ControladorAutenticacion::iniciarSesion()` con `password_verify()`.
- Datos de sesión mínimos: `usuario_id`, `usuario_nombre`, `tipo_usuario`.
- Roles previstos: `administrador`, `arbitro`.
- Protección de rutas: ejemplo en `index.php` para `partidos` y `usuarios`.

## Estándares y buenas prácticas

- Nomenclatura en español (clases, métodos, variables) según requisitos.
- Clean Code: funciones cortas, responsabilidades claras, comentarios precisos.
- Manejo de errores centralizado en `config.php`.
- Evitar *echo* de errores en producción (`APP_DEPURACION = false`).
- Evitar duplicación de lógica entre controladores/modelos.

### Reglas rápidas

- Siempre usar consultas preparadas para datos externos.
- Validar entrada en controladores y, si aplica, revalidar en modelos.
- No acceder a `$_POST`/`$_GET` directamente en vistas.
- Reutilizar constantes/mensajes comunes de `config.php`.

## Cómo añadir nuevas funcionalidades

1. Base de datos: añade tablas/campos en `src/sql/bbdd.sql` (y semillas opcionales en `datos.sql`).
2. Modelo: crea `src/www/modelos/modelo_nueva_entidad.php` extendiendo `ModeloBase`.
3. Controlador: `src/www/controladores/controlador_nueva_entidad.php` con métodos CRUD.
4. Vistas: en `src/www/vistas/` crea `nueva_entidad_listado.php`, `nueva_entidad_formulario.php`, etc.
5. Enrutamiento: añade casos en `index.php` bajo `switch ($ruta)`.
6. Seguridad: protege rutas según rol y valida/sanea toda entrada.

Plantilla mínima de modelo:

```php
class ModeloEjemplo extends ModeloBase {
  public function crear(array $d): int {
    $stmt = $this->conexion->prepare('INSERT INTO ejemplo (nombre) VALUES (:n)');
    $stmt->execute([':n' => $d['nombre']]);
    return (int)$this->conexion->lastInsertId();
  }
}
```

Plantilla mínima de controlador:

```php
class ControladorEjemplo {
  public function listado(): void { /* obtener y require vista */ }
  public function crear(): void { /* validar, insertar, flash, redirect */ }
  public function editar(): void { /* cargar registro y vista */ }
  public function actualizar(): void { /* validar, actualizar */ }
  public function eliminar(): void { /* borrar y redirect */ }
}
```

## Depuración y registro de errores

- `APP_DEPURACION = true` → muestra detalles controlados de excepciones.
- `set_error_handler` y `set_exception_handler` centralizados en `config.php`.
- Usar `error_log()` para trazas puntuales (ya presente en login).

## Despliegue y configuración

- Desarrollo local con XAMPP en `http://localhost/split-1/src/www/`.
- Variables de BD en `config.php` (evitar *commitear* credenciales reales en otros entornos).
- Producción: desactivar depuración, restringir acceso a herramientas de administración, backups de BD.



// Antonio Gat Fernández | agatf02@educarex.es
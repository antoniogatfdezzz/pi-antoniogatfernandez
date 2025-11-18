# Arquitectura Técnica — Split-1

Última actualización: 18/11/2025

Este documento describe la arquitectura técnica del módulo Split-1, centrado en autenticación y gestión de partidos bajo un patrón MVC (Modelo–Vista–Controlador) con PHP 8, Apache (XAMPP) y MySQL.

## Objetivos arquitectónicos

- Separación de responsabilidades clara (MVC) para facilitar mantenimiento y escalabilidad.
- Sencillez operativa: sin frameworks externos; uso de PHP nativo con PDO.
- Seguridad razonable por defecto: control de acceso por rol, saneado mínimo de entradas, reglas de Apache y manejo de errores controlado.
- Rendimiento básico: cacheo de estáticos vía cabeceras y consultas SQL indexadas.
- Trazabilidad: puntos de log centralizados (error_log) y mensajes flash en interfaz.

## Visión general del sistema

```mermaid
flowchart LR
  A[Navegador] -- HTTP GET/POST --> B[Apache + mod_rewrite]
  B -- Regla Front Controller --> C[index.php]
  C -- Determina ruta/acción --> D[Controlador]
  D -- invoca --> E[Modelo]
  E -- PDO/MySQL --> F[(Base de Datos MySQL)]
  D -- pasa datos --> G[Vista (PHP/HTML)]
  G -- HTML/CSS/JS --> A
  B -. estáticos .-> H[/assets (css/js/img)/]
```

- Enrutamiento: `.htaccess` redirige todas las rutas no físicas a `index.php`.
- Controladores: orquestan lógica de aplicación y seguridad por rol.
- Modelos: encapsulan acceso a datos con PDO y SQL parametrizado.
- Vistas: archivos PHP delgados que renderizan HTML sin lógica de negocio.
- Estáticos: servidos directamente por Apache desde `css/`, `js/`, `assets/` con cache-control.

## Tecnologías y versiones

- PHP ≥ 8.0 (uso de `match`, propiedades tipadas, `Throwable`).
- Servidor web: Apache (XAMPP en Windows) con `mod_rewrite` y `mod_headers`.
- Base de datos: MySQL 8.x (compatible MariaDB) vía PDO.
- Cliente: HTML5 + CSS + JavaScript.

## Estructura de carpetas

```
src/
  sql/              # SQL de creación y datos (bbdd.sql, datos.sql, pruebas.sql)
  www/
    .htaccess       # Reescritura de URLs y cache estáticos
    config.php      # Configuración global, sesión, errores
    index.php       # Front Controller (routing simple por querystring)
    controladores/  # Controladores MVC (autenticación, partidos, usuarios,...)
    modelos/        # Modelos de datos (Base, Partido, Usuario, Árbitro,...)
    vistas/         # Vistas PHP (HTML renderizado); sin JS/CSS inline
    css/            # Hojas de estilo globales
    js/             # Scripts de interacción ligera
    assets/imagenes # Recursos estáticos
```

## Configuración y bootstrap

Archivo `src/www/config.php`:
- Parámetros de BD (`BD_HOST`, `BD_NOMBRE`, `BD_USUARIO`, `BD_PASSWORD`).
- `APP_DEPURACION` para alternar verbosidad de errores.
- Zona horaria y codificación interna UTF-8.
- Inicio de sesión (`session_start()` si procede).
- `set_error_handler` y `set_exception_handler` para capturar y registrar errores.

Archivo `src/www/.htaccess`:
- `RewriteEngine On` y `RewriteRule` al Front Controller.
- Bloqueo de acceso directo a `modelos/`, `controladores/` y archivos sensibles.
- Forzado de `AddDefaultCharset UTF-8`.
- Cache-Control para `css|js|png|jpg|gif`.

## Enrutamiento y rutas principales

El Front Controller (`index.php`) determina `ruta` y `accion` (querystring o POST):

- `login`:
  - GET: muestra vista de login.
  - POST (`accion=iniciar`): `ControladorAutenticacion::iniciarSesion()`.
- `dashboard`:
  - Determina vista según `tipo_usuario` (`administrador`, `arbitro`).
- `logout`: cierra sesión.
- `partidos` (admin):
  - `crear`, `actualizar`, `eliminar`, `editar` → `ControladorPartido`.
  - default → listado de partidos.
- `usuarios` (admin):
  - `crear`, `form`, `eliminar` → `ControladorUsuario`.
  - default → listado de usuarios.
- `disponibilidad_arbitros` (admin): formulario/consulta de disponibilidad.
- `disponibilidad` (árbitro): ver/guardar disponibilidad propia.
- `perfil`: vista de perfil.
- `mis_partidos` (árbitro): lista de partidos asignados con rol contextual.

Control de acceso: comprobaciones por rol antes de entrar a acciones sensibles (p.ej., `partidos`, `usuarios`).

## Controladores y responsabilidades

- `controlador_autenticacion.php`
  - Login/Logout, verificación `autenticado()` y rol de usuario.
- `controlador_partido.php`
  - CRUD de partidos, saneado y validación mínima de datos, render de vistas relacionadas.
- `controlador_usuario.php`
  - Alta/listado/eliminación de usuarios (solo admin).
- `controlador_disponibilidad.php` y `controlador_disponibilidad_admin.php`
  - Gestión y consulta de disponibilidad de árbitros.
- `controlador_perfil.php`
  - Visualización/edición de perfil del usuario autenticado.

## Modelos y entidades de datos

Modelo base `modelo_base.php`: inicializa `$this->conexion` (PDO) y utilidades comunes.

Entidades principales (ver `src/sql/bbdd.sql`):
- `usuarios` (id, tipo_usuario, usuario, email, password, activo). Unique `email`.
- `arbitros` (id, usuario_id→usuarios.id, nombre, apellidos, ciudad, licencia).
- `categorias` (id, nombre). Unique `nombre`.
- `partidos`
  - Local/visitante, `categoria_id` (FK), `fecha`, `pabellon_nombre`.
  - Árbitros asignados: `arbitro_principal_id`, `arbitro_segundo_id`, `anotador_id` (FK a `arbitros`, `ON DELETE SET NULL`).
  - Índices por categoría; joins enriquecen nombres completos.
- `disponibilidad_arbitros` (arbitro_id, fecha, mañana/tarde, observaciones) con `UNIQUE (arbitro_id, fecha)` e índice por fecha.

## Vistas y recursos estáticos

- Vistas PHP bajo `vistas/` (p.ej., `dashboard_admin.php`, `dashboard_arbitro.php`, `partidos_listado.php`, `partido_formulario.php`, `login.php`).
- Estilos en `css/estilos.css` y scripts en `js/app.js` (sin inline en vistas).
- Imágenes bajo `assets/imagenes/`.

## Seguridad

- Control de acceso por rol en rutas sensibles (admin vs árbitro).
- Saneado de entradas en controladores antes de persistir.
- Consultas parametrizadas (prevención de SQLi) vía PDO.
- `.htaccess` bloquea acceso directo a `modelos/`, `controladores/` y archivos de lógica.
- Sesión iniciada de forma centralizada; mensajes flash no persistentes.
- Charset forzado a UTF-8; `htmlspecialchars` en renderizado de campos.

Recomendaciones adicionales (futuras):
- Hash robusto de contraseñas con `password_hash()`/`password_verify()` (si no se usa ya internamente en el controlador de autenticación).
- CSRF tokens en formularios de cambios (`crear/actualizar/eliminar`).
- Cabeceras `SameSite`/`Secure` para cookies en despliegues HTTPS.

## Manejo de errores y logging

- `config.php` define `set_error_handler` y `set_exception_handler`:
  - En producción (`APP_DEPURACION=false`): log en `error_log`, mensaje genérico al usuario.
  - En desarrollo: salida controlada (debug) del mensaje capturado.


## Flujos clave

1) Autenticación
- Usuario accede a `/index.php?ruta=login` → formulario.
- POST `accion=iniciar` → `ControladorAutenticacion` valida credenciales y establece `$_SESSION['usuario_id']` y `$_SESSION['tipo_usuario']`.
- Redirección a `dashboard` según rol.

2) CRUD de partidos (admin)
- Listado: `index.php?ruta=partidos` → `ControladorPartido::listado()` → `ModeloPartido::obtenerTodos()` → vista `partidos_listado`.
- Crear: formulario POST `accion=crear` → saneado + validación → `ModeloPartido::crear()` → flash y redirect.
- Editar/Actualizar: `accion=editar` renderiza formulario; POST `accion=actualizar` actualiza registro.
- Eliminar: POST `accion=eliminar` elimina y redirige.

3) Mis partidos (árbitro)
- Ruta `mis_partidos` filtra partidos por el árbitro autenticado y etiqueta el rol (`Árbitro Principal`, `Árbitro Segundo`, `Anotador`).

## Decisiones arquitectónicas (resumen)

- MVC sin framework: menor dependencia y curva de aprendizaje; conviene reforzar con estándares internos de código.
- Front Controller único via `.htaccess`: simplifica rutas; adecúa para futuros controladores.
- PDO + SQL explícito: control y transparencia de consultas; se mantienen índices y FKs para integridad.
- Estructura de carpetas estricta: separación de vistas, controladores, modelos y estáticos.

## Calidad y mantenimiento

- Estándares de Clean Code: nombres en español, cohesión por clase/controlador, funciones cortas y documentadas.
- Consola del navegador y PHP sin errores ni warnings en ejecución normal; usar `APP_DEPURACION` en desarrollo.
- Dependencias: sin Composer; mantener PHP actualizado (≥ 8.0) y MySQL con parches de seguridad actuales.



// Antonio Gat Fernández | agatf02@educarex.es
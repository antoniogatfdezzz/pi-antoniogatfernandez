# Product Backlog — Split 1

Última actualización: 18/11/2025

## Alcance del Split 1

- Inicio de sesión y cierre de sesión.
- Control de acceso básico por rol (Administrador, Árbitro) para proteger la gestión de partidos.
- Gestión de Partidos (CRUD): crear, listar, editar y eliminar.
- Estructura MVC operativa, con modelos, vistas y controladores.
- Base de datos MySQL con scripts: `bbdd.sql`, `datos.sql`, `pruebas.sql`.
- Estáticos organizados: CSS y JS.
- Manejo de errores, logs mínimos y seguridad esencial (sesiones seguras y consultas preparadas).

## Leyenda

- Prioridad: Alta (A), Media (M), Baja (B)
- Estado: Por hacer, En curso, Hecho
- Estimación: Puntos de historia (PH)

---

## Épicas

- EP-01 Autenticación y control de acceso
- EP-02 Gestión de Partidos
- EP-03 Base de Datos y Modelo de Datos
- EP-04 Arquitectura, Seguridad y Entorno
- EP-05 Calidad y Documentación

---

## Backlog (Resumen)

| ID | Épica | Historia de Usuario | Prioridad | PH | Dependencias | Estado |
|----|-------|----------------------|-----------|----|--------------|--------|
| US-01 | EP-01 | Inicio de sesión de usuario | A | 5 | EP-04 | Hecho |
| US-02 | EP-01 | Cierre de sesión | A | 2 | US-01 | Hecho |
| US-03 | EP-01 | Control de acceso por rol | A | 3 | US-01 | Hecho |
| US-10 | EP-02 | Crear partido | A | 8 | EP-03, EP-04 | Hecho |
| US-11 | EP-02 | Listar partidos con filtros básicos | A | 5 | EP-03 | Hecho |
| US-12 | EP-02 | Editar partido | A | 5 | US-10 | Hecho |
| US-13 | EP-02 | Eliminar partido | A | 3 | US-10 | Hecho |
| US-14 | EP-02 | Ver detalle de partido (opcional) | M | 3 | US-11 | Hecho |
| US-20 | EP-03 | Definir esquema MySQL (bbdd.sql) | A | 5 | — | Hecho |
| US-21 | EP-03 | Cargar datos iniciales (datos.sql) | M | 3 | US-20 | Hecho |
| US-22 | EP-03 | Datos de prueba (pruebas.sql) | M | 3 | US-20 | Hecho |
| US-30 | EP-04 | Estructura MVC base y rutas | A | 5 | — | Hecho |
| US-31 | EP-04 | CSS/JS  | M | 3 | US-30 | Hecho |
| US-32 | EP-04 | Manejo de errores y logs mínimos | M | 3 | US-30 | Hecho |

---

## Historias de Usuario y Criterios de Aceptación

### US-01 — Inicio de sesión de usuario (EP-01)
Como usuario registrado, quiero iniciar sesión con mi correo y contraseña para acceder a la aplicación según mi rol.

Criterios de aceptación (Gherkin):
- Dado que estoy en la pantalla de login, cuando ingreso un correo y contraseña válidos, entonces accedo al dashboard correspondiente a mi rol.
- Dado que ingreso credenciales inválidas, cuando intento iniciar sesión, entonces veo un mensaje claro de error sin revelar detalles sensibles.
- Dado que cierro el navegador, cuando vuelvo a abrirlo, entonces mi sesión solo persiste si se configuró explícitamente “recordarme” (si aplica en el diseño).

Notas:
- Sesiones seguras, cookies con HttpOnly/SameSite=Lax.
- Consultas preparadas para validar credenciales.

Estimación: 5 PH — Prioridad: Alta — Estado: Hecho — Dependencias: EP-04.

---

### US-02 — Cierre de sesión (EP-01)
Como usuario autenticado, quiero cerrar sesión para finalizar mi acceso a la aplicación.

Criterios de aceptación:
- Dado que estoy autenticado, cuando hago clic en “Cerrar sesión”, entonces se destruye la sesión y soy redirigido a la pantalla de login.
- Dado que cierro sesión, cuando intento volver con el botón atrás, entonces no puedo acceder a páginas protegidas.

Estimación: 2 PH — Prioridad: Alta — Estado: Hecho — Dependencias: US-01.

---

### US-03 — Control de acceso por rol (EP-01)
Como sistema, debo restringir el acceso a rutas y acciones según rol (Administrador, Árbitro).

Criterios de aceptación:
- Dado que soy Árbitro, cuando intento acceder a gestión de partidos (crear/editar/eliminar), entonces el sistema lo impide o limita según permisos definidos.
- Dado que soy Administrador, cuando accedo a gestión de partidos, entonces puedo realizar todas las acciones del CRUD.

Estimación: 3 PH — Prioridad: Alta — Estado: Hecho — Dependencias: US-01.

---

### US-10 — Crear partido (EP-02)
Como Administrador, quiero crear un partido registrando fecha, hora, equipos, sede y observaciones para gestionar la competición.

Criterios de aceptación:
- Dado que estoy autenticado como Administrador, cuando completo el formulario con datos válidos, entonces el partido queda guardado en la base de datos y veo confirmación.
- Dado que algunos campos obligatorios están vacíos o inválidos, cuando envío el formulario, entonces veo mensajes de validación claros al usuario.
- Dado que los datos son persistidos, cuando consulto la lista de partidos, entonces el partido recién creado aparece correctamente.

Estimación: 8 PH — Prioridad: Alta — Estado: Hecho — Dependencias: EP-03, EP-04.

---

### US-11 — Listar partidos con filtros básicos (EP-02)
Como usuario autorizado, quiero ver un listado paginado de partidos con filtros por fecha, equipo y estado, para facilitar la gestión.

Criterios de aceptación:
- Dado que existen partidos, cuando abro la vista de listado, entonces veo una tabla paginada ordenada por fecha descendente.
- Dado que aplico filtros, cuando los envío, entonces la tabla se actualiza mostrando solo los registros filtrados.
- Dado que hay muchos registros, cuando avanzo de página, entonces la navegación es ágil (sin errores ni recargas innecesarias).

Estimación: 5 PH — Prioridad: Alta — Estado: Hecho — Dependencias: EP-03.

---

### US-12 — Editar partido (EP-02)
Como Administrador, quiero editar los datos de un partido existente para corregir o actualizar información.

Criterios de aceptación:
- Dado que selecciono un partido, cuando abro la edición, entonces el formulario muestra los campos precargados.
- Dado que guardo cambios válidos, cuando confirmo, entonces se persisten y la lista refleja la actualización.
- Dado que ingreso datos inválidos, cuando guardo, entonces recibo mensajes de validación.

Estimación: 5 PH — Prioridad: Alta — Estado: Hecho — Dependencias: US-10.

---

### US-13 — Eliminar partido (EP-02)
Como Administrador, quiero eliminar un partido para mantener limpia la base de datos.

Criterios de aceptación:
- Dado que estoy en el listado, cuando hago clic en eliminar y confirmo, entonces el registro se elimina y la interfaz se actualiza.
- Dado que cancelo la confirmación, cuando hago clic en eliminar, entonces no se elimina el registro.

Estimación: 3 PH — Prioridad: Alta — Estado: Hecho — Dependencias: US-10.

---

### US-14 — Ver detalle de partido (opcional) (EP-02)
Como usuario autorizado, quiero ver el detalle completo del partido en una vista dedicada.

Criterios de aceptación:
- Dado que accedo al detalle, cuando el partido existe, entonces veo todos los campos relevantes en una sola vista.

Estimación: 3 PH — Prioridad: Media — Estado: Hecho — Dependencias: US-11.

---

### US-20 — Definir esquema MySQL (bbdd.sql) (EP-03)
Como equipo de desarrollo, necesitamos un esquema de base de datos en MySQL para soportar las entidades de usuarios y partidos.

Criterios de aceptación:
- Dado que diseño el modelo, cuando ejecuto `bbdd.sql`, entonces se crean tablas con claves primarias/foráneas, índices esenciales y tipos correctos.
- Dado que la aplicación use el esquema, cuando opero el CRUD, entonces no hay errores de integridad referencial ni de tipos.

Estimación: 5 PH — Prioridad: Alta — Estado: Hecho — Dependencias: —.

---

### US-21 — Cargar datos iniciales (datos.sql) (EP-03)
Cargar datos mínimos (p.ej., usuarios Administrador/Árbitro, equipos de ejemplo) para permitir validaciones y demostración.

Criterios de aceptación:
- Dado que ejecuto `datos.sql`, cuando reviso las tablas, entonces existen registros semilla coherentes y reutilizables.

Estimación: 3 PH — Prioridad: Media — Estado: Hecho — Dependencias: US-20.

---

### US-22 — Datos de prueba (pruebas.sql) (EP-03)
Poblar la base con múltiples partidos de prueba para verificar rendimiento básico, filtrado y paginación.

Criterios de aceptación:
- Dado que ejecuto `pruebas.sql`, cuando consulto el listado, entonces puedo validar filtros y paginación con un volumen razonable.

Estimación: 3 PH — Prioridad: Media — Estado: Hecho — Dependencias: US-20.

---

### US-30 — Estructura MVC base y rutas (EP-04)
Como equipo de desarrollo, quiero disponer de la estructura MVC (modelos, vistas, controladores), `index.php`, `config.php` y `.htaccess` para enrutar y cargar la app.

Criterios de aceptación:
- Dado que abro la app, cuando navego por rutas principales, entonces se despachan controladores y vistas correctos sin errores.
- Dado que cargo estáticos, cuando reviso la consola, entonces no hay 404 ni errores de carga.

Estimación: 5 PH — Prioridad: Alta — Estado: Hecho — Dependencias: —.

---

### US-31 — CSS/JS sin inlines y estructura de assets (EP-04)
Como equipo de desarrollo, quiero todos los estilos en `src/www/css` y scripts en `src/www/js` sin código en línea en vistas.

Criterios de aceptación:
- Dado que reviso las vistas, cuando busco `<style>` o `<script>`, entonces no existen bloques in-line.
- Dado que cargo la app, cuando inspecciono, entonces los archivos CSS/JS correctos están presentes y versionados si aplica.

Estimación: 3 PH — Prioridad: Media — Estado: Hecho — Dependencias: US-30.

---

### US-32 — Manejo de errores y logs mínimos (EP-04)
Como equipo, quiero capturar errores controlados y tener logs mínimos para diagnóstico sin exponer información sensible.

Criterios de aceptación:
- Dado que ocurren errores de validación, cuando los manejo, entonces muestro mensajes amigables y registro detalles técnicos en logs.
- Dado que hay excepciones, cuando se lanzan, entonces las capturo en puntos centrales y retorno respuestas coherentes.

Estimación: 3 PH — Prioridad: Media — Estado: Hecho — Dependencias: US-30.

---



// Antonio Gat Fernández | agatf02@educarex.es
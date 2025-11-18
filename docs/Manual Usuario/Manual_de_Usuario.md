# Split-1 — Manual de Usuario

Última actualización: 18-11-2025

## Acceso a la aplicación

- URL local (XAMPP): `http://localhost/splits-intra/split-1/src/www/`
- Introduce tu usuario y contraseña.
- Si no recuerdas tu contraseña, contacta con el Administrador.

Usuarios de ejemplo (si se cargaron los datos iniciales):
- Administrador: `admin` (contraseña `123456`)
- Árbitros: `arbitro1`, `arbitro2`, `arbitro3` (contraseña `123456`)

## Roles y menús principales

### Administrador
- Dashboard administrativo
- Gestión de Partidos (crear, listar, editar, eliminar)
- Gestión de Usuarios (listado y eliminar)
- Consulta de Disponibilidad de Árbitros (por fecha)

### Árbitro
- Dashboard de árbitro
- Mis Partidos (listado de asignaciones)
- Mi Disponibilidad (ver/guardar)

El sistema muestra mensajes informativos ("flash") en la parte superior tras realizar acciones.

## Iniciar y cerrar sesión

1. Abre la URL de acceso.
2. Escribe tu usuario y contraseña.
3. Pulsa Iniciar sesión.
4. Para salir, usa la opción Cerrar sesión (logout) del menú.

## Gestión de partidos (Administrador)

### Listado de partidos
- Accede a: Menú → Partidos.
- Verás una tabla con: fecha, equipos, categoría, pabellón y asignaciones de árbitros.

### Crear un partido
1. En la vista de Partidos, pulsa "Nuevo" (o accede al formulario de creación).
2. Completa los campos:
   - Equipo local
   - Equipo visitante
   - Categoría
   - Fecha (día y hora)
   - Pabellón
   - Árbitro principal (opcional)
   - Árbitro segundo (opcional)
   - Anotador (opcional)
3. Guarda. Si faltan datos obligatorios o hay errores (por ejemplo, equipos iguales), se mostrará un aviso.

### Editar un partido
1. En el listado, pulsa "Editar" en la fila del partido.
2. Modifica los datos necesarios.
3. Guarda para aplicar los cambios.

### Eliminar un partido
1. En el listado, pulsa "Eliminar" en la fila del partido.
2. Confirma la eliminación. La acción es irreversible.

## Mis partidos (Árbitro)

- Accede a: Menú → Mis partidos.
- Se muestran tus asignaciones. En cada partido podrás ver tu rol (Árbitro Principal, Árbitro Segundo, Anotador).

## Disponibilidad (Árbitro)

- Accede a: Menú → Disponibilidad.
- Marca las franjas disponibles (mañana/tarde) y añade observaciones si procede.
- Guarda los cambios. Si una fecha ya existe para ti, el sistema impedirá duplicados.



// Antonio Gat Fernández | agatf02@educarex.es
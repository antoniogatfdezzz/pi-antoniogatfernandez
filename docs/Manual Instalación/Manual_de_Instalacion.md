# Split-1 — Manual de Instalación

Última actualización: 18-11-2025

## Requisitos del sistema

- Sistema operativo: Windows 10/11
- Servidor web y PHP: XAMPP 8.x (PHP 8.0 o superior). Recomendado PHP 8.2+
- Base de datos: MySQL 8.x (incluido en XAMPP)
- Extensiones PHP activas:
  - pdo_mysql
  - mbstring
- Navegador web moderno (Edge, Chrome, Firefox)

## Estructura del proyecto (extracto)

```
split-1/
  src/
    sql/
      bbdd.sql          # Estructura BBDD
      datos.sql         # Datos iniciales (usuarios, categorías, árbitros)
      pruebas.sql       # Datos de prueba (partidos, disponibilidad)
    www/
      config.php        # Configuración de app y BD
      index.php         # Punto de entrada / enrutador simple MVC
      controladores/    # Controladores (autenticación, partidos, etc.)
      modelos/          # Modelos (PDO + consultas)
      vistas/           # Vistas (HTML/PHP sin lógica de negocio)
      css/              # Estilos globales
      js/               # Scripts globales
```

Ruta base sugerida en XAMPP:
- Carpeta: `C:\\xampp\\htdocs\\splits-intra\\split-1\\`
- URL local: `http://localhost/splits-intra/split-1/src/www/`

## Paso 1 — Instalar/arrancar XAMPP

1. Instala XAMPP 8.x desde el sitio oficial.
2. Abre el Panel de Control de XAMPP y pulsa:
   - Start en Apache
   - Start en MySQL
3. Verifica que no haya conflictos de puertos (Apache en 80/443, MySQL en 3306).

## Paso 2 — Copiar el código fuente

1. Copia la carpeta del proyecto en:
   - `C:\\xampp\\htdocs\\splits-intra\\split-1\\`
2. Comprueba que existan los archivos indicados en la estructura.

## Paso 3 — Crear y poblar la base de datos

1. Abre phpMyAdmin: `http://localhost/phpmyadmin/`.
2. Crea la base de datos (si no existe): `intra_vb` con cotejamiento `utf8mb4_unicode_ci`.
3. Importa, en este orden, los ficheros SQL desde `split-1/src/sql/`:
   - `bbdd.sql` (estructura)
   - `datos.sql` (datos mínimos, usuarios y catálogos)
   - `pruebas.sql` (datos de prueba: partidos y disponibilidades)

Alternativa por línea de comandos (opcional) desde PowerShell (ajusta usuario/contraseña si procede):

```powershell
# Importa estructura
& "C:\\xampp\\mysql\\bin\\mysql.exe" -u root -h 127.0.0.1 -e "source C:/xampp/htdocs/splits-intra/split-1/src/sql/bbdd.sql"
# Importa datos iniciales
& "C:\\xampp\\mysql\\bin\\mysql.exe" -u root -h 127.0.0.1 intra_vb -e "source C:/xampp/htdocs/splits-intra/split-1/src/sql/datos.sql"
# Importa datos de prueba
& "C:\\xampp\\mysql\\bin\\mysql.exe" -u root -h 127.0.0.1 intra_vb -e "source C:/xampp/htdocs/splits-intra/split-1/src/sql/pruebas.sql"
```

## Paso 4 — Configurar la aplicación

Edita `split-1/src/www/config.php` y ajusta, si es necesario, los parámetros:

- `BD_HOST` (por defecto `127.0.0.1`)
- `BD_NOMBRE` (por defecto `intra_vb`)
- `BD_USUARIO` (por defecto `root` en XAMPP)
- `BD_PASSWORD` (por defecto vacío en XAMPP)
- `APP_DEPURACION`: `true` en desarrollo, `false` en producción

El proyecto usa PDO para conectar a MySQL y requiere la extensión `pdo_mysql` habilitada.

## Paso 5 — Acceder a la aplicación

1. En tu navegador, abre: `http://localhost/splits-intra/split-1/src/www/`
2. Verás la pantalla de inicio de sesión.
3. Usuarios de ejemplo (tras importar `datos.sql`):
   - Administrador: usuario `admin`, contraseña `admin`
   - Árbitros: `arbitro1`, `arbitro2`, `arbitro3` (contraseña `admin`)

Si las credenciales de ejemplo no funcionan en tu entorno, actualiza la contraseña de un usuario con un hash generado por `password_hash()` desde PHP, o crea un nuevo usuario administrador desde la tabla `usuarios` (campo `password` con hash `bcrypt`).

## Paso 6 — Verificación rápida

- Iniciar sesión como administrador → Acceso a Dashboard
- Navegar a Partidos → Listado con datos de prueba
- Crear/Editar/Eliminar partido → Confirmación por mensajes "flash"

## Configuración adicional (opcional)

- .htaccess y mod_rewrite: no es obligatorio para la navegación actual basada en `index.php?ruta=...`. Si deseas URLs limpias, habilita `mod_rewrite` y crea reglas adecuadas.
- Zona horaria: se define en `config.php` (`Europe/Madrid`). Ajusta si tu servidor está en otra zona.

## Seguridad y producción

- Cambia las contraseñas de los usuarios de ejemplo.
- Ajusta `APP_DEPURACION` a `false` en producción.
- Restringe el acceso a phpMyAdmin desde redes no confiables.
- Realiza backups periódicos de la base de datos `intra_vb`.

## Problemas comunes y soluciones

- Apache no arranca: libera el puerto 80/443 o reconfigura Apache.
- MySQL no arranca: verifica el puerto 3306 o archivos de datos corruptos.
- Error de conexión a BD: revisa `BD_USUARIO`, `BD_PASSWORD`, `BD_NOMBRE` en `config.php`.
- Página en blanco o errores: activa `APP_DEPURACION = true` en desarrollo y revisa el `error_log` de PHP.
- Falta `pdo_mysql`: habilita la extensión en `php.ini` de XAMPP.

## Desinstalación

1. Borra la carpeta `C:\\xampp\\htdocs\\splits-intra\\split-1\\`.
2. Elimina la base de datos `intra_vb` desde phpMyAdmin.

---

Soporte: consulta el Manual del Programador para detalles técnicos y resolución avanzada de incidencias.



// Antonio Gat Fernández | agatf02@educarex.es
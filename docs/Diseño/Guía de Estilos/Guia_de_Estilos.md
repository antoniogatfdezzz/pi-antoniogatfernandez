# Guía de Estilos (split-1)

Última actualización: 18-11-2025

## Principios de diseño

- Consistencia: mismos patrones de colores, tipografías y espaciamiento en todo el sistema.
- Claridad: jerarquía visual con buen contraste y tipografías legibles.
- Accesibilidad: orientado a WCAG AA. Enlaces y controles con foco visible, etiquetas de formularios, y textos alternativos.
- Escalabilidad: clases reutilizables, variantes claras y sin dependencias implícitas.

## Tokens de diseño

### Colores base (variables CSS)
Definidas en `:root` de `estilos.css` para navegación y acentos (tema verde):

- `--color-base`: `#065f46` (emerald-800) – Fondo base lateral
- `--color-base-2`: `#064e3b` (emerald-900) – Fondo superior lateral
- `--color-texto`: `#f3f4f6` (slate-100) – Texto claro sobre fondo oscuro
- `--color-hover`: `#047857` (emerald-700) – Hover en navegación lateral
- `--color-acento`: `#10b981` (emerald-500) – Acento/realce
- `--color-acento-2`: `#34d399` (emerald-400) – Acento secundario / foco

### Paleta funcional
- Texto principal: `#222`
- Fondo de página: `#f5f5f5`
- Fondo de área de contenido: `#f9fafb`
- Superficies (tarjeta/bloque): `#fff`
- Borde tabla: `#e5e7eb`
- Tabla encabezado: `#f3f4f6`
- Primario botón: `#059669` (hover `#047857`)
- Neutro botón: `#6b7280` (hover `#4b5563`)
- Peligro botón: `#dc2626` (hover `#b91c1c`)
- Alerta éxito: fondo `#d1fae5`, texto `#065f46`, borde `#10b981`
- Alerta error: fondo `#fee2e2`, texto `#991b1b`, borde `#ef4444`

### Tipografía
- Familia: `system-ui, Arial, sans-serif`
- Tamaños de referencia:
  - Base: `1rem`
  - Título principal (`.titulo`): `1.6rem`
  - Subtítulos (`.bloque h2`): `1.15rem`
  - Texto secundario (`.texto-secundario`, celdas tabla): `0.9rem`
- Peso: `600` en etiquetas y botones destacados

### Espaciado y medidas
Se usan valores en `rem` para escalabilidad. Escala sugerida (aprox. usada en estilos):

- `0.25rem` (4px), `0.5rem` (8px), `0.6rem` (10px), `0.75rem` (12px)
- `0.8rem` (13px), `0.9rem` (14px), `1rem` (16px)
- `1.25rem` (20px), `1.5rem` (24px), `1.6rem` (25px), `2rem` (32px)

### Bordes y radios
- Radios comunes: `5px`, `6px`, `8px`, `10px`
- Bordes de inputs/tablas: `1px solid` con grises claros

### Sombras
- Tarjetas/Bloques: `0 2px 8px rgba(0,0,0,.08)` y `0 1px 4px rgba(0,0,0,.06)`

## Componentes UI

### Botones
- Primario: `.boton-primario`
- Neutro: `.boton`
- Peligro: `.boton-peligro`

```html
<button class="boton-primario" type="submit">Guardar</button>
<a class="boton" href="#">Volver</a>
<button class="boton-peligro" type="button">Eliminar</button>
```

### Alertas
- Contenedor: `.alerta`
- Modificadores: `.exito`, `.error`

```html
<div class="alerta exito" role="status">Operación realizada correctamente.</div>
<div class="alerta error" role="alert">Ha ocurrido un error.</div>
```

### Formularios
Agrupa etiquetas e inputs con `.grupo-form`. Formularios en línea: `.formulario-inline`.

```html
<form action="" method="post">
  <div class="grupo-form">
    <label for="usuario">Usuario</label>
    <input id="usuario" name="usuario" type="text" required />
  </div>
  <div class="grupo-form">
    <label for="password">Contraseña</label>
    <input id="password" name="password" type="password" required />
  </div>
  <button class="boton-primario" type="submit">Entrar</button>
</form>
```

```html
<form class="formulario-inline" action="" method="get">
  <div class="grupo-form">
    <label for="desde">Desde</label>
    <input id="desde" type="date" name="desde" />
  </div>
  <div class="grupo-form">
    <label for="hasta">Hasta</label>
    <input id="hasta" type="date" name="hasta" />
  </div>
  <button class="boton" type="submit">Filtrar</button>
</form>
```

### Tablas
Usa `.tabla` para tablas estándar:

```html
<table class="tabla" role="table">
  <thead>
    <tr>
      <th>Columna</th>
      <th>Columna</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Dato</td>
      <td>Dato</td>
    </tr>
  </tbody>
</table>
```

- Encabezado con fondo gris (`th`).
- Hover de fila: `tbody tr:hover`.

### Tarjetas/Bloques
- Tarjeta simple: `.tarjeta` (login)
- Bloque de contenido: `.bloque` (dashboard)

```html
<section class="bloque">
  <h2>Título de sección</h2>
  <p class="texto-secundario">Descripción o instrucciones.</p>
</section>
```

### Layout y navegación
- Estructura principal con barra lateral: `.layout-principal`
- Lateral: `.sidebar` + grupos y navegación
- Área de contenido: `.area-contenido`
- Enlaces activos: `.activo`

```html
<div class="layout-principal">
  <aside class="sidebar">
    <div class="logo">Panel</div>
    <nav>
      <a class="activo" href="#"><span class="icono" aria-hidden="true"></span> Inicio</a>
      <a href="#"><span class="icono" aria-hidden="true"></span> Partidos</a>
    </nav>
  </aside>
  <main class="area-contenido">
    <!-- contenido -->
  </main>
</div>
```

Responsive: a `max-width:900px` la barra lateral se reacomoda en fila y los enlaces se envuelven.

## Accesibilidad (A11y)

- Foco: los enlaces de la barra lateral muestran `:focus-visible` con contorno de acento. No elimines el estado de foco.
- Etiquetas: todos los `input` con su `label for` correspondiente.
- ARIA: usa `role="alert"` / `role="status"` en alertas; `aria-current="page"` en navegación activa.
- Contraste: mantener contraste AA o superior (los colores definidos ya lo cumplen en uso previsto).
- Texto alternativo: imágenes con `alt` descriptivo.



// Antonio Gat Fernández | agatf02@educarex.es
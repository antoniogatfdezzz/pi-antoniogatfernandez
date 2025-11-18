-- Datos de prueba de partidos
INSERT INTO partidos (equipo_local, equipo_visitante, categoria_id, fecha, pabellon_nombre)
VALUES
 ('Club A', 'Club B', 1, '2025-01-10 18:00:00', 'Pabellón Central'),
 ('Club C', 'Club D', 2, '2025-01-11 16:30:00', 'Pabellón Norte'),
 ('Club E', 'Club F', 3, '2025-01-12 12:00:00', 'Pabellón Sur');

-- Asignar árbitro principal al primer partido (suponiendo arbitro id=1)
UPDATE partidos SET arbitro_principal_id = 1 WHERE id = 1;

-- Crear disponibilidad de prueba para el árbitro
INSERT INTO disponibilidad_arbitros (arbitro_id, fecha, manana, tarde, observacion_manana, observacion_tarde)
VALUES (1, '2025-01-09', 1, 0, 'Disponible mañana', NULL),
	   (1, '2025-01-11', 0, 1, NULL, 'Disponible tarde');


-- Datos iniciales mínimos
INSERT INTO usuarios (tipo_usuario, usuario, email, password, activo)
VALUES ('administrador', 'admin', 'admin@intra.local', '$2y$10$WQo.ToeWzNhajlFGQnxhB.g2nELSp/nFowuOfPIFLf4J1Os5dmibK', 1);

INSERT INTO categorias (nombre) VALUES
 ('INFANTIL'),
 ('CADETE'),
 ('JUVENIL');

-- Usuario árbitro de ejemplo (password hash ficticio, ajustar en entorno real)
INSERT INTO usuarios (tipo_usuario, usuario, email, password, activo)
VALUES ('arbitro', 'arbitro1', 'arbitro1@intra.local', '$2y$10$WQo.ToeWzNhajlFGQnxhB.g2nELSp/nFowuOfPIFLf4J1Os5dmibK', 1);

-- Perfil árbitro asociado
INSERT INTO arbitros (usuario_id, nombre, apellidos, ciudad, licencia)
VALUES (LAST_INSERT_ID(), 'Juan', 'Pérez García', 'Sevilla', 'N3');

-- Segundo usuario árbitro de ejemplo
INSERT INTO usuarios (tipo_usuario, usuario, email, password, activo)
VALUES ('arbitro', 'arbitro2', 'arbitro2@intra.local', '$2y$10$WQo.ToeWzNhajlFGQnxhB.g2nELSp/nFowuOfPIFLf4J1Os5dmibK', 1);

-- Perfil árbitro asociado
INSERT INTO arbitros (usuario_id, nombre, apellidos, ciudad, licencia)
VALUES (LAST_INSERT_ID(), 'María', 'López Díaz', 'Cádiz', 'N1');

-- Tercer usuario árbitro de ejemplo
INSERT INTO usuarios (tipo_usuario, usuario, email, password, activo)
VALUES ('arbitro', 'arbitro3', 'arbitro3@intra.local', '$2y$10$WQo.ToeWzNhajlFGQnxhB.g2nELSp/nFowuOfPIFLf4J1Os5dmibK', 1);

-- Perfil árbitro asociado
INSERT INTO arbitros (usuario_id, nombre, apellidos, ciudad, licencia)
VALUES (LAST_INSERT_ID(), 'Francisco', 'Lorentz Garcia', 'Madrid', 'N1');

CREATE DATABASE IF NOT EXISTS `intra_vb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `intra_vb`;

CREATE TABLE IF NOT EXISTS `usuarios` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`tipo_usuario` ENUM('administrador','arbitro') NOT NULL DEFAULT 'administrador',
	`usuario` VARCHAR(50) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`activo` TINYINT(1) DEFAULT 1,
	UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `categorias` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`nombre` VARCHAR(100) NOT NULL,
	UNIQUE KEY `uk_nombre` (`nombre`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `partidos` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`equipo_local` VARCHAR(100) NOT NULL,
	`equipo_visitante` VARCHAR(100) NOT NULL,
	`categoria_id` INT NOT NULL,
	`fecha` DATETIME NOT NULL,
	`pabellon_nombre` VARCHAR(200) NOT NULL,
	`arbitro_principal_id` INT NULL,
	`arbitro_segundo_id` INT NULL,
	`anotador_id` INT NULL,
	`sets_local` INT NULL,
	`sets_visitante` INT NULL,
	`estado` ENUM('programado','finalizado','cancelado') DEFAULT 'programado',
	INDEX `idx_categoria` (`categoria_id`),
	CONSTRAINT `fk_partidos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE RESTRICT,
	CONSTRAINT `fk_partidos_arbitro_principal` FOREIGN KEY (`arbitro_principal_id`) REFERENCES `arbitros` (`id`) ON DELETE SET NULL,
	CONSTRAINT `fk_partidos_arbitro_segundo` FOREIGN KEY (`arbitro_segundo_id`) REFERENCES `arbitros` (`id`) ON DELETE SET NULL,
	CONSTRAINT `fk_partidos_anotador` FOREIGN KEY (`anotador_id`) REFERENCES `arbitros` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `arbitros` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`usuario_id` INT NOT NULL,
	`nombre` VARCHAR(100) NOT NULL,
	`apellidos` VARCHAR(150) NOT NULL,
	`ciudad` VARCHAR(100) DEFAULT NULL,
	`licencia` VARCHAR(50) DEFAULT NULL,
	CONSTRAINT `fk_arbitros_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
	INDEX `idx_arbitros_usuario` (`usuario_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `disponibilidad_arbitros` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`arbitro_id` INT NOT NULL,
	`fecha` DATE NOT NULL,
	`manana` TINYINT(1) NOT NULL DEFAULT 0,
	`tarde` TINYINT(1) NOT NULL DEFAULT 0,
	`observacion_manana` VARCHAR(255) DEFAULT NULL,
	`observacion_tarde` VARCHAR(255) DEFAULT NULL,
	CONSTRAINT `fk_disponibilidad_arbitro` FOREIGN KEY (`arbitro_id`) REFERENCES `arbitros`(`id`) ON DELETE CASCADE,
	UNIQUE KEY `uk_arbitro_fecha` (`arbitro_id`, `fecha`),
	INDEX `idx_disponibilidad_fecha` (`fecha`)
) ENGINE=InnoDB;



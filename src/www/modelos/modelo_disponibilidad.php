<?php
require_once __DIR__ . '/modelo_base.php';

/**
 * ModeloDisponibilidad: gestiona disponibilidad de los árbitros.
 */
class ModeloDisponibilidad extends ModeloBase {
    /** Obtiene disponibilidad futura de un árbitro */
    public function obtenerPorArbitro(int $arbitroId): array {
        $sql = 'SELECT * FROM disponibilidad_arbitros WHERE arbitro_id = :id AND fecha >= CURDATE() ORDER BY fecha ASC';
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $arbitroId]);
        return $stmt->fetchAll();
    }

    /** Guarda o actualiza disponibilidad de un día */
    public function guardar(int $arbitroId, string $fecha, int $manana, int $tarde, ?string $obsManana, ?string $obsTarde): bool {
        $sql = 'INSERT INTO disponibilidad_arbitros (arbitro_id, fecha, manana, tarde, observacion_manana, observacion_tarde)
                VALUES (:arbitro, :fecha, :manana, :tarde, :obsM, :obsT)
                ON DUPLICATE KEY UPDATE manana = VALUES(manana), tarde = VALUES(tarde), observacion_manana = VALUES(observacion_manana), observacion_tarde = VALUES(observacion_tarde)';
        $stmt = $this->conexion->prepare($sql);
        // Aseguramos que las observaciones nulas se registren como NULL en la base de datos
        return $stmt->execute([
            ':arbitro' => $arbitroId,
            ':fecha' => $fecha,
            ':manana' => $manana,
            ':tarde' => $tarde,
            ':obsM' => $obsManana ?: null,
            ':obsT' => $obsTarde ?: null
        ]);
    }

    /**
     * Lista disponibilidad por fecha con datos del árbitro (para administradores).
     * Devuelve sólo registros existentes en la fecha indicada.
     * @param string $fecha YYYY-MM-DD
     * @return array<int,array>
     */
    public function listarPorFecha(string $fecha): array {
        $sql = 'SELECT d.fecha, d.manana, d.tarde, d.observacion_manana, d.observacion_tarde,
                       a.id AS arbitro_id, a.nombre, a.apellidos, a.ciudad, a.licencia
                FROM disponibilidad_arbitros d
                INNER JOIN arbitros a ON a.id = d.arbitro_id
                WHERE d.fecha = :fecha
                ORDER BY a.apellidos, a.nombre';
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll();
    }
}
?>
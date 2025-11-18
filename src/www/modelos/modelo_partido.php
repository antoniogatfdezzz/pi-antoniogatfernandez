<?php
require_once __DIR__ . '/modelo_base.php';

/**
 * ModeloPartido: CRUD de partidos.
 */
class ModeloPartido extends ModeloBase {
    /**
     * Crea un nuevo partido.
     * @param array $datos
     * @return int ID del partido creado
     */
    public function crear(array $datos): int {
        $sql = 'INSERT INTO partidos (equipo_local, equipo_visitante, categoria_id, fecha, pabellon_nombre, arbitro_principal_id, arbitro_segundo_id, anotador_id) 
                VALUES (:local, :visitante, :categoria, :fecha, :pabellon, :arbitro1, :arbitro2, :anotador)';
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':local' => $datos['equipo_local'],
            ':visitante' => $datos['equipo_visitante'],
            ':categoria' => $datos['categoria_id'],
            ':fecha' => $datos['fecha'],
            ':pabellon' => $datos['pabellon_nombre'],
            ':arbitro1' => $datos['arbitro_principal_id'] ?: null,
            ':arbitro2' => $datos['arbitro_segundo_id'] ?: null,
            ':anotador' => $datos['anotador_id'] ?: null,
        ]);
        return (int)$this->conexion->lastInsertId();
    }

    /**
     * Actualiza partido.
     * @param int $id
     * @param array $datos
     * @return bool
     */
    public function actualizar(int $id, array $datos): bool {
        $sql = 'UPDATE partidos SET equipo_local = :local, equipo_visitante = :visitante, categoria_id = :categoria, fecha = :fecha, pabellon_nombre = :pabellon, 
                arbitro_principal_id = :arbitro1, arbitro_segundo_id = :arbitro2, anotador_id = :anotador WHERE id = :id';
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':local' => $datos['equipo_local'],
            ':visitante' => $datos['equipo_visitante'],
            ':categoria' => $datos['categoria_id'],
            ':fecha' => $datos['fecha'],
            ':pabellon' => $datos['pabellon_nombre'],
            ':arbitro1' => $datos['arbitro_principal_id'] ?: null,
            ':arbitro2' => $datos['arbitro_segundo_id'] ?: null,
            ':anotador' => $datos['anotador_id'] ?: null,
            ':id' => $id
        ]);
    }

    /**
     * Elimina partido por ID.
     */
    public function eliminar(int $id): bool {
        $stmt = $this->conexion->prepare('DELETE FROM partidos WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Obtiene todos los partidos ordenados por fecha descendente.
     * @return array
     */
    public function obtenerTodos(): array {
        $sql = 'SELECT p.*, c.nombre AS categoria_nombre,
                       ap.nombre AS arbitro_principal_nombre, ap.apellidos AS arbitro_principal_apellidos,
                       asg.nombre AS arbitro_segundo_nombre, asg.apellidos AS arbitro_segundo_apellidos,
                       an.nombre AS anotador_nombre, an.apellidos AS anotador_apellidos
                FROM partidos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                LEFT JOIN arbitros ap ON p.arbitro_principal_id = ap.id
                LEFT JOIN arbitros asg ON p.arbitro_segundo_id = asg.id
                LEFT JOIN arbitros an ON p.anotador_id = an.id
                ORDER BY p.fecha DESC';
        $filas = $this->conexion->query($sql)->fetchAll();
        // Añadir campos de nombre completo para facilidad en vistas
        foreach ($filas as &$f) {
            $f['arbitro_principal_nombre_completo'] = trim(($f['arbitro_principal_nombre'] ?? '') . ' ' . ($f['arbitro_principal_apellidos'] ?? '')) ?: null;
            $f['arbitro_segundo_nombre_completo'] = trim(($f['arbitro_segundo_nombre'] ?? '') . ' ' . ($f['arbitro_segundo_apellidos'] ?? '')) ?: null;
            $f['anotador_nombre_completo'] = trim(($f['anotador_nombre'] ?? '') . ' ' . ($f['anotador_apellidos'] ?? '')) ?: null;
        }
        return $filas;
    }

    /**
     * Obtiene un partido específico.
     */
    public function obtenerPorId(int $id): ?array {
        $stmt = $this->conexion->prepare('SELECT * FROM partidos WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }
}
?>

<?php
require_once __DIR__ . '/modelo_base.php';

/**
 * ModeloArbitro: operaciones para la tabla arbitros.
 */
class ModeloArbitro extends ModeloBase {
    /**
     * Lista todos los árbitros activos con nombre completo.
     * @return array<int,array>
     */
    public function listarTodos(): array {
        $sql = 'SELECT a.id, a.nombre, a.apellidos, CONCAT(a.nombre, " ", a.apellidos) AS nombre_completo, a.ciudad, a.licencia
                FROM arbitros a ORDER BY a.apellidos, a.nombre';
        return $this->conexion->query($sql)->fetchAll();
    }
    /**
     * Obtiene un árbitro por el ID de usuario vinculado.
     * @param int $usuarioId
     * @return array|null
     */
    public function obtenerPorUsuario(int $usuarioId): ?array {
        $sql = 'SELECT * FROM arbitros WHERE usuario_id = :uid LIMIT 1';
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':uid', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        $fila = $stmt->fetch();
        return $fila ?: null;
    }
}
?>
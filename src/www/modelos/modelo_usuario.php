<?php
require_once __DIR__ . '/modelo_base.php';

/**
 * ModeloUsuario: operaciones relacionadas con la tabla usuarios.
 */
class ModeloUsuario extends ModeloBase {
    /**
     * Obtiene un usuario activo por nombre de usuario.
     * @param string $usuario
     * @return array|null
     */
    public function obtenerPorUsuario(string $usuario): ?array {
        $sql = 'SELECT * FROM usuarios WHERE usuario = :usuario AND activo = 1 LIMIT 1';
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':usuario', $usuario);
        $stmt->execute();
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    /**
     * Registrar nuevo usuario (para futuros casos de uso).
     * @param string $usuario
     * @param string $email
     * @param string $passwordPlano
     * @param string $tipoUsuario
     * @return int ID generado
     */
    public function crear(string $usuario, string $email, string $passwordPlano, string $tipoUsuario = 'administrador'): int {
        $sql = 'INSERT INTO usuarios (tipo_usuario, usuario, email, password, activo) VALUES (:tipo, :usuario, :email, :password, 1)';
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':tipo' => $tipoUsuario,
            ':usuario' => $usuario,
            ':email' => $email,
            ':password' => password_hash($passwordPlano, PASSWORD_DEFAULT)
        ]);
        return (int)$this->conexion->lastInsertId();
    }

    /**
     * Lista todos los usuarios activos.
     * @return array<int,array>
     */
    public function listarTodos(): array {
    $sql = 'SELECT id, usuario, email, tipo_usuario, activo FROM usuarios WHERE activo = 1 ORDER BY usuario ASC';
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Elimina lógicamente un usuario por ID (marca activo=0).
     * @param int $id
     * @return bool
     */
    public function eliminarPorId(int $id): bool {
        $sql = 'UPDATE usuarios SET activo = 0 WHERE id = :id';
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>

<?php
/**
 * Clase base para modelos: centraliza la conexión PDO y utilidades comunes.
 */
class ModeloBase {
    /** @var PDO */
    protected $conexion;

    public function __construct() {
        $this->conexion = $this->crearConexion();
    }

    /**
     * Crea y devuelve una instancia PDO.
     * @return PDO
     */
    protected function crearConexion(): PDO {
        $dsn = 'mysql:host=' . BD_HOST . ';dbname=' . BD_NOMBRE . ';charset=utf8mb4';
        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, BD_USUARIO, BD_PASSWORD, $opciones);
    }
}
?>

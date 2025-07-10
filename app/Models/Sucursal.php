<?php

namespace Mindshot\Kiyozumicf\Models;

use Mindshot\Kiyozumicf\Core\Database;
use PDO;

class Sucursal
{
    private PDO $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->db = (new Database($config['db']))->getConnection();
    }

    /**
     * Obtiene todas las sucursales ordenadas por nombre.
     * @return array
     */
    public function obtenerTodas(): array
    {
        $stmt = $this->db->query("SELECT * FROM sucursales ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una sucursal por su ID.
     * @param int $id
     * @return array|null
     */
    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM sucursales WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $sucursal = $stmt->fetch(PDO::FETCH_ASSOC);
        return $sucursal ?: null;
    }

    /**
     * Crea una nueva sucursal.
     * @param string $nombre
     * @param string|null $direccion
     * @param string|null $telefono
     * @return int
     */
    public function crear(string $nombre, ?string $direccion, ?string $telefono): int
    {
        $sql = "INSERT INTO sucursales (nombre, direccion, telefono) VALUES (:nombre, :direccion, :telefono)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'direccion' => $direccion,
            'telefono' => $telefono
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Actualiza los datos de una sucursal.
     * @param int $id
     * @param string $nombre
     * @param string|null $direccion
     * @param string|null $telefono
     * @return bool
     */
    public function actualizar(int $id, string $nombre, ?string $direccion, ?string $telefono): bool
    {
        $sql = "UPDATE sucursales SET nombre = :nombre, direccion = :direccion, telefono = :telefono WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'direccion' => $direccion,
            'telefono' => $telefono
        ]);
    }

    /**
     * Elimina una sucursal por su ID.
     * @param int $id
     * @return bool
     */
    public function eliminar(int $id): bool
    {
        // El ON DELETE CASCADE se encargará de borrar las relaciones en `maquinas_sucursales`
        $sql = "DELETE FROM sucursales WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
<?php

namespace Mindshot\Kiyozumicf\Models;

use Mindshot\Kiyozumicf\Core\Database;
use PDO;

class Clase
{
    private PDO $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->db = (new Database($config['db']))->getConnection();
    }

    public function obtenerTodas(): array
    {
        $stmt = $this->db->query("SELECT * FROM clases ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM clases WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $clase = $stmt->fetch(PDO::FETCH_ASSOC);
        return $clase ?: null;
    }

    public function crear(string $nombre, string $descripcion, string $tipo, string $dias, string $horario, string $estado): int
    {
        $sql = "INSERT INTO clases (nombre, descripcion, tipo, dias, horario, estado) VALUES (:nombre, :descripcion, :tipo, :dias, :horario, :estado)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'tipo' => $tipo,
            'dias' => $dias,
            'horario' => $horario,
            'estado' => $estado
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function actualizar(int $id, string $nombre, string $descripcion, string $tipo, string $dias, string $horario, string $estado): bool
    {
        $sql = "UPDATE clases SET nombre = :nombre, descripcion = :descripcion, tipo = :tipo, dias = :dias, horario = :horario, estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'tipo' => $tipo,
            'dias' => $dias,
            'horario' => $horario,
            'estado' => $estado
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM clases WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
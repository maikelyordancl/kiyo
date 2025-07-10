<?php

namespace Mindshot\Kiyozumicf\Models;

use Mindshot\Kiyozumicf\Core\Database;
use PDO;

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->db = (new Database($config['db']))->getConnection();
    }

    public function obtenerPorUsername(string $username): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }
}

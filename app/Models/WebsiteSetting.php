<?php

namespace Mindshot\Kiyozumicf\Models;

use Mindshot\Kiyozumicf\Core\Database;
use PDO;

class WebsiteSetting
{
    private PDO $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->db = (new Database($config['db']))->getConnection();
    }

    public function getSetting(string $key): ?string
    {
        $stmt = $this->db->prepare("SELECT setting_value FROM website_settings WHERE setting_key = :key LIMIT 1");
        $stmt->execute(['key' => $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['setting_value'] : null;
    }

    public function getAllSettings(): array
    {
        $stmt = $this->db->query("SELECT setting_key, setting_value FROM website_settings ORDER BY setting_key ASC");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Retorna un array asociativo [clave => valor]
    }

    public function updateSetting(string $key, string $value): bool
    {
        $sql = "INSERT INTO website_settings (setting_key, setting_value) VALUES (:key, :value)
                ON DUPLICATE KEY UPDATE setting_value = :value";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['key' => $key, 'value' => $value]);
    }

    public function deleteSetting(string $key): bool
    {
        $stmt = $this->db->prepare("DELETE FROM website_settings WHERE setting_key = :key");
        return $stmt->execute(['key' => $key]);
    }
}
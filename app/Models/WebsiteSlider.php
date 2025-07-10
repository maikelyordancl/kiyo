<?php

namespace Mindshot\Kiyozumicf\Models;

use Mindshot\Kiyozumicf\Core\Database;
use PDO;

class WebsiteSlider
{
    private PDO $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->db = (new Database($config['db']))->getConnection();
    }

    public function getAllSliders(): array
    {
        $stmt = $this->db->query("SELECT * FROM website_sliders ORDER BY order_num ASC, id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSliderById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM website_sliders WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $slider = $stmt->fetch(PDO::FETCH_ASSOC);
        return $slider ?: null;
    }

    public function createSlider(
        string $headingTop,
        string $headingBottom,
        string $paragraph1 = '',
        string $paragraph2 = '',
        ?string $backgroundImage = null,
        ?string $foregroundImage = null,
        int $orderNum = 0,
        bool $isActive = true
    ): int {
        $sql = "INSERT INTO website_sliders (heading_top, heading_bottom, paragraph_1, paragraph_2, background_image, foreground_image, order_num, is_active)
                VALUES (:heading_top, :heading_bottom, :paragraph_1, :paragraph_2, :background_image, :foreground_image, :order_num, :is_active)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'heading_top' => $headingTop,
            'heading_bottom' => $headingBottom,
            'paragraph_1' => $paragraph1,
            'paragraph_2' => $paragraph2,
            'background_image' => $backgroundImage,
            'foreground_image' => $foregroundImage,
            'order_num' => $orderNum,
            'is_active' => $isActive
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateSlider(
        int $id,
        string $headingTop,
        string $headingBottom,
        string $paragraph1,
        string $paragraph2,
        ?string $backgroundImage,
        ?string $foregroundImage,
        int $orderNum,
        bool $isActive
    ): bool {
        $sql = "UPDATE website_sliders SET
                    heading_top = :heading_top,
                    heading_bottom = :heading_bottom,
                    paragraph_1 = :paragraph_1,
                    paragraph_2 = :paragraph_2,
                    background_image = :background_image,
                    foreground_image = :foreground_image,
                    order_num = :order_num,
                    is_active = :is_active
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'heading_top' => $headingTop,
            'heading_bottom' => $headingBottom,
            'paragraph_1' => $paragraph1,
            'paragraph_2' => $paragraph2,
            'background_image' => $backgroundImage,
            'foreground_image' => $foregroundImage,
            'order_num' => $orderNum,
            'is_active' => $isActive
        ]);
    }

    public function deleteSlider(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM website_sliders WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
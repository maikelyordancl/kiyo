<?php

namespace Mindshot\Kiyozumicf\Models;

use Mindshot\Kiyozumicf\Core\Database;
use PDO;

class Maquina
{
    private PDO $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->db = (new Database($config['db']))->getConnection();
    }
     /**
     * 🔥 OBTENER MÁQUINAS POR SUCURSAL (MODIFICADO)
     * Obtiene todas las máquinas asignadas a una sucursal específica.
     * @param int $id_sucursal
     * @return array
     */
    public function obtenerPorSucursal(int $id_sucursal): array
    {
        $sql = "SELECT m.* FROM maquinas m
                JOIN maquinas_sucursales ms ON m.id = ms.id_maquina
                WHERE ms.id_sucursal = :id_sucursal
                ORDER BY m.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_sucursal' => $id_sucursal]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * 🔥 OBTENER TODAS LAS MÁQUINAS (SIN CAMBIOS)
     * Sigue siendo útil para funciones globales o para copiar entre sucursales.
     */
    public function obtenerTodas(): array
    {
        $stmt = $this->db->query("SELECT * FROM maquinas ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * 🔥 OBTENER MÁQUINAS NO ASIGNADAS A UNA SUCURSAL (NUEVO)
     * Obtiene las máquinas que existen en el sistema pero NO están en la sucursal actual.
     * @param int $id_sucursal
     * @return array
     */
    public function obtenerNoAsignadas(int $id_sucursal): array
    {
        $sql = "SELECT * FROM maquinas
                WHERE id NOT IN (SELECT id_maquina FROM maquinas_sucursales WHERE id_sucursal = :id_sucursal)
                ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_sucursal' => $id_sucursal]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * 🔥 CREAR MÁQUINA Y ASIGNARLA A SUCURSAL (MODIFICADO)
     * Crea una máquina y la asigna automáticamente a la sucursal activa.
     * @param string $nombre
     * @param int $id_sucursal
     * @return int|false El ID de la nueva máquina o false si falla.
     */
    public function crearYAsignar(string $nombre, int $id_sucursal): int|false
    {
        try {
            $this->db->beginTransaction();

            // 1. Crear la máquina
            $stmt = $this->db->prepare("INSERT INTO maquinas (nombre, descripcion, estado) VALUES (:nombre, '', 'activa')");
            $stmt->execute(['nombre' => $nombre]);
            $id_maquina = (int)$this->db->lastInsertId();

            // 2. Asignarla a la sucursal
            $this->asignarASucursal($id_maquina, $id_sucursal);

            $this->db->commit();
            return $id_maquina;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en Maquina::crearYAsignar => " . $e->getMessage());
            return false;
        }
    }

    /**
     * 🔥 ASIGNAR MÁQUINA EXISTENTE A UNA SUCURSAL (NUEVO)
     * @param int $id_maquina
     * @param int $id_sucursal
     * @return bool
     */
    public function asignarASucursal(int $id_maquina, int $id_sucursal): bool
    {
        $sql = "INSERT IGNORE INTO maquinas_sucursales (id_maquina, id_sucursal) VALUES (:id_maquina, :id_sucursal)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id_maquina' => $id_maquina, 'id_sucursal' => $id_sucursal]);
    }

    /**
     * 🔥 QUITAR MÁQUINA DE UNA SUCURSAL (NUEVO)
     * @param int $id_maquina
     * @param int $id_sucursal
     * @return bool
     */
    public function desasignarDeSucursal(int $id_maquina, int $id_sucursal): bool
    {
        $sql = "DELETE FROM maquinas_sucursales WHERE id_maquina = :id_maquina AND id_sucursal = :id_sucursal";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id_maquina' => $id_maquina, 'id_sucursal' => $id_sucursal]);
    }

    // 🔥 Obtener máquina por ID
    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM maquinas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $maquina = $stmt->fetch(PDO::FETCH_ASSOC);
        return $maquina ?: null;
    }

    // 🔥 Crear máquina rápida solo con nombre
    public function crear(string $nombre): int
    {
        $stmt = $this->db->prepare("INSERT INTO maquinas (nombre, descripcion, estado) VALUES (:nombre, '', 'activa')");
        $stmt->execute(['nombre' => $nombre]);
        return (int)$this->db->lastInsertId();
    }

    // 🔥 Obtener imágenes de la máquina
    public function obtenerImagenes(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM maquina_imagenes WHERE maquina_id = :id ORDER BY id ASC");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔥 Obtener videos de la máquina
    public function obtenerVideos(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM maquina_videos WHERE maquina_id = :id ORDER BY id ASC");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔥 Actualizar datos de la máquina
    public function actualizar(
        int $id,
        string $nombre,
        string $descripcion,
        string $estado,
        array $imagenes,
        array $videos,
        ?string $imagenPortada = null
    ): bool {
        try {
            $this->db->beginTransaction();

            // Actualizar datos principales
            $sql = "UPDATE maquinas 
                    SET nombre = :nombre, 
                        descripcion = :descripcion, 
                        estado = :estado";

            if ($imagenPortada) {
                $sql .= ", imagen_portada = :imagen_portada";
            }

            $sql .= " WHERE id = :id";

            $params = [
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'estado' => $estado,
                'id' => $id
            ];

            if ($imagenPortada) {
                $params['imagen_portada'] = $imagenPortada;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            // Limpiar imágenes existentes
            $this->db->prepare("DELETE FROM maquina_imagenes WHERE maquina_id = :id")
                     ->execute(['id' => $id]);

            // Insertar nuevas imágenes
            foreach ($imagenes as $img) {
                $this->db->prepare("INSERT INTO maquina_imagenes (maquina_id, url_imagen) VALUES (:id, :url)")
                         ->execute(['id' => $id, 'url' => $img]);
            }

            // Limpiar videos existentes
            $this->db->prepare("DELETE FROM maquina_videos WHERE maquina_id = :id")
                     ->execute(['id' => $id]);

            // Insertar nuevos videos
            foreach ($videos as $vid) {
                $this->db->prepare("INSERT INTO maquina_videos (maquina_id, url_video) VALUES (:id, :url)")
                         ->execute(['id' => $id, 'url' => $vid]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Error en Maquina::actualizar => " . $e->getMessage());
            return false;
        }
    }
    // 🔥 Eliminar una máquina por ID
    public function eliminar(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            // Eliminar imágenes
            $this->db->prepare("DELETE FROM maquina_imagenes WHERE maquina_id = :id")
                     ->execute(['id' => $id]);

            // Eliminar videos
            $this->db->prepare("DELETE FROM maquina_videos WHERE maquina_id = :id")
                     ->execute(['id' => $id]);

            // Eliminar la máquina
            $this->db->prepare("DELETE FROM maquinas WHERE id = :id")
                     ->execute(['id' => $id]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Error en Maquina::eliminar => " . $e->getMessage());
            return false;
        }
    }

}

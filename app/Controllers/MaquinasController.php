<?php

namespace Mindshot\Kiyozumicf\Controllers;

use Mindshot\Kiyozumicf\Core\Controller;
use Mindshot\Kiyozumicf\Helpers\AuthMiddleware;
use Mindshot\Kiyozumicf\Models\Maquina;

class MaquinasController extends Controller
{
    private function verificarSucursalSeleccionada()
    {
        if (!isset($_SESSION['sucursal_seleccionada'])) {
            // Si no hay sucursal, redirigir a la página de sucursales para que elija o cree una.
            header('Location: /sucursales');
            exit;
        }
    }
    
    /**
     * Muestra el listado de máquinas DE LA SUCURSAL SELECCIONADA
     */
     public function index()
    {
        AuthMiddleware::check();
        $this->verificarSucursalSeleccionada();

        $maquinaModel = new Maquina();
        $id_sucursal_actual = $_SESSION['sucursal_seleccionada']['id'];

        $maquinas_en_sucursal = $maquinaModel->obtenerPorSucursal($id_sucursal_actual);
        $maquinas_no_asignadas = $maquinaModel->obtenerNoAsignadas($id_sucursal_actual);
        
        // --- NUEVO: Obtenemos TODAS las máquinas para el panel de administración global ---
        $maquinas_todas = $maquinaModel->obtenerTodas();

        $this->view('Maquinas/maquinas', [
            'maquinas' => $maquinas_en_sucursal,
            'maquinas_no_asignadas' => $maquinas_no_asignadas,
            'maquinas_todas' => $maquinas_todas // Pasamos la nueva variable a la vista
        ]);
    }

    /**
     * Crea una NUEVA máquina y la asigna a la sucursal actual
     */
    public function crear()
    {
        AuthMiddleware::check();
        $this->verificarSucursalSeleccionada();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            if (!empty($nombre)) {
                $maquinaModel = new Maquina();
                $id_sucursal_actual = $_SESSION['sucursal_seleccionada']['id'];
                $id_maquina_nueva = $maquinaModel->crearYAsignar($nombre, $id_sucursal_actual);

                if ($id_maquina_nueva) {
                    header('Location: /maquinas/editar/' . $id_maquina_nueva);
                    exit;
                }
            }
        }
        header('Location: /maquinas');
        exit;
    }
    
    /**
     * Asigna una máquina YA EXISTENTE a la sucursal actual
     */
    public function asignarExistente()
    {
        AuthMiddleware::check();
        $this->verificarSucursalSeleccionada();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ahora esperamos un array de IDs
            $ids_maquinas = $_POST['id_maquinas'] ?? []; 
            
            if (!empty($ids_maquinas)) {
                $maquinaModel = new Maquina();
                $id_sucursal_actual = $_SESSION['sucursal_seleccionada']['id'];
                
                // Iteramos sobre cada ID y lo asignamos
                foreach ($ids_maquinas as $id_maquina) {
                    if (filter_var($id_maquina, FILTER_VALIDATE_INT)) {
                        $maquinaModel->asignarASucursal((int)$id_maquina, $id_sucursal_actual);
                    }
                }
            }
        }
        header('Location: /maquinas');
        exit;
    }

    /**
     * Quita una máquina de la sucursal actual (no la elimina del sistema)
     */
    public function desasignar($id_maquina)
    {
        AuthMiddleware::check();
        $this->verificarSucursalSeleccionada();

        $maquinaModel = new Maquina();
        $id_sucursal_actual = $_SESSION['sucursal_seleccionada']['id'];
        $maquinaModel->desasignarDeSucursal((int)$id_maquina, $id_sucursal_actual);

        header('Location: /maquinas');
        exit;
    }

    public function editar($id)
    {
        AuthMiddleware::check();

        $maquinaModel = new Maquina();
        $maquina = $maquinaModel->obtenerPorId((int)$id);

        if (!$maquina) {
            echo "Máquina no encontrada";
            exit;
        }

        $imagenes = $maquinaModel->obtenerImagenes((int)$id);
        $videos = $maquinaModel->obtenerVideos((int)$id);

        $this->view('Maquinas/editar', [
            'maquina' => $maquina,
            'imagenes' => $imagenes,
            'videos' => $videos
        ]);
    }

    public function actualizar($id)
    {
        AuthMiddleware::check();

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $estado = $_POST['estado'];
        $videos = json_decode($_POST['videos'], true) ?? [];

        // Carpeta de subida
        $uploadDir = __DIR__ . '/../../public/uploads/';
        $uploadUrl = '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Imagen de portada
        $imagenPortada = null;
        if (isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] === 0) {
            $extension = pathinfo($_FILES['imagen_portada']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('portada_') . '.' . strtolower($extension);
            $targetFile = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['imagen_portada']['tmp_name'], $targetFile)) {
                $imagenPortada = $uploadUrl . $filename;
            }
        }

        // Imágenes nuevas
        $imagenesSubidas = [];
        if (!empty($_FILES['nuevas_imagenes']['name'][0])) {
            foreach ($_FILES['nuevas_imagenes']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['nuevas_imagenes']['error'][$key] === 0) {
                    $extension = pathinfo($_FILES['nuevas_imagenes']['name'][$key], PATHINFO_EXTENSION);
                    $filename = uniqid('img_') . '.' . strtolower($extension);
                    $targetFile = $uploadDir . $filename;

                    if (move_uploaded_file($tmp_name, $targetFile)) {
                        $imagenesSubidas[] = $uploadUrl . $filename;
                    }
                }
            }
        }

        // Imágenes existentes
        $imagenesExistentes = json_decode($_POST['imagenes'], true) ?? [];

        // Unir imágenes existentes con nuevas
        $imagenes = array_merge($imagenesExistentes, $imagenesSubidas);

        // Guardar en la base de datos
        $maquinaModel = new Maquina();
        $resultado = $maquinaModel->actualizar(
            (int)$id,
            $nombre,
            $descripcion,
            $estado,
            $imagenes,
            $videos,
            $imagenPortada
        );

        if ($resultado) {
            header('Location: /maquinas/editar/' . $id);
            exit;
        } else {
            echo "Error al actualizar la máquina.";
        }
    }

    public function ver($id)
    {
        $maquinaModel = new Maquina();
        $maquina = $maquinaModel->obtenerPorId((int)$id);

        if (!$maquina) {
            echo "Máquina no encontrada";
            exit;
        }

        $imagenes = $maquinaModel->obtenerImagenes((int)$id);
        $videos = $maquinaModel->obtenerVideos((int)$id);

        // --- INICIO DE ADICIONES PARA CLASES ---
        $claseModel = new Clase();
        $clases = $claseModel->obtenerTodas(); // Obtener todas las clases

        $clasesActivas = array_filter($clases, function($clase) {
            return $clase['estado'] === 'activa';
        });

        $clasesMensualidad = array_filter($clasesActivas, function($clase) {
            return $clase['tipo'] === 'mensualidad';
        });

        $clasesAdicionales = array_filter($clasesActivas, function($clase) {
            return $clase['tipo'] === 'adicional';
        });
        // --- FIN DE ADICIONES PARA CLASES ---

        $this->view('Maquinas/ver', [
            'maquina' => $maquina,
            'imagenes' => $imagenes,
            'videos' => $videos,
            // --- AÑADIR ESTAS NUEVAS VARIABLES ---
            'clasesMensualidad' => $clasesMensualidad,
            'clasesAdicionales' => $clasesAdicionales,
            'hayClases' => !empty($clasesActivas)
            // --- FIN DE NUEVAS VARIABLES ---
        ]);
    }
    public function eliminar($id)
    {
        AuthMiddleware::check();

        $maquinaModel = new Maquina();
        // La lógica de eliminación en el modelo ya elimina la máquina y sus relaciones (imágenes, videos, etc)
        $resultado = $maquinaModel->eliminar((int)$id);

        // Opcional: añadir mensajes de éxito/error en la sesión (flash messages)
        // if ($resultado) { ... }

        header('Location: /maquinas');
        exit;
    }
}
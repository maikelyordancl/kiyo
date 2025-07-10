<?php

namespace Mindshot\Kiyozumicf\Controllers;

use Mindshot\Kiyozumicf\Core\Controller;
use Mindshot\Kiyozumicf\Helpers\AuthMiddleware;
use Mindshot\Kiyozumicf\Models\Clase; // Importar el nuevo modelo Clase

class ClasesController extends Controller
{
    // Método para listar y gestionar clases (ADMIN)
    public function index()
    {
        AuthMiddleware::check(); // Proteger esta ruta

        $claseModel = new Clase();
        $clases = $claseModel->obtenerTodas();

        $this->view('Clases/clases', [
            'clases' => $clases
        ]);
    }

    // Método para crear una nueva clase (ADMIN)
    public function crear()
    {
        AuthMiddleware::check(); // Proteger esta ruta

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $tipo = trim($_POST['tipo'] ?? '');
            $dias = trim($_POST['dias'] ?? '');
            $horario = trim($_POST['horario'] ?? '');
            $estado = trim($_POST['estado'] ?? 'inactiva'); // Default inactiva si no se marca

            if (empty($nombre) || empty($tipo) || empty($dias) || empty($horario)) {
                // Puedes añadir un mensaje de error si lo deseas
                header('Location: /clases'); // Redirigir de vuelta al listado
                exit;
            }

            $claseModel = new Clase();
            $id = $claseModel->crear($nombre, $descripcion, $tipo, $dias, $horario, $estado);

            if ($id) {
                header('Location: /clases/editar/' . $id);
            } else {
                // Manejar error de creación
                header('Location: /clases');
            }
            exit;
        }
        header('Location: /clases'); // Si no es POST, redirigir
        exit;
    }

    // Método para mostrar el formulario de edición de una clase (ADMIN)
    public function editar($id)
    {
        AuthMiddleware::check(); // Proteger esta ruta

        $claseModel = new Clase();
        $clase = $claseModel->obtenerPorId((int)$id);

        if (!$clase) {
            echo "Clase no encontrada";
            exit;
        }

        $this->view('Clases/editar', [
            'clase' => $clase
        ]);
    }

    // Método para actualizar una clase (ADMIN)
    public function actualizar($id)
    {
        AuthMiddleware::check(); // Proteger esta ruta

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $tipo = trim($_POST['tipo'] ?? '');
            $dias = trim($_POST['dias'] ?? '');
            $horario = trim($_POST['horario'] ?? '');
            $estado = trim($_POST['estado'] ?? 'inactiva'); // Default inactiva si no se marca

            if (empty($nombre) || empty($tipo) || empty($dias) || empty($horario)) {
                // Puedes añadir un mensaje de error si lo deseas
                header('Location: /clases/editar/' . $id);
                exit;
            }

            $claseModel = new Clase();
            $resultado = $claseModel->actualizar((int)$id, $nombre, $descripcion, $tipo, $dias, $horario, $estado);

            if ($resultado) {
                header('Location: /clases/editar/' . $id);
            } else {
                echo "Error al actualizar la clase.";
            }
            exit;
        }
        header('Location: /clases'); // Si no es POST, redirigir
        exit;
    }

    // Método para eliminar una clase (ADMIN)
    public function eliminar($id)
    {
        AuthMiddleware::check(); // Proteger esta ruta

        $claseModel = new Clase();
        $resultado = $claseModel->eliminar((int)$id);

        if ($resultado) {
            header('Location: /clases');
        } else {
            echo "Error al eliminar la clase.";
        }
        exit;
    }

    // Método para mostrar todas las clases disponibles al público (CLIENTE)
    public function verTodas()
    {
        $claseModel = new Clase();
        $clases = $claseModel->obtenerTodas(); // Obtener todas, luego filtrar por estado 'activa' en la vista si es necesario, o pasar solo activas

        $clasesActivas = array_filter($clases, function($clase) {
            return $clase['estado'] === 'activa';
        });

        $clasesMensualidad = array_filter($clasesActivas, function($clase) {
            return $clase['tipo'] === 'mensualidad';
        });

        $clasesAdicionales = array_filter($clasesActivas, function($clase) {
            return $clase['tipo'] === 'adicional';
        });


        $this->view('Clases/ver_todas', [
            'clasesMensualidad' => $clasesMensualidad,
            'clasesAdicionales' => $clasesAdicionales,
            'hayClases' => !empty($clasesActivas)
        ]);
    }
}
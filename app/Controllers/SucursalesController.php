<?php

namespace Mindshot\Kiyozumicf\Controllers;

use Mindshot\Kiyozumicf\Core\Controller;
use Mindshot\Kiyozumicf\Helpers\AuthMiddleware;
use Mindshot\Kiyozumicf\Models\Sucursal;

class SucursalesController extends Controller
{
    /**
     * Muestra la lista de sucursales y el formulario para crear una nueva.
     */
    public function index()
    {
        AuthMiddleware::check();
        $sucursalModel = new Sucursal();
        $sucursales = $sucursalModel->obtenerTodas();

        $this->view('Sucursales/index', ['sucursales' => $sucursales]);
    }

    /**
     * Procesa la creaciÃ³n de una nueva sucursal.
     */
    public function crear()
    {
        AuthMiddleware::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? null);
            $telefono = trim($_POST['telefono'] ?? null);

            if (!empty($nombre)) {
                $sucursalModel = new Sucursal();
                $sucursalModel->crear($nombre, $direccion, $telefono);
            }
        }
        header('Location: /sucursales');
        exit;
    }

    /**
     * Muestra el formulario para editar una sucursal.
     * @param int $id
     */
    public function editar($id)
    {
        AuthMiddleware::check();
        $sucursalModel = new Sucursal();
        $sucursal = $sucursalModel->obtenerPorId((int)$id);

        if (!$sucursal) {
            // Manejar error de sucursal no encontrada
            header('Location: /sucursales');
            exit;
        }

        $this->view('Sucursales/editar', ['sucursal' => $sucursal]);
    }

    /**
     * Procesa la actualizaciÃ³n de una sucursal.
     * @param int $id
     */
    public function actualizar($id)
    {
        AuthMiddleware::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? null);
            $telefono = trim($_POST['telefono'] ?? null);

            if (!empty($nombre)) {
                $sucursalModel = new Sucursal();
                $sucursalModel->actualizar((int)$id, $nombre, $direccion, $telefono);
            }
        }
        header('Location: /sucursales');
        exit;
    }

    /**
     * Procesa la eliminaciÃ³n de una sucursal.
     * @param int $id
     */
    public function eliminar($id)
    {
        AuthMiddleware::check();
        $sucursalModel = new Sucursal();
        $sucursalModel->eliminar((int)$id);

        header('Location: /sucursales');
        exit;
    }
    /**
     * Selecciona una sucursal y la guarda en la sesi¨®n.
     * @param int $id
     */
    public function seleccionar($id)
    {
        AuthMiddleware::check();
        $sucursalModel = new Sucursal();
        $sucursal = $sucursalModel->obtenerPorId((int)$id);

        if ($sucursal) {
            $_SESSION['sucursal_seleccionada'] = [
                'id' => $sucursal['id'],
                'nombre' => $sucursal['nombre']
            ];
        }

        // Redirigir a la p¨¢gina anterior o al dashboard por defecto
        $redirectTo = $_SERVER['HTTP_REFERER'] ?? '/panel';
        header('Location: ' . $redirectTo);
        exit;
    }
}
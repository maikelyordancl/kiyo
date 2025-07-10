<?php

namespace Mindshot\Kiyozumicf\Controllers;

use Mindshot\Kiyozumicf\Core\Controller;
use Mindshot\Kiyozumicf\Helpers\AuthMiddleware;
use Mindshot\Kiyozumicf\Models\Maquina;

class PanelController extends Controller
{
    public function index()
    {
        AuthMiddleware::check();

        $usuario = $_SESSION['usuario'];
        $maquinaModel = new Maquina();
        $maquinas = $maquinaModel->obtenerTodas();

        $this->view('Panel/dashboard', [
            'usuario' => $usuario,
            'maquinas' => $maquinas
        ]);
    }
}

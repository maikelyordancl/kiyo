<?php

namespace Mindshot\Kiyozumicf\Controllers;

use Mindshot\Kiyozumicf\Core\Controller;
use Mindshot\Kiyozumicf\Models\Usuario;
use Mindshot\Kiyozumicf\Models\Sucursal; // <-- AÑADIR ESTA LÍNEA

class AuthController extends Controller
{
    public function index()
    {
        $this->view('Auth/login');
    }

    public function login()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->obtenerPorUsername($username);

            if ($usuario && password_verify($password, $usuario['password'])) {
                // Guardar datos del usuario en la sesión
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'username' => $usuario['username'],
                    'nombre' => $usuario['nombre_completo']
                ];

                // --- INICIO DE LA MODIFICACIÓN ---
                // Seleccionar la primera sucursal por defecto
                $sucursalModel = new Sucursal();
                $sucursales = $sucursalModel->obtenerTodas();

                if (!empty($sucursales)) {
                    // Si hay sucursales, seleccionamos la primera
                    $_SESSION['sucursal_seleccionada'] = [
                        'id' => $sucursales[0]['id'],
                        'nombre' => $sucursales[0]['nombre']
                    ];
                } else {
                    // Si no hay ninguna sucursal, dejamos el valor como null
                    $_SESSION['sucursal_seleccionada'] = null;
                }
                // --- FIN DE LA MODIFICACIÓN ---

                header('Location: /panel');
                exit; // Añadido exit para asegurar que el script se detenga

            } else {
                // Usuario o contraseña incorrecta
                $this->view('Auth/login', ['error' => 'Usuario o contraseña incorrecta']);
                exit; // Añadido exit
            }
        }
        // Redirigir si no es POST
        header('Location: /');
        exit;
    }


    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit; // Añadido exit
    }
}
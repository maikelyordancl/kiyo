<?php

namespace Mindshot\Kiyozumicf\Controllers;

use Mindshot\Kiyozumicf\Core\Controller;
use Mindshot\Kiyozumicf\Helpers\AuthMiddleware;
use Mindshot\Kiyozumicf\Models\WebsiteSetting; // Importar el nuevo modelo WebsiteSetting
use Mindshot\Kiyozumicf\Models\WebsiteSlider; // Importar el nuevo modelo WebsiteSlider

class WebsiteController extends Controller
{
    private string $uploadDir;
    private string $uploadUrl;

    public function __construct()
    {
        // Directorios para subir imágenes del sitio web
        $this->uploadDir = __DIR__ . '/../../public/uploads/website/';
        $this->uploadUrl = '/uploads/website/';

        // Asegurarse de que el directorio de subida exista
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    // Método para la página de inicio del panel de administración del sitio web
    public function index()
    {
        AuthMiddleware::check(); // Proteger esta ruta
        $this->view('Website/dashboard'); // Una vista simple de dashboard para la administración web
    }

    // --- Gestión de Configuración del Sitio Web (Website Settings) ---

    // Muestra el formulario para editar la configuración global
    public function settings()
    {
        AuthMiddleware::check(); // Proteger esta ruta
        $settingModel = new WebsiteSetting();
        $settings = $settingModel->getAllSettings(); // Obtener todas las configuraciones existentes
        $this->view('Website/settings', ['settings' => $settings]);
    }

    // Maneja la actualización de la configuración global
    public function updateSettings()
    {
        AuthMiddleware::check(); // Proteger esta ruta
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = new WebsiteSetting();
            $success = true;
            foreach ($_POST as $key => $value) {
                // Procesar solo los campos que comienzan con 'setting_'
                if (strpos($key, 'setting_') === 0) {
                    $cleanKey = str_replace('setting_', '', $key); // Eliminar el prefijo 'setting_'
                    if (!$settingModel->updateSetting($cleanKey, trim($value))) {
                        $success = false;
                        // Aquí se podría añadir lógica para un mensaje de error
                    }
                }
            }
            if ($success) {
                // Aquí se podría añadir lógica para un mensaje de éxito
            } else {
                // Aquí se podría añadir lógica para un mensaje de error
            }
            header('Location: /website/settings'); // Redirigir de nuevo a la página de configuración
            exit;
        }
        header('Location: /website'); // Redirigir si no es una solicitud POST
        exit;
    }

    // --- Gestión de Sliders del Sitio Web ---

    // Muestra el listado de sliders y el formulario para añadir uno nuevo
    public function sliders()
    {
        AuthMiddleware::check(); // Proteger esta ruta
        $sliderModel = new WebsiteSlider();
        $sliders = $sliderModel->getAllSliders(); // Obtener todos los sliders
        $this->view('Website/sliders', ['sliders' => $sliders]);
    }

    // Maneja la creación de un nuevo slider
    public function createSlider()
    {
        AuthMiddleware::check(); // Proteger esta ruta
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $headingTop = trim($_POST['heading_top'] ?? '');
            $headingBottom = trim($_POST['heading_bottom'] ?? '');
            $paragraph1 = trim($_POST['paragraph_1'] ?? '');
            $paragraph2 = trim($_POST['paragraph_2'] ?? '');
            $orderNum = (int)($_POST['order_num'] ?? 0);
            $isActive = isset($_POST['is_active']);

            $backgroundImage = $this->handleFileUpload('background_image'); // Subir imagen de fondo
            $foregroundImage = $this->handleFileUpload('foreground_image'); // Subir imagen de primer plano

            $sliderModel = new WebsiteSlider();
            $id = $sliderModel->createSlider(
                $headingTop,
                $headingBottom,
                $paragraph1,
                $paragraph2,
                $backgroundImage,
                $foregroundImage,
                $orderNum,
                $isActive
            );

            if ($id) {
                header('Location: /website/sliders'); // Redirigir al listado de sliders
            } else {
                // Manejar error de creación
            }
            exit;
        }
        header('Location: /website/sliders'); // Redirigir si no es POST
        exit;
    }

    // Muestra el formulario para editar un slider existente
    public function editSlider($id)
    {
        AuthMiddleware::check(); // Proteger esta ruta
        $sliderModel = new WebsiteSlider();
        $slider = $sliderModel->getSliderById((int)$id);
    
        if (!$slider) {
            echo "Slider no encontrado.";
            exit;
        }
    
        // Cargar la configuración y obtener la URL base
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = $config['app']['base_url'];
    
        // Pasar el slider y la URL base a la vista
        $this->view('Website/edit_slider', [
            'slider' => $slider,
            'baseUrl' => $baseUrl
        ]);
    }

    // Maneja la actualización de un slider existente
    public function updateSlider($id)
    {
        AuthMiddleware::check(); // Proteger esta ruta
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sliderModel = new WebsiteSlider();
            $slider = $sliderModel->getSliderById((int)$id);

            if (!$slider) {
                echo "Slider no encontrado.";
                exit;
            }

            $headingTop = trim($_POST['heading_top'] ?? '');
            $headingBottom = trim($_POST['heading_bottom'] ?? '');
            $paragraph1 = trim($_POST['paragraph_1'] ?? '');
            $paragraph2 = trim($_POST['paragraph_2'] ?? '');
            $orderNum = (int)($_POST['order_num'] ?? 0);
            $isActive = isset($_POST['is_active']);

            // Manejo de la subida de imagen de fondo
            $backgroundImage = $slider['background_image']; // Conservar la existente si no hay nueva subida
            if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] === UPLOAD_ERR_OK) {
                $newBgImage = $this->handleFileUpload('background_image');
                if ($newBgImage) {
                    $backgroundImage = $newBgImage;
                }
            } elseif (isset($_POST['remove_background_image']) && $_POST['remove_background_image'] === '1') {
                // Opcional: Eliminar el archivo antiguo del disco
                if ($backgroundImage && file_exists(__DIR__ . '/../../public' . $backgroundImage)) {
                    unlink(__DIR__ . '/../../public' . $backgroundImage);
                }
                $backgroundImage = null;
            }

            // Manejo de la subida de imagen de primer plano
            $foregroundImage = $slider['foreground_image']; // Conservar la existente si no hay nueva subida
            if (isset($_FILES['foreground_image']) && $_FILES['foreground_image']['error'] === UPLOAD_ERR_OK) {
                $newFgImage = $this->handleFileUpload('foreground_image');
                if ($newFgImage) {
                    $foregroundImage = $newFgImage;
                }
            } elseif (isset($_POST['remove_foreground_image']) && $_POST['remove_foreground_image'] === '1') {
                // Opcional: Eliminar el archivo antiguo del disco
                if ($foregroundImage && file_exists(__DIR__ . '/../../public' . $foregroundImage)) {
                    unlink(__DIR__ . '/../../public' . $foregroundImage);
                }
                $foregroundImage = null;
            }

            $resultado = $sliderModel->updateSlider(
                (int)$id,
                $headingTop,
                $headingBottom,
                $paragraph1,
                $paragraph2,
                $backgroundImage,
                $foregroundImage,
                $orderNum,
                $isActive
            );

            if ($resultado) {
                header('Location: /website/editSlider/' . $id); // Redirigir a la página de edición del slider
            } else {
                echo "Error al actualizar el slider.";
            }
            exit;
        }
        header('Location: /website/sliders'); // Redirigir si no es POST
        exit;
    }

    // Maneja la eliminación de un slider
    public function deleteSlider($id)
    {
        AuthMiddleware::check(); // Proteger esta ruta
        $sliderModel = new WebsiteSlider();
        $resultado = $sliderModel->deleteSlider((int)$id);

        if ($resultado) {
            header('Location: /website/sliders'); // Redirigir al listado de sliders
        } else {
            echo "Error al eliminar el slider.";
        }
        exit;
    }

    // Función auxiliar para manejar la subida de archivos
    private function handleFileUpload(string $fieldName): ?string
    {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
            $filename = uniqid($fieldName . '_') . '.' . strtolower($extension);
            $targetFile = $this->uploadDir . $filename;

            if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetFile)) {
                return $this->uploadUrl . $filename;
            }
        }
        return null;
    }
}
<?php

namespace Mindshot\Kiyozumicf\Core;

class Controller
{
    public function view(string $view, array $data = []): void
    {
        extract($data);
        require_once "../app/Views/$view.php";
    }

    public function model(string $model)
    {
        $modelClass = "Mindshot\\Kiyozumicf\\Models\\$model";
        return new $modelClass();
    }
}

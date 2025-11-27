<?php
require_once __DIR__ .  '/../models/CategoryModel.php';
require_once __DIR__ . '/../../config/constants.php';

class CategoryController extends ApplicationController {
    private CategoryModel $model;

    public function __construct() {
        $this->model = new CategoryModel();
    }

    
    public function indexAction() {
    $this->view->categories = $this->model->getAll();
    }

    public function createAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            
            if ($name !== '') {
                $this->model->create(['name' => $name]);
            }
        }

        // Redirigir de nuevo a la lista
        header('Location: ' . WEB_ROOT . '/categories');
        exit;
    }

    public function updateAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');

            if ($id > 0 && $name !== '') {
                $this->model->update($id, ['name' => $name]);
            }
        }

        header('Location: ' . WEB_ROOT . '/categories');
        exit;
    }

    public function deleteAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->model->delete($id);
            }
        }

        header('Location: ' . WEB_ROOT . '/categories');
        exit;
    }

}
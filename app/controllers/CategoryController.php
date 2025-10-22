<?php
require_once ROOT_PATH . '/app/models/CategoryModel.php';
require_once ROOT_PATH . '/app/controllers/ApplicationController.php';

class CategoryController extends ApplicationController
{
    private CategoryModel $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    
    public function indexAction()
    {
       $this->view->categories = $this->model->getAll();
    }

    public function createAction()
    {
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

}
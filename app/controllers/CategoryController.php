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

}
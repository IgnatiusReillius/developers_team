<?php 

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */
$routes = array(
	'/' => 'test#index', // Si está vacío, cargará "index.php" de la carpeta "(app/views/scripts/)test"
	'/error' => 'error#error',

	// rutas para las tareas
	'/task/viewTask' => 'task#viewTask',
    '/task/create'   => 'task#createTask',
    '/task/edit'     => 'task#edit',
    '/task/delete'   => 'task#delete',
);

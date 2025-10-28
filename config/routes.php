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
	'/test' => 'test#index',
	 // Ruta para la lista de categorías
    '/categories' => 'Category#index',
	'/categories/create'  => 'Category#create',
    '/categories/update'  => 'Category#update',
    '/categories/delete'  => 'Category#delete',


	//---Users routes---
	'/login' => 'user#login',       //parte dcha se refiere a la accion en el controlador.
	'/signup' => 'user#signup',
	'/registeredUser' => 'user#registeredUser',
	'/delete' => 'user#delete',
	'/update' => 'user#update',
	'/home' => 'user#home',
	'/logout' => 'user#logout',
	'/deleteConfirm' => 'user#deleteConfirm',

	  // raíz → login
    '/' => 'user#login',
    ''  => 'user#login'
);

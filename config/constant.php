<?php

// Ruta absoluta a la raíz del proyecto
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . '/../'); 
}

if (!defined('VIEWS_PATH')) {
    define('VIEWS_PATH', __DIR__ . '/../app/views/');
}
define('DATA_JSON', __DIR__ . '/../lib/data/users.json');
define('BASE_URL', rtrim(WEB_ROOT, '/'));



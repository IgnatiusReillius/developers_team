#TASKIFY

Es un proyecto web para gestionar tareas.
Las crea el mismo usuario y le puede asignar categorías.

Funcionalidades:
- CRUD de tareas
- CRUD de usuarios
- CRUD de categorías


Las tecnologías usadas son:
- PHP 
- HTML/framework de CSS Tailwind.
- JSON como almacenamiento de datos
- Servidor local: Apache
- Entorno: XAMPP


Recomendación para descargarlo en tu ordenador:

1. Terminal:
   Desde la terminal dirigete a la ubicación donde tengas la carpeta XAMPP/htdocs

2. Clonar el repositorio:
   git clone <https://github.com/IgnatiusReillius/developers_team>

3. Abrir el proyecto en el navegador:
   http://localhost/developers_team/web


Estructura del proyecto:

/app
   /controllers
     - ApplicationController.php
     - CategoryController.php
     - ErrorController.php
     - TaskController.php
     - TestController.php
     - UserController.php
   /model
     - CategoryModel.php
     - TaskModel.php
     - UserModel.php
     README
   /views
     /layouts
      - layout.phtml
      - header.phtml
      - subheader.phtml
      - footer.phtml
     /scripts
      /category
       - index.phtml
      /error
       - error.phtml
      /task
       - index.phtml
      /test
       - index.phtml
      /user
       - deleteConfirm.phtml
       - home.phtml
       - login.phtml
       - registeredUser.phtml
       - signup.phtml
       - update.phtm
/config
 /buttoms
   - volverInicio.php
 - constants.php
 - db.inc.php
 - environment.inc.php
 - routes.php
 - settings.ini
/lib
   /base
    - Controller.php
    - Model.php
    - Request.php
    - Router.php
    - View.php
   /data
    - categories.json
    - task.json
    - users.json
   README
/web
 /images
   README
   - icon-delete.svg
   - icon-edit.svg
   - icon-email.svg
   - icon-password.svg
   - icon-tick.svg
   - icon-user.svg
   - logo-taskify.svg
 /javascripts
   README
   - category.js
 /stylesheets
   - .htaccess
   - index.php
README


⚠️ Nota para usuarios de Mac:
Es posible que los archivos JSON no se puedan escribir después de un pull de Git. 
Ejecutar en la terminal dentro de la carpeta /data:
chmod 666 data/*.json
chmod 777 data



  
       


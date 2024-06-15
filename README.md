**Correr de forma correcta el gestor de tareas**

Hola, este proyecto se desarrollo usando MVC, php puro con POO, JS, HTML, Boostrap5 y DataTable, por los que si o si se uso jQuery, para poder trabajar el datatable y usar sus beneficios. Adicionalmente, se hace uso de Alert2 para que las notificaciones se vean bonitas, y los endpoint generados en PHP se consumen con fetch, ya que fue mucho mas sencillo que trabajar cUrl de PHP.
Tener en cuenta que no se uso JWT, ni ningun tipo de seguridad.

Pasos:

1. Clonar el repositorio en htdocs o www dependiendo de que use para correr localmente sus proyectos, el repositorio se va a llamar gestiontareas1, por error humano los endpoint los cree con **gestionTareasDC1**, así que por favor **nombrar** el directorio del proyecto con **gestionTareasDC1**.
2. Navegue dentro del proyecto recien clonado y visite la ruta “ **Models/tareas.sql** “ donde va a encontrar una copia vacía de la base de datos usada, la cual tendrá que importar en sus sistema local.
3. Cree una bd llamada “ **gestiontareas** “ e importe el sql que deje en la ruta del paso 3. Generalmente me gusta crear las bd en utf8mb4_spanish2_ci
4. Ahora vaya a su navegador e inicie el sistema en la url: http://localhost/gestionTareasDC1/
5. Si desea probar algun endpoint, ingrese a la clase creada en el controlador llamada Tarea.php, copie cualquier metodo y complementelo en este endpoint: **http://localhost/gestionTareasDC1/tarea/endpoint** por ejemplo:
   http://localhost/gestionTareasDC1/tarea/agregarTarea
  y envia este JSON:
  
  {
      "titulo": "",
      "descripcion": "",
      "completado": 0
  
  }
  
  
  Los estados posibles para completado son 1 y 0 pero en el front se les dio valor de completado al numero 1 y pendiente al numero 0

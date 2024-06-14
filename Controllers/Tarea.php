<?php
class Tarea extends Controllers {

    public function __construct() {
        parent::__construct();
    }

    // Método para listar una tarea
    public function tarea($id) {
        echo "Hola desde tarea con el id: " . $id;
    }

    // Método para listar todas las tareas
    public function tareas() {
        echo "Hola desde tarea";
    }

    public function agregarTarea() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                $_POST = json_decode(file_get_contents('php://input'), true);

                if (!testString($_POST['titulo'])) {
                    $response = array('status' => false, 'msg' => 'El título es requerido');
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['descripcion'])) {
                    $response = array('status' => false, 'msg' => 'La descripción es requerida');
                    jsonResponse($response, 200);
                    die();
                }



                $strTitulo = $_POST['titulo'];
                $strDescripcion = $_POST['descripcion'];
                $strCompletado = intval($_POST['completado']); // Convertir a int

                $request = $this->model->setTarea(
                    $strTitulo,
                    $strDescripcion,
                    $strCompletado
                );

                if ($request > 0) {
                    $arrTarea = array(
                        'id' => $request,
                        'titulo' => $strTitulo,
                        'descripcion' => $strDescripcion,
                        'completado' => $strCompletado
                    );
                    $response = array('status' => true, 'msg' => 'Datos guardados correctamente', 'data' => $arrTarea);
                } else {
                    $response = array('status' => false, 'msg' => 'El título o descripción ya existe');
                }

                $code = 200;
            } else {
                $response = array('status' => false, 'msg' => 'Error en la solicitud ' . $method);
                $code = 400;
            }

            jsonResponse($response, $code);
            die();

        } catch (Exception $e) {
            $response = array('status' => false, 'msg' => 'Error en el proceso: ' . $e->getMessage());
            jsonResponse($response, 500);
        }
    }

    public function editarTarea() {
        // Implementar lógica para editar tarea
    }

    public function eliminarTarea() {
        // Implementar lógica para eliminar tarea
    }
}
?>

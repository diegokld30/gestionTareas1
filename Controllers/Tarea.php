<?php
class Tarea extends Controllers {

    public function __construct() {
        parent::__construct();
    }

    // Método para listar todas las tareas
    public function tareas() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method == "GET") {
                $data = $this->model->getTareas();
                $response = array('status' => true, 'data' => $data);
                jsonResponse($response, 200);
            } else {
                $response = array('status' => false, 'msg' => 'Método no permitido');
                jsonResponse($response, 405);
            }
        } catch (Exception $e) {
            $response = array('status' => false, 'msg' => 'Error en el proceso: ' . $e->getMessage());
            jsonResponse($response, 500);
        }
    }

    public function tarea($id) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method == "GET") {
                $data = $this->model->getTareaById($id);
                if (!empty($data)) {
                    $response = array('status' => true, 'data' => $data);
                } else {
                    $response = array('status' => false, 'msg' => 'Tarea no encontrada');
                }
                jsonResponse($response, 200);
            } else {
                $response = array('status' => false, 'msg' => 'Método no permitido');
                jsonResponse($response, 405);
            }
        } catch (Exception $e) {
            $response = array('status' => false, 'msg' => 'Error en el proceso: ' . $e->getMessage());
            jsonResponse($response, 500);
        }
    }

    public function marcarTareas() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method == "PUT") {
                $_PUT = json_decode(file_get_contents('php://input'), true);
                $ids = $_PUT['ids'];
                $completado = intval($_PUT['completado']);

                foreach ($ids as $id) {
                    $this->model->updateTareaStatus($id, $completado);
                }

                $response = array('status' => true, 'msg' => 'Tareas actualizadas correctamente');
                jsonResponse($response, 200);
            } else {
                $response = array('status' => false, 'msg' => 'Método no permitido');
                jsonResponse($response, 405);
            }
        } catch (Exception $e) {
            $response = array('status' => false, 'msg' => 'Error en el proceso: ' . $e->getMessage());
            jsonResponse($response, 500);
        }
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
                $strCompletado = intval($_POST['completado']);

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
                    $response = array('success' => true, 'status' => true, 'msg' => 'Datos guardados correctamente', 'data' => $arrTarea);
                } else {
                    $response = array('success' => false, 'status' => false, 'msg' => 'El título o descripción ya existe');
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

    public function actualizarTarea($id) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "PUT") {
                $_PUT = json_decode(file_get_contents('php://input'), true);

                if (empty($_PUT['titulo']) || empty($_PUT['descripcion'])) {
                    $response = array('status' => false, 'msg' => 'El título y la descripción son requeridos');
                    jsonResponse($response, 200);
                    die();
                }

                $strTitulo = $_PUT['titulo'];
                $strDescripcion = $_PUT['descripcion'];
                $strCompletado = intval($_PUT['completado']);

                $request = $this->model->updateTarea(
                    $id,
                    $strTitulo,
                    $strDescripcion,
                    $strCompletado
                );

                if ($request) {
                    $response = array('status' => true, 'msg' => 'Datos actualizados correctamente');
                } else {
                    $response = array('status' => false, 'msg' => 'Error al actualizar los datos');
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

    public function eliminarTarea($id) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method == "DELETE") {
                $request = $this->model->deleteTarea($id);
                if ($request) {
                    $response = array('status' => true, 'msg' => 'Tarea eliminada correctamente');
                } else {
                    $response = array('status' => false, 'msg' => 'Error al eliminar la tarea');
                }
                jsonResponse($response, 200);
            } else {
                $response = array('status' => false, 'msg' => 'Método no permitido');
                jsonResponse($response, 405);
            }
        } catch (Exception $e) {
            $response = array('status' => false, 'msg' => 'Error en el proceso: ' . $e->getMessage());
            jsonResponse($response, 500);
        }
    }
}
?>

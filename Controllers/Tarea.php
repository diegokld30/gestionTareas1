<?php
class Tarea extends Controllers{

    public function __construct()
    {
        parent::__construct();
    }
    //Metodo para listar una tarea
    public function tarea($id){
        echo "Hola desde tarea con el id: ".$id;
    }
    //Metodo para listar todas las tareas
    public function tareas(){
        echo "Hola desde tarea";
    }
    public function agregarTarea(){
        try {
            $method = $_SERVER["REQUEST_METHOD"];
            $response = [];
            if($method == "POST"){
                $response = array(
                    'status' =>true,
                    'msg' => 'Datos guardados con exito REY'
                );
                $code = 200;
            }else{
                $response = array(
                    'status' => false,
                    'msg' => 'Error en la solicitud REY por el metodo: '.$method.'. Cambie a POST'
                );
                $code = 400;
            }

            header("HTTP/1.1 ".$code);
            header("Content-type: application/json");
            echo json_encode($response);

        }catch (Exception $e){
            echo "Error en el proceso: ".$e->getMessage();

        }
    }

    public function editarTarea()
    {

    }

    public function eliminarTarea()
    {

    }


}


?>
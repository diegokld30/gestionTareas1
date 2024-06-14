<?php

class TareaModel extends Mysql
{
    private $strTitulo;
    private $strDescripcion;
    private $intCompletado;

    public function __construct()
    {
        parent::__construct();
    }

    public function setTarea(string $titulo, string $descripcion, string $completado)
    {
        $this->strTitulo = $titulo;
        $this->strDescripcion = $descripcion;
        $this->intCompletado = $completado;

        $sql = "SELECT titulo, descripcion FROM tareas WHERE (titulo = :titulo OR descripcion = :descripcion) AND completado = :completado";
        $arrParams = array(
            ":titulo" => $this->strTitulo,
            ":descripcion" => $this->strDescripcion,
            ":completado" => '0' // buscando solo tareas no completadas
        );
        $request = $this->select($sql, $arrParams);

        if (!empty($request)) {
            return false;
        } else {
            $query_insert = "INSERT INTO tareas(titulo, descripcion, completado) VALUES(:titulo, :descripcion, :completado)";
            $arrData = array(
                ":titulo" => $this->strTitulo,
                ":descripcion" => $this->strDescripcion,
                ":completado" => $this->intCompletado
            );
            $request_insert = $this->insert($query_insert, $arrData);
            return $request_insert;
        }
    }

    public function getTareas()
    {
        $sql = "SELECT id, titulo, descripcion, DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro FROM tareas WHERE completado != '1' ORDER BY id DESC";
        $request = $this->select_all($sql);
        return $request;
    }
    public function putTarea(int $id, string $titulo, string $descripcion, $completado){
        $this->intIdTarea = $id;
        $this->strTitulo = $titulo;
        $this->strDescripcion = $descripcion;
        $this->intCompletado = $completado;

        $sql = "SELECT titulo,descripcion FROM tareas WHERE
            (titulo = :titulo AND id != :id) OR
            (titulo = :titulo AND id != :id) AND 
            completado=1";
        $arrayData = array(":titulo" => $this->strTitulo,
            ":descripcion" =>  $this->strDescripcion,
            ":id" => $this->intIdTarea
        );
        $request_tarea = $this->select($sql,$arrayData);

        if(empty($request_tarea)){
            $sql= "UPDATE tareas SET titulo = :titulo, descripcion = :descripcion, completado = :completado
                        WHERE id = :id";

            $arrData = array(
                ":titulo" => $this->strTitulo,
                ":descripcion" => $this->strDescripcion,
                ":completado" => $this->intCompletado,
                ":id" => $this->intIdTarea
            );

            $request = $this->update($sql,$arrData);
            return $request;
        }else{
            return false;
        }


    }
}
?>

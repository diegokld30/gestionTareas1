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

    public function setTarea(string $titulo, string $descripcion, int $completado)
    {
        $this->strTitulo = $titulo;
        $this->strDescripcion = $descripcion;
        $this->intCompletado = $completado;

        $sql = "INSERT INTO tareas(titulo, descripcion, completado) VALUES(?, ?, ?)";
        $arrData = array($this->strTitulo, $this->strDescripcion, $this->intCompletado);
        $request_insert = $this->insert($sql, $arrData);
        return $request_insert;
    }

    public function getTareas()
    {
        $sql = "SELECT id, titulo, descripcion, DATE_FORMAT(fechaRegistro, '%d-%m-%Y') as fechaRegistro, completado 
                FROM tareas ORDER BY id DESC";
        $request = $this->select_all($sql);
        return $request;
    }

    public function getTareaById(int $id)
    {
        $sql = "SELECT id, titulo, descripcion, DATE_FORMAT(fechaRegistro, '%d-%m-%Y') as fechaRegistro, completado FROM tareas WHERE id = ?";
        $arrData = array($id);
        $request = $this->select($sql, $arrData);
        return $request;
    }

    public function updateTarea(int $id, string $titulo, string $descripcion, int $completado)
    {
        $sql = "UPDATE tareas SET titulo = ?, descripcion = ?, completado = ? WHERE id = ?";
        $arrData = array($titulo, $descripcion, $completado, $id);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function updateTareaStatus(int $id, int $completado)
    {
        $sql = "UPDATE tareas SET completado = ? WHERE id = ?";
        $arrData = array($completado, $id);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function deleteTarea(int $id)
    {
        $sql = "DELETE FROM tareas WHERE id = ?";
        $arrData = array($id);
        $request = $this->delete($sql, $arrData);
        return $request;
    }


}
?>

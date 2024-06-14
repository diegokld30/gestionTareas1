<?php

    // Habilitar reporte de errores para depuración
    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/

    require_once("Config/Config.php");
    require_once("Helpers/Helpers.php");
    $url = !empty($_GET['url']) ? $_GET['url'] : "home/home" ;
    $arrUrl = explode("/",$url);
    $controller = $arrUrl[0];
    $method =  $arrUrl[0];
    $params = "";

    if(!empty($arrUrl[1])){
        if($arrUrl[1] != ""){
            $method = $arrUrl[1]; 
        }
    }

    if(!empty($arrUrl[2]) && $arrUrl[2] != "")
    {
        for ($i=2; $i < count($arrUrl); $i++) { 
            $params .= $arrUrl[$i].',';
        }
        $params = trim($params,",");
    }

    require_once("Libraries/Core/Autoload.php");
    require_once("Libraries/Core/Load.php");

?>
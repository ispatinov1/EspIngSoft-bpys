<?php
error_reporting(0);
session_start();

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && $_SERVER['REQUEST_METHOD'] == 'POST' 
    && isset($_SERVER['HTTP_REFERER']) && $_POST && isset($_POST['accion'])){      
    
    include_once 'CifradoSimetrico.php';
    
    $api = new CifradoSimetrico();  
    $api->procesarLlamada($_POST['accion'], $_POST['data']);  

}
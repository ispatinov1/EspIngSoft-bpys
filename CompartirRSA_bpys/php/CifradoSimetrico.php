<?php

error_reporting(0);
session_start();

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['HTTP_REFERER'])) {

    if ($_POST && isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'guardarArchivo':
                //$respuesta;
                break;
        }
    } else {
        if (!empty($_FILES['archivo'])) {
            $respuesta = guardarArchivo();
        }
    }
    if ($respuesta) {
        echo json_encode($respuesta);
    }
}

function guardarArchivo() {
    $nombreArchivo = $_FILES['archivo']['name'];
    $tipoArchivo = $_FILES['archivo']['type'];
    $tmpArchivo = $_FILES['archivo']['tmp_name'];

    $nombreDirectorio = preg_replace('/\\.[^.\\s]{3,4}$/', '', $nombreArchivo);
    $nombreDirectorio = $nombreDirectorio .'_'. obtenerIpUsuario();

    if (!is_dir('../tmp/' . $nombreDirectorio)) {
        mkdir('../tmp/' . $nombreDirectorio, 0777);
    }

    $rutaDirectorio = '../tmp/' . $nombreDirectorio . '/';
    $archivoServidor = $rutaDirectorio . '/' . $nombreArchivo;

    if (move_uploaded_file($tmpArchivo, $archivoServidor)) {
        $respuesta = array('status' => true, 'mensaje' => "El archivo:  " . $nombreArchivo . "  fue guardado con exito.");
    } else {
        $respuesta = array('status' => false, 'mensaje' => "Ocurrio un error al subir el archivo:  " . $nombreArchivo . "  No pudo guardarse.");
    }

    return $respuesta;
}


function obtenerIpUsuario() {
    $direcionIpUsuario = '';
    if ($_SERVER['HTTP_CLIENT_IP']) {
        $direcionIpUsuario = $_SERVER['HTTP_CLIENT_IP'];
    } else if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
        $direcionIpUsuario = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if ($_SERVER['HTTP_X_FORWARDED']) {
        $direcionIpUsuario = $_SERVER['HTTP_X_FORWARDED'];
    } else if ($_SERVER['HTTP_FORWARDED_FOR']) {
        $direcionIpUsuario = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if ($_SERVER['HTTP_FORWARDED']) {
        $direcionIpUsuario = $_SERVER['HTTP_FORWARDED'];
    } else if ($_SERVER['REMOTE_ADDR']) {
        $direcionIpUsuario = $_SERVER['REMOTE_ADDR'];
    } else {
        $direcionIpUsuario = 'UNKNOWN';
    }
    return $direcionIpUsuario;
}

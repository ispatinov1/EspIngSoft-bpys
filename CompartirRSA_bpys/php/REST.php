<?php

/**
 * Clase REST para ser heredada por los servicios a implementar en php basada
 * en idea de servicios UGC_0.1 en lumen y especificación rfc2616.
 *
 * @author PATINOV1
 * @version 0.1
 * 
 */
class REST {

    /**
     * Atributo para el tipo de header. 
     * Por el momento solo respuestas tipo MIME application/json
     */
    public $tipo = "application/json";

    /**
     * Atributo para almacenar datos de petición depurados y sanitizados, acordes 
     * al método de entrada.
     */
    public $datosPeticion = array();

    /**
     * Atributo para el codigo de estado en la respuesta a la solicitud.
     */
    private $__codigoEstado = 200;

    /**
     * Método constructor de la clase padre.
     */
    public function __construct() {
        $this->tratarEntrada();
    }

    /**
     * Método que representa la respuesta de la solicitud en tupla datos - codigo de estado.
     * @param $respuesta array u objeto de respuesta, retornado por el api.
     * @param $estado int de codigo de estado para la respuesta.        
     */
    public function mostrarRespuesta($respuesta, $estado) {
        $this->__codigoEstado = ($estado) ? $estado : 200;
        $this->setCabecera();
        echo $respuesta;
        exit;
    }

    /**
     * Método para establecer el header de la respuesta. 
     * - Estandar: HTTP/1.1
     * - Codigo estado: acorde a respuesta.
     * - Tipo de contenido: application/json
     * - Charset: utf-8
     */
    private function setCabecera() {
        header("HTTP/1.1 " . $this->__codigoEstado . " " . $this->getCodigoEstado());
        header("content-Type:" . $this->tipo . ";charset=utf-8");
    }

    /**
     * Método para enrutar solicitudes acorde al método de la solicitud, la trama 
     * depurada se asigna al atributo datosPetición.
     * @param $arrayEntrada[] array del método de entrada .
     * @return $entrada[] array 
     */
    private function limpiarEntrada($arrayEntrada) {
        $entrada = array();
        if (is_array($arrayEntrada)) {
            foreach ($arrayEntrada as $clave => $valor) {
                $entrada[$clave] = $this->limpiarEntrada($valor);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $arrayEntrada = trim(stripslashes($arrayEntrada));
            }
            $arrayEntrada = strip_tags($arrayEntrada);
            $arrayEntrada = htmlentities($arrayEntrada);
            $entrada = trim($arrayEntrada);
        }
        return $entrada;
    }

    /**
     * Método para enrutar solicitudes acorde al método de la solicitud, la trama 
     * depurada se asigna al atributo datosPetición.
     */
    private function tratarEntrada() {
        $metodo = $_SERVER['REQUEST_METHOD'];
        switch ($metodo) {
            case 'GET':
                $this->datosPeticion = $this->limpiarEntrada($_GET);
                break;
            case 'POST':
                $this->datosPeticion = $this->limpiarEntrada($_POST);
                break;
            case 'DELETE':
            case 'PUT':
                parse_str(file_get_contents("php://input"), $this->datosPeticion);
                break;
            default :
                $this->response('', 404);
                break;
        }
    }

    /**
     * Método que retorna elemento de array segun el código de estado de respuesta 
     * a la solicitud, codificacion acorde a especificación rfc2616:     
     * - 1xx:Informational = Información transferencia protocolo-nivel.
     * - 2xx:Success = Solicitud de cliente aceptada de forma exitosa.
     * - 3xx:Redirection = Indica que el cliente debe tomar acciones adicionales para completar su solicitud.
     * - 4xx:Client-Error = Errores en lado del cliente.
     * - 5xx:Client-Error = Errores en lado del servidor.
     */
    private function getCodigoEstado() {
        $estado = array(200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            204 => 'No Content',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            412 => 'Precondition Failed',
            415 => 'Unsupported Media Type',
            500 => 'Internal Server Error',
            501 => 'Not Implemented');
        $respuesta = ($estado[$this->__codigoEstado]) ? $estado[$this->__codigoEstado] : $estado[500];
        return $respuesta;
    }

}

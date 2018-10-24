$(document).ready(function () {   
    
    //defaultCampos();

    /*
     * Evento para todos o cualquier input tipo file
     */
    $('input[type=file]').change(function(){
        var texto = $(this).val();
        var textoLabel = 'Archivo seleccionado: ' + texto.substr(12, texto.length);
        $(this).prev('label').text(textoLabel);
    });
    
    /*
     * Evento para boton 
     */
    $('#btnSubirArchivoLlave').click(function(){
        if($('#fileSeleccionararArchivoLlave').val()){
            subirLlave();
        }
    });
  
});

function defaultCampos(){
    $('#divInfoArchivoLlave').hide();
    $('#spmInfoArchivoLlave').html('');
}

function subirLlave() {
    var archivo = $('#fileSeleccionararArchivoLlave').prop('files')[0];
    var formData = new FormData(archivo);    
    formData.append('archivo', archivo);
    /*
    var parametros= { 
                        accion: 'guardarArchivo',
                        data : new FormData(archivo)
                    };    
    */
    $.ajax({
        //url: "http://127.0.0.1/apisPHPespinsoftNPV/API_bpys/CifradoSimetrico.php/guardarArchivo/",
        //url: "http://127.0.0.1/compartirRSA/php/CifradoSimetrico.php",
        url: "../php/CifradoSimetrico.php",
        type: "POST",
        async: true,
        dataType: "json",
        contentType: false,        
        processData: false,
        cache: false,
        data: formData,
        success: function (respuesta) {
            if(respuesta.status){
                $('#divInfoArchivoLlave').addClass('alert-success');
                $('#divInfoArchivoLlave').removeClass('alert-warning');
            }
            else{
                $('#divInfoArchivoLlave').addClass('alert-warning');
                $('#divInfoArchivoLlave').removeClass('alert-success');
            }
            $('#divInfoArchivoLlave').show();
            $('#spmInfoArchivoLlave').html(respuesta.mensaje);
                
        },
        error: function (jqXHR, textStatus, errorThrown) {                                                   
            var msgError = "Error en solicitud Ajax de subida de llaves | " + jqXHR.responseText + " | " + textStatus + " | " + errorThrown;
            alert(msgError);
        }
    });
}



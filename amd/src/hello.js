$(document).ready(function(){
    var contenido;
    //$("#mform1").attr("action","reporte3.php");
    var id = document.getElementsByName('id');
    var context_id= document.getElementsByName('context_id');
    function consulta(param)
    {
        console.log('inicio');
        $.ajax({
        data:  param,
        url:   'consulta.php',
        type:  'post',
        beforeSend: function () {
                $("#resultado").html("Procesando, espere por favor...");
        },
        success:  function (response) {
                $("#resultado").html(response);
                contenido= response;
                contenido = contenido.replace('[','');
                contenido = contenido.replace(']','');
                contenido = contenido.replace('"','');
                contenido = contenido.replace('"','');
                var res = contenido.split(",");
                console.log(res[0]);

                $('#id_paralelo').find('option').remove().end().append('<option value="0">Seleccione un componente</option>').val('0');
                $.each(res, function (i, item) {
                    //res[i].replace('"');
                    console.log(res[i]);
                    $('#id_paralelo').append($('<option>', {value: res[i],text : res[i] }));
                });

            }
        });
        
    }
    $('#id_filtro1').keyup(function(event) {
        var param = {'1': $('#id_filtro1').val()};
        console.log('valor del campo');
        console.log(param['1']);
        consulta(param);
       
    });
    // $('#id_paralelo').click(function(event){
    //     alert($this.value());
    // });
    $("#id_paralelo").change(function(){
            var value= $("#id_paralelo").val();
            $('input[name=selected]').attr('value', $("#id_paralelo").val());
            //$('#id_paralelo').val();
            //alert($('input[name=selected]').val());
           // alert($('input[name=selected]').val());

        }); 
    function myfunction(){
        console.log('ola k ace');
    }
    myfunction();

});
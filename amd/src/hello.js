$(document).ready(function(){
        $('input[name=selected_variant]').attr('value', null);
        $('input[name=selected_period]').attr('value', null);
        var path = '';
    //Load periods for initial display
    function load_periodos(){
        var param = {'0': 2,'1': 0, '2':0};
        console.log(param);
         $.ajax({
            data:  param,
            url:   'consulta.php',
            type:  'post',
            success:  function (response) {
                    console.log('periodos en js:');
                    console.log(response);
                    var periods = jQuery.parseJSON(response);;
                    console.log(periods);

                    $.each(periods, function (i, item) {
                        //res[i].replace('"')
                        
                        $('#id_period').append($('<option>', {value: periods[i].id,text : periods[i].name }));
                    });
                }
            });
    }
    //Request variants and charge period id value
    $("#id_period").change(function(){
        path='';
        console.log('id periodo');
        console.log($("#id_period").val());
        $('input[name=selected_period]').attr('value', $("#id_period").val());
            var period_val= {'1':$("#id_period").val()};
                $('#id_variant').find('option').remove().end().append('<option value="0">Seleccione un componente</option>').val(null);
                path ='/'.concat($("#id_period").val());
                console.log(path);
                var param = {'0': 1,'1': $("#id_period").val(), '2':0};
                console.log(param);
            $.ajax({
                data: param,
                url:   'consulta.php',
                type:  'post',
                success:  function (response)
                {
                        console.log('variantes en js:');
                        console.log(response);
                        var variants = jQuery.parseJSON(response);
                        console.log(variants);
                        $.each(variants, function (i, item) {
                        $('#id_variant').append($('<option>', {value: variants[i].id,text : variants[i].name }));
                });

            }
            });

        }); 
    $('#id_variant').change(function(){
        console.log('id variante');
        console.log($("#id_variant").val());
        $('input[name=selected_variant]').attr('value', $("#id_variant").val());
        path='/'.concat($("#id_period").val());
        if($("#id_variant").val()!=0)
        {
            path=path.concat('/');
            path=path.concat($("#id_variant").val());
        }
        
        console.log(path);
    });
    var contenido;
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
                var res = jQuery.parseJSON(response);
                console.log(res);

                $('#id_paralelo').find('option').remove().end().append('<option value="0">Seleccione un componente</option>').val('0');
                $.each(res, function (i, item) {
                    $('#id_paralelo').append($('<option>', {value: res[i],text : res[i] }));
                });

            }
        });
        
    }
    $('#id_filtro1').keyup(function(event) {
        var param = {'0': 0,'1': $('#id_filtro1').val(), '2':path};
        console.log(param);
        console.log('valor del campo');
        console.log(param['1']);
        consulta(param);
       
    });
    $("#id_paralelo").change(function(){
            // var value= $("#id_paralelo").val();
            $('input[name=selected]').attr('value', $("#id_paralelo").val());
            // $('#mform1').submit();
        }); 
    function myfunction(){
        console.log('ola k ace');
    }
    myfunction();
    load_periodos();

});
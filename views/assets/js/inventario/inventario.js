/**
 * Created by Fede on 06/09/2017.
 */
$('.side-menu li:eq(4)').addClass('active');
$('.side-menu li:eq(5)').addClass('active2');
$("#sl-stock-sal").select2({
    dropdownParent: $('#modal-out')
});
$("#sl-stock-ent").select2({
    dropdownParent: $('#modal-in')
});

$('.eliminar').on('click', function(){
    var id = $(this).find("#codigo-inventario").val();
    $("input[name='codigo-inventario']").val(id);
    var producto = $(this).find("#producto").val();
    $("input[name='producto']").val(producto);
});

$('.end').on('click', function () {
    location.reload();
});

/*--------Inserta las salidas de materiales AJAX--------*/
$('form[name="form-out"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    //var url = window.location.pathname;

    var fecha = $('input[name="fecha"]').val();
    var cantidad = $('input[name="cantidad"]').val();
    var motivo = $('input[name="motivo"]').val();
    

    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp > 0){
                var html;
                html = '<tr>';
                html +=  '<td><i class="fa fa-arrow-circle-down" style="color:#FA2A00"></i></td>';
                html +=  '<td>'+fecha+'</td>';
                html +=  '<td>'+$("#sl-stock-sal").find(":selected").text()+'</td>';
                html +=  '<td>'+cantidad+'</td>';
                html +=  '<td></td>';
                html +=  '<td>'+motivo+'</td>';
                html +=  '<td>'+$("#sl-empleado").find(":selected").text()+'</td>';
                html +=  '<td class="text-center"><i class="fa fa-times delete"></i></td>';
                html +='</tr>';
                //guardado();
                $('.table').find('> tbody:last-child').append(html);
                $('input[name="cantidad"]').val('').focus();
                $('.alert-modal').text('Insertado Correctamente!!');
            }else{
                $('.alert-modal').text('Error en los datos!!');
            }
        },
        error: function (jqXHR,estado,error){
            console.log(estado);
            console.log(error);
        }
    });
});


/*--------Inserta las entradas de materiales AJAX--------*/
$('form[name="form-in"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    //var url = window.location.pathname;

    var fecha = $('input[name="fecha"]').val();
    var cantidad = $('input[name="cantidad"]').val();
    var motivo = $('input[name="motivo"]').val();


    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp > 0){
                var html;
                html = '<tr>';
                html +=  '<td><i class="fa fa-arrow-circle-up" style="color:#5cb85c"></i></td>';
                html +=  '<td>'+fecha+'</td>';
                html +=  '<td>'+$("#sl-stock-ent").find(":selected").text()+'</td>';
                html +=  '<td>'+cantidad+'</td>';
                html +=  '<td></td>';
                html +=  '<td>'+motivo+'</td>';
                html +=  '<td>'+$("#sl-empleado-ent").find(":selected").text()+'</td>';
                html +=  '<td class="text-center"><i class="fa fa-times delete"></i></td>';
                html +='</tr>';
                //guardado();
                $('.table').find('> tbody:last-child').append(html);
                $('input[name="cantidad"]').val('').focus();
                $('.alert-modal').text('Insertado Correctamente!!');
            }else{
                $('.alert-modal').text('Error en los datos!!');
            }
        },
        error: function (jqXHR,estado,error){
            console.log(estado);
            console.log(error);
        }
    });
});
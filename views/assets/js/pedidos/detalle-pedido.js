/**
 * Created by Fede on 31/08/2017.
 */
$('.side-menu li:eq(17)').addClass('active');
$('#cant-producto').focus();


if($('#saldo').html() == "0"){
    $('#end-order').show();
}

var cliente = $('input[name="idcliente"]').val();
$("#sl-customer").val(cliente).change();

//Confirmar Cobro
$('.cobro').on('click', function(){
    var id = $(this).attr("id");
    var obs =  $(this).parents("tr").find(".obs-pay").html();
    var deuda =  $(this).parents("tr").find(".deuda-pay").html();
    $("input[name='obs']").val(obs);
    $("input[name='cobro']").val(deuda);
    $("input[name='iddeuda']").val(id);
    $('#modal-pay').modal('show');
    //alert (id);
});

$('.end').on('click', function () {
    location.reload();
});

$('#receipt-senha').on('click',function() {
    var presupuesto = $('#nro-presu').text();
    $('#input-concept').val("Seña de "+presupuesto);
    $('#input-cost').val($('#senha').text());
});

/*--------Inserta los productos de los pedidos AJAX--------*/
$('form[name="insert-product"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    //var url = window.location.pathname;

    var cantidad = $('input[name="cantidad"]').val();
    var costo = $('input[name="costo"]').val();
    var producto = $('input[name="producto"]').val();

    var nocero = costo.replace(/\./g, '');
    var num = parseInt(nocero);
    var total = num*cantidad;
    var totalN = total.toLocaleString('es-PY');

    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp > 0){
                var html;
                html = '<tr class="fila-detalle">';
                html +=  '<td></td>';
                html +=  '<td class="text-center">'+cantidad+'</td>';
                html +=  '<td>'+producto+'</td>';
                html +=  '<td>'+costo+'</td>';
                html +=  '<td>'+totalN+'</td>';
                html +=  '<td class="text-center"><i class="fa fa-times"></i></td>';
                html +='</tr>';
                //guardado();
                $('#tabla-productos').find('> tbody:last-child').append(html);
                $('input[name="cantidad"]').val('');
                $('input[name="costo"]').val('');
                $('input[name="producto"]').val('').focus();
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

/*--------Inserta los cobros de los pedidos AJAX--------*/
$('form[name="insert-pay"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    //var url = window.location.pathname;

    var fecha = $('input[name="fecha"]').val();
    var cobro = $('input[name="cobro"]').val();
    var obs = $('input[name="obs"]').val();

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
                html +=  '<td></td>';
                html +=  '<td class="text-center">'+fecha+'</td>';
                html +=  '<td>'+cobro+'</td>';
                html +=  '<td>'+obs+'</td>';
                html +=  '<td class="text-center"><i class="fa fa-times"></i></td>';
                html +='</tr>';
                //guardado();
                $('#tabla-cobro').find('> tbody:last-child').append(html);
                $('input[name="fecha"]').val('');
                $('input[name="cobro"]').val('');
                $('input[name="obs"]').val('');
                $('.alert-modal').text('INSERTADO CORRECTAMENTE!!');
                $('#print-receipt').show();
                $('#input-date').val(fecha);
                $('#input-concept').val(obs);
                $('#input-cost').val(cobro);
            }else{
                $('.alert-modal').text('Error en los datos!!');
                $('#print-receipt').hide();
            }
        },
        error: function (jqXHR,estado,error){
            console.log(estado);
            console.log(error);
        }
    });
});

/*--------Imprime los recibos AJAX--------*/
$('form[name="form-print-receipt"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    //var url = window.location.pathname;

    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp > 0){
                //guardado();
                $('.alert-modal').text('RECIBO IMPRESO CORRECTAMENTE!!');
                $('#print-receipt').hide();
                $('#input-date').val('');
                $('#input-concept').val('');
                $('#input-cost').val('');
            }else{
                $('.alert-modal').text('Error al imprimir!!');
            }
        },
        error: function (jqXHR,estado,error){
            console.log(estado);
            console.log(error);
        }
    });

    $('#modal-print-receipt').modal('hide');
});

/*--------Inserta los cobros de los pedidos AJAX--------*/
$('form[name="form-debt"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    //var url = window.location.pathname;

    var fecha = $('input[name="fecha-deuda"]').val();
    var cobro = $('input[name="monto-deuda"]').val();
    var obs = $('input[name="obs-deuda"]').val();

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
                html +=  '<td></td>';
                html +=  '<td class="text-center">'+fecha+'</td>';
                html +=  '<td>'+cobro+'</td>';
                html +=  '<td>'+obs+'</td>';
                html +=  '<td class="text-center"><i class="fa fa-times"></i></td>';
                html +=  '<td class="text-center"><i class="fa fa-money"></i></td>';
                html +='</tr>';
                //guardado();
                $('#tabla-deuda').find('> tbody:last-child').append(html);
                $('input[name="fecha-deuda"]').val('');
                $('input[name="monto-deuda"]').val('');
                $('input[name="obs-deuda"]').val('');
                $('.alert-modal').text('INSERTADO CORRECTAMENTE!!');
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

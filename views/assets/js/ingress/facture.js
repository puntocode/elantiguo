/**
 * Created by Fede on 11/07/2017.
 */

$('#sl-customer').select2();
$("#switch").bootstrapSwitch({
    onColor: 'success',
    onText: '<i class="fa fa-money"></i>',
    offText:'<i class="fa fa-file-text-o"></i>'
}).on('switchChange.bootstrapSwitch', function (event, state) {
    if($(this).is(':checked')) {
        $('#tipoingress').val("M");
    } else {
        $('#tipoingress').val("D");
    }
});


$('form[name="detalle-activo"]').on('submit', function(e){
   // e.preventDefault();
    var fecha = $('#fecha_factura').val();
    var idCliente = $('#sl-customer').val();
    var nroFactura = $('#nro_factura').val();
    //alert(fecha+"/"+idCliente+"/"+nroFactura);
    $('input[name="fecha"]').val(fecha);
    $('input[name="idcliente"]').val(idCliente);
    $('input[name="nrofactura"]').val(nroFactura);
   // alert( $('input[name="fecha"]').val()+"/"+$('input[name="idcliente"]').val()+"/"+$('input[name="nrofactura"]').val());
});


/*--------Agregar Cliente--------*/
$('form[name="insert-customer"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    var customer = $('input[name="customer"]').val();
    // alert(pet+met+provider);

    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp > 0){
                //guardado();
                $('#sl-customer').append($('<option>', {
                    value: resp,
                    text: customer
                })).val(resp).change();
                $('#modal-add-customer').modal('toggle');
                $('input[name="customer"]').val('');
                $('input[name="ruc"]').val('');
                $('input[name="city"]').val('');
                $('input[name="phone"]').val('');
                $('input[name="cellphone"]').val('');
                $('input[name="address"]').val('');
                $('input[name="mail"]').val('');
                $('input[name="contact"]').val('');
            }else{
                alert('Error en los datos del proveedor: Repetido o incorrecto!!')
            }
            console.log(resp);
        },
        error: function (jqXHR,stado,error){
            console.log(estado);
            console.log(error);
        }
    });
});
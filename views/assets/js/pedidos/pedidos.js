$('.side-menu li:eq(17)').addClass('active');
$("#sl-customer").select2({
    dropdownParent: $('#new-order')
});

/*--------Agrega Cliente--------*/
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


//Confirmar Cobro
$('.entregar').on('click', function(){
    var id = $(this).attr("id");
    $("input[name='idpedidos']").val(id);
});

$("#print").on('click', function (){
    $(".card").printArea();
});
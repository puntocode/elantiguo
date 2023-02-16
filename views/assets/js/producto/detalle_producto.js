$('.side-menu li:eq(18)').addClass('active');
$("#sl-stock").select2();

//Formato moneda input
$('.moneda').keyup(function () {
    var strn = $(this).val();
    if (strn != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    }else{
        var nocero = strn.replace(/\./g, '');
        var num = parseInt(nocero);
        $(this).val(num.toLocaleString('es-PY'));
        //alert(strn);
    }
});

//cuando cambia de material cambia de precio
$('#sl-stock').change(function () {
    var idstock = $('#sl-stock').val();
    //alert(idstock);
    $('#sl-stock-cost').val(idstock).change();
});


/*--------Agregar Stock--------*/
$('form[name="add-stock"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    var stock = $('input[name="producto"]', this).val();
    var cost = $('input[name="costo"]', this).val();

    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp > 0){
                //guardado();
                $('#sl-stock').append($('<option>', {
                    value: resp,
                    text: stock
                })).val(resp).change();
                $('#sl-stock-cost').append($('<option>', {
                    value: resp,
                    text: cost
                })).val(resp).change();
                $('#modal-add-stock').modal('toggle');
                $('input[name="stock"]').val("");
                $('input[name="producto"]').val("");
                $('input[name="ubicacion"]').val("");
                $('input[name="unidad"]').val("");
                $('input[name="minimo"]').val("");
                $('input[name="costo"]').val("");
            }else{
                alert('Error en los datos del proveedor!!')
            }
            //console.log(resp);
        },
        error: function (jqXHR,stado,error){
            console.log(estado);
            console.log(error);
        }
    });
});
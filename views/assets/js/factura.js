/**
 * Created by FedeXavier on 20/03/2017.
 */
$('.side-menu li:eq(8)').addClass('active');
$('.side-menu li:eq(9)').addClass('active2');
$("#sl-provider").select2();
//$("#sl-stock").select2();

/*--------Inserta la Cabecera de la Factura AJAX--------*/
$('form[name="pasivo"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');


    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp){
                //guardado();
                $('input[name="date"]').prop('disabled', true);
                $('select[name="provider"]').prop('disabled', true);
                $('input[name="fac-number"]').prop('disabled', true);
                $('input[name="quantity"]').prop('disabled', false);
                $('input[name="stock"]').prop('disabled', false);
                $('select[name="tax"]').prop('disabled', false);
                $('input[name="cost"]').prop('disabled', false);
                $('input[name="idpasivo"]').val(resp);
                $('#btn-add-provider').prop('disabled', true);
                $('#btn-add-stock').prop('disabled', false);
                $('.btn-insert-stock').attr('disabled', false);
                $('input[name="descuento"]').prop('disabled', false);
                $('#btn-end-facture').prop('disabled', false);
            }else{
                alert('Error en los datos de la cabecera!!')
            }
            console.log(resp);
        },
        error: function (jqXHR,stado,error){
            alert(error)
            console.log(estado);
            console.log(error);
        }
    });
 });

/*--------Inserta los detalles de la Factura AJAX--------*/
$('form[name="detalle-pasivo"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    var url = window.location.pathname;

    var cantidad = $('input[name="quantity"]').val();
    var costo = $('input[name="cost"]').val();
    var producto = $('input[name="stock"]').val();

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
                html +=  '<td>'+cantidad+'</td>';
                html +=  '<td>'+producto+'</td>';
                html +=  '<td>'+costo+'</td>';
                html +=  '<td>'+totalN+'</td>';
                //html +=  '<td class="text-center basurero"><i class="fa fa-trash-o delete"><input name="id-detalle" type="hidden" value="'+resp+'"></i></td>';
                html +=  '<td class="text-center"><a class="basurero" href="'+url+'/delete-detalle-pasivo/'+resp+'"><i class="fa fa-trash-o delete"></i></a></td>';
                html +='</tr>';
                //guardado();
                $('#tabla-factura > tbody:last-child').append(html);
                $('input[name="quantity"]').val('');
                $('input[name="cost"]').val('');
                $('input[name="stock"]').val('');
                $('input[name="quantity"]').focus();

               /*--------Eliminar detalle Factura--------*/
                $('.basurero').on('click', function(e) {
                    e.preventDefault();
                    var href = $(this).attr('href');
                    $(this).load(href, function (response, status, xhr){
                        console.log(response);
                        if(response=="true"){
                            $(this).parents("tr").remove();
                           // fila.remove();
                        }
                    });
                    e.stopImmediatePropagation();
                });
            }else{
                alert('Error en los datos!!')
            }
        },
        error: function (jqXHR,estado,error){
            console.log(estado);
            console.log(error);
        }
    });

    var id = $('input[name="idpasivo"]').val();
    $('#total-fac').load(url+"/total-factura", {idpasivo:id}, function (response, status, xhr){
        console.log(response);
    });

});

/*--------Agregar Proveedor--------*/
$('form[name="add-provider"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    var provider = $('input[name="provider"]').val();
    //alert(pet+met+provider);

    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp > 0){
                //guardado();
                $('#sl-provider').append($('<option>', {
                    value: resp,
                    text: provider
                }));
                $("#sl-provider").val(resp).change();
                $('#modal-add-provider').modal('toggle');
                $('input[name="provider"]').val('');
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

/*--------Agregar Stock--------*/
$('form[name="add-stock"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    var stock = $('input[name="producto"]', this).val();

    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp > 0){
                //guardado();
                $('#list-stock').append("<option value='" + stock + "'>");
                $('#modal-add-stock').modal('toggle');
                $('input[name="stock"]').val(stock);
                $('input[name="producto"]').val("");
                $('input[name="ubicacion"]').val("");
                $('input[name="unidad"]').val("");
                $('input[name="minimo"]').val("");
            }else{
                alert('Error al cargar en el stock!!')
            }
            //console.log(resp);
        },
        error: function (jqXHR,stado,error){
            console.log(estado);
            console.log(error);
        }
    });
});


$('input[name="descuento"]').keypress(function(e) {
    if (e.which == 13) {
        var descuento = $(this).val();
        $('#descuento').load('/elantiguo/cargar-descuento', {descuento:descuento}, function (response, status, xhr){
        //$('#descuento').load('/cargar-descuento', {descuento:descuento}, function (response, status, xhr){
            $('input[name="descuento"]').val('');
        });
    }
});

/*--------Terminar Factura--------*/
$('#btn-end-facture').on('click', function() {
    location.reload();
});


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


//------------------NEW FORMAT-------------------------------------------------------------------------
const pasivo = {
    descuento: 0,
    nro_factura: '',
    fecha: '',
    detallePasivo: []
}

$('#input-descuento').on('keypress',function(e) {
    if(e.which == 13) {
        addDiscount();
    }
});

$('#save-discount').on('click',function(e) {
    addDiscount();
});


$('#costo').on('keypress',function(e) {
    if(e.which == 13) {
        addCost();
    }
});

$('#save').on('click', function(e){
    const fecha = $('#fecha').val();
    const nroFac = $('input[name="nro_factura"]').val();
    console.log(fecha, nroFac);

    if(!fecha || !nroFac || !pasivo.detallePasivo.length){
        alertError('La fecha, el Nro. de Factura o el detalle de la Factura no pueden estar vacíos');
        return;
    }

    Swal.fire({
        title: 'Deseas terminar esta factura?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#5cb85c',
      }).then((result) => {
        if (result.isConfirmed) {
            pasivo.fecha = fecha;
            pasivo.nro_factura = nroFac;
            pasivo.idproveedor = $('#sl-provider').val();
            saveFacture();
        } 
      })
})

$(document).on('click', '.delete', function() {
    const product = $( this ).parent().parent().find(".product").text();
    const index = pasivo.detallePasivo.findIndex( detail => detail.producto === product);
    pasivo.detallePasivo.splice(index, 1)
    $( this ).parent().parent().remove();
    calTotal();
    console.log(pasivo);
});

function addCost(){
    const cantidad = $('input[name="cantidad"]').val();
    const precio =  ($('input[name="costo"]').val()).replaceAll('.',"");
    const product = $('#producto').val();
    const tax = $("#impuesto").val();
    
    const costo = Number(precio);
    const total = cantidad * costo;
    const impuesto = total/tax;
    const producto = product.trim();
    
    console.log(cantidad, costo, precio, producto, impuesto, total);
    
    if(!cantidad || !producto || !impuesto || !costo){
        alert('No puede dejar datos vacíos');
        return;
    }
    
    pasivo.detallePasivo.push({cantidad, producto, impuesto, costo, total});
    console.log(pasivo);

    addTable(cantidad, producto, costo, total);
    calTotal();
    clearCost();
}


function addTable(cantidad, producto, costo, total){
    let html = `<tr>
        <td class="text-center">${cantidad}</td>  
        <td class="product">${producto}</td>  
        <td class="text-right">${costo.toLocaleString('es-PY')}</td>  
        <td class="text-right">${total.toLocaleString('es-PY')}</td>  
        <td class="text-center"><i class="fa fa-trash-o delete"></i></td>
    </tr>`;

    $("#table-body").prepend(html);
}


function clearCost(){
    $('input[name="cantidad"]').val('');
    $('input[name="costo"]').val('');
    $('#producto').val('');
    $('input[name="cantidad"]').focus();
}


function calTotal(){
    const impuesto = pasivo.detallePasivo.reduce((impuesto, detail) => { 
            return impuesto + detail.impuesto;
    }, 0);

    const subTotal = pasivo.detallePasivo.reduce((total, detail) => { 
            return total + detail.total;
    }, 0);

    const total = subTotal - pasivo.descuento;

    $('#impTotal').html(impuesto.toLocaleString('es-PY'));
    $('#subtotal').html(subTotal.toLocaleString('es-PY'));
    $('#total').html(total.toLocaleString('es-PY'));
}


function addDiscount(){
    const discount = $('#input-descuento').val();

    if(!discount){
        alert('por favor ingrese un descuento válido');
        return;
    }

    pasivo.descuento = Number(discount.replace('.',""));
    $('#td-descuento').html(discount);
    calTotal();
    $('#modalDescuento').modal('toggle');
}

function alertError(text){
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text,
        confirmButtonColor: '#f27474'
      })
}

function alertSuccess(text, title){
    Swal.fire({
        icon: 'success',
        title,
        text,
        confirmButtonColor: '#53a553'
      }).then((result) => {
        location.reload();
      })
}

//----------------------Guardar en la BD----------------------------------------------------
async function saveFacture(){
    try{
        const response = await axios.post('/egress/insert', pasivo);
        const result = await response.data;
	    alertSuccess('La factura ha sido registrada correctamente', 'Factura Cargada')
	}catch(error){
	    alertError(error);
	}
}
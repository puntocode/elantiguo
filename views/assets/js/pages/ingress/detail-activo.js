$('form[name="detalle-activo"]').submit(function(e){
    e.preventDefault();

    var idActivo =  $('input[name="id-activo"]').val();
    var cantidad = $('input[name="cantidad"]').val();
    var costo = $('input[name="costo"]').val();
    var producto = $('input[name="producto"]').val();
    var url = $(this).attr('action');

    $('#div-none').load(url, {idactivo:idActivo,costo:costo,cantidad:cantidad,producto:producto}, function (response, status, xhr){
        if(response > 0){
            //tFooter();
            tBody(cantidad,costo,producto,response,idActivo);
            tFooter(idActivo);
        }else{
            alert('Error al insertar los datos!!');
        }
    });
});

$('form[name="update-descuento"]').submit(function(e){
    e.preventDefault();
   
    var idActivo = $('#id-activo').val();
    var discount =  $('input[name="discount"]').val();
    var numberInt = discount.replace(/\./g, '');
    var number = parseInt(numberInt);
    var url = $(this).attr('action');

    $('#discount').load(url, {id:idActivo,discount:number}, function (response, status, xhr){
        if(response > 0){
            tFooter(idActivo);
        }else{
            alert('Error al cargar el descuento!!');
        }
    });
});

function tBody(cantidad,costo,producto,idDetalle,idActivo){
     //habilita detalle activo
     $('.detail-activo').val("");
     $('input[name="cantidad"]').focus();

    var nocero = costo.replace(/\./g, '');
    var num = parseInt(nocero);
    var total = num*cantidad;
    var totalN = total.toLocaleString('es-PY');
    var rutaDel = +idActivo+'-'+idDetalle;

     //carga la tabla
     var html;
     html = '<tr>';
     html +=  '<td>'+cantidad+'</td>';
     html +=  '<td>'+producto+'</td>';
     html +=  '<td>'+costo+'</td>';
     html +=  '<td>'+totalN+'</td>';
     html +=  '<td class="text-center"><a href="#" data-href="/elantiguo/detalle-activo/eliminar-producto/'+rutaDel+'" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times delete"></i></a></td>';
     html +='</tr>';
     $('#tabla-factura tbody').append(html);

}

function tFooter(idActivo){
    
    //var url = "/sum-detalle-activo";
    var url = "/elantiguo/sum-detalle-activo";
    $('#div-none').load(url, {id:idActivo}, function (response, status, xhr){
        var totalJson = JSON.parse (response);
        console.log(totalJson);

        $('#sub-total').text(totalJson.subtotal);
        $('#discount').text(totalJson.discount);
        $('#total').text(totalJson.total);
        $('#iva').text(totalJson.iva);
        
    });
}


 $('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    var id =  $(this).find('.btn-ok').attr('href');
});
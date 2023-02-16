$('.side-menu li:eq(19)').addClass('active');
$('.side-menu li:eq(24)').addClass('active2');
$('#cerrar_modal').click(function() {
    location.reload();
});

/*----------------------------------Insertar Sueldo--------------------------------------------------*/
$('form[name="insert-salary"]').on('submit', function(e){
    e.preventDefault();
    var pet = $(this).attr('action');
    var met = $(this).attr('method');
    //var customer = $('input[name="customer"]').val();
   // alert(pet+met+provider);

    $.ajax({
        beforeSend: function(){

        },
        url : pet,
        type: met,
        data: $(this).serialize(),
        success: function(resp){
            if(resp > 0){
                $('input[name="total"]').val('');
                $('#message').html("Insertado correctamente");
            	$('input[name="concepto"]').val('').focus();
            }else{
                alert('Error en los datos!!');
            }
            //console.log(resp);
        },
        error: function (jqXHR,stado,error){
            console.log(estado);
            console.log(error);
        }
    });
});

$('#table-salary tbody').on('click', 'a[data-target="#delete"]', function () {
    var btn = this;
    var test = $(this).attr('data-href');

    swal({
        title: "Desea eliminar este sueldo?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "ELIMINAR!",
        cancelButtonText: "CANCELAR",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            swal("Eliminado!", "Eliminado correctamente", "success");

            $.post( test, { id: btn.id }).done(function( data ) {
                $(btn).parents('tr').remove();
            });

        } else {
            swal("Cancelado", "", "error");
        }
    });
});


function deleteSalary() {

    $('#none').load('/appmapa/dashboard/date/makers', {desde: inicio, hasta: fin}, function (response, status, xhr) {

        var json = JSON.parse(response);

        $.each(json, function (key, data) {
            var latLng = new google.maps.LatLng(data.latitud, data.longitud);

            var idAccidente = data.id_accidente;
            var fecha = data.fecha;
            var obs = data.observacion;
            var direccion = data.direccion;
            var urlDetalle = "/appmapa/dashboard/detalle-accidente/" + idAccidente + "/" + inicio + "/" + fin;


            var accidente = '<div id="content-map">' +
                '<div><a href="' + urlDetalle + '">ACCIDENTE Nº: ' + idAccidente + '</a></div>' +
                '</div>' +
                '<div id="body">' +
                '<ul id="detalle-mapa">' +
                '<li><strong>Fecha: </strong>' + fecha + '</li>' +
                '<li><strong>Dirección: </strong>' + direccion + '</li>' +
                '<li><strong>Detalle: </strong>' + obs + '</li>' +
                '</ul>' +
                '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: accidente
            });
            // Creating a marker and putting it on the map
            var marker = new google.maps.Marker({
                position: latLng,
                icon: image,
                title: 'Accidente'
            });

            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });
            marker.setMap(map);
        });

    });
}

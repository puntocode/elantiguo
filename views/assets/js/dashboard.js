$(function(){
    $(".navbar-expand-toggle").click(function() {
        $(".app-container").toggleClass("expanded");
        return $(".navbar-expand-toggle").toggleClass("fa-rotate-90");
    });
    return $(".navbar-right-expand-toggle").click(function() {
        $(".navbar-right").toggleClass("expanded");
        return $(".navbar-right-expand-toggle").toggleClass("fa-rotate-90");
    });
});

//select all
$("input").focus(function(){
    this.select();
});

var urlNoti = '/cantidad-deuda';
//var urlNoti = '/elantiguo/cantidad-deuda';


$("#cant-deuda").load(urlNoti,{},function(response, status, xhr){
    var cantidad = response;
    
    if(cantidad>0){
        $("#mail").show();
        
        
        $.getJSON("/elantiguo/dias-deudas", function (result) {
        //$.getJSON("/dias-deudas", function (result) {
            var data = result;
                
            for(var i in data) {
                //console.log(data[i].fecha);

                if (data[i].fecha == $('#hoy').text()) {
                    $('.hoy .no-deuda').hide();
                    var html;
                    html = '<a href="detalle-pedido/'+data[i].idpedidos+'" class="line-bottom">';
                    html += 	'<div>';
                    html += 		'<div class="text-truncate font-weight-bold"><span class="fa fa-circle text-danger"></span> '+data[i].cliente+'</div>';
                    html +=         '<div class="small text-muted text-truncate">'+data[i].detalle+'</div>';
                    html +=         '<div class="monto">Monto pago: '+data[i].deuda+'</div>';
                    html += 	'</div>';
                    html += '</a>';
                    $('.hoy').append(html);
                }

                if (data[i].fecha == $('#tomorrow').text()) {
                    $('.tomorrow .no-deuda').hide();
                    var html;
                    html = '<a href="detalle-pedido/'+data[i].idpedidos+'" class="line-bottom">';
                    html += 	'<div>';
                    html += 		'<div class="text-truncate font-weight-bold"><span class="fa fa-circle text-danger"></span> '+data[i].cliente+'</div>';
                    html +=         '<div class="small text-muted text-truncate">'+data[i].detalle+'</div>';
                    html +=         '<div class="monto">Monto pago: '+data[i].deuda+'</div>';
                    html += 	'</div>';
                    html += '</a>';
                    $('.tomorrow').append(html);
                }

                if (data[i].fecha == $('#after-tomorrow').text()) {
                    $('.after-tomorrow .no-deuda').hide();
                    var html;
                    html = '<a href="detalle-pedido/'+data[i].idpedidos+'" class="line-bottom">';
                    html += 	'<div>';
                    html += 		'<div class="text-truncate font-weight-bold"><span class="fa fa-circle text-danger"></span> '+data[i].cliente+'</div>';
                    html +=         '<div class="small text-muted text-truncate">'+data[i].detalle+'</div>';
                    html +=         '<div class="monto">Monto pago: '+data[i].deuda+'</div>';
                    html += 	'</div>';
                    html += '</a>';
                    $('.after-tomorrow').append(html);
                }
            }

        });
    }
});


$('#noti-message').on('click', function (e) {
    e.preventDefault();

    //$(this).load("/dias-deudas/correo",function(response, status, xhr){
    $(this).load("/elantiguo/dias-deudas/correo",function(response, status, xhr){
            console.log(response);
        if(response>0){
            $('#noti-message').text("Correo enviado correctamente!!")
        }else {
            $('#noti-message').text("Error al enviar!!")
        }
    });
});
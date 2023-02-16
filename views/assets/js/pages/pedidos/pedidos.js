$('.side-menu li:eq(17)').addClass('active');

//Confirmar Cobro
$('.entregar').on('click', function(){
    var id = $(this).attr("id");
    $("input[name='idpedidos']").val(id);
});

$("#print").on('click', function (){
    $(".card").printArea();
});
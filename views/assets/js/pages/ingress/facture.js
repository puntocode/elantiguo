/**
 * Created by Fede on 11/07/2017 
 * v2 .27/11/2019
 */

$('#sl-customer').select2();

/*-------------------------------------Insert Activo------------------------------------------------------*/
$('form[name="insert-activo"]').on('submit', function(e){
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
                $('#sl-customer').prop( "disabled", true );
                $('input[name="date"]').prop( "disabled", true );
                $('#sl-type').prop( "disabled", true );
                $('input[name="fac-number"]').prop( "disabled", true );
                $('input[name="observations"]').prop( "disabled", true );
                $('input[name="submit"]').prop( "disabled", true );
                
                //habilita detalle activo
                $('input[name="id-activo"]').val(resp);
                $('.detail-activo').prop( "disabled", false );
                $('.discount').prop( "disabled", false );
                $('input[name="btn-insert-detalle"]').prop( "disabled", false );
                $('input[name="cantidad"]').focus();
            }else{
                alert('Error: datos incorrectos!!')
            }
            console.log(resp);
        },
        error: function (jqXHR,stado,error){
            console.log(estado);
            console.log(error);
        }
    });
});




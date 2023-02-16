/**
 * Created by FedeXavier on 05/05/2017.
 */
$('.side-menu li:eq(18)').addClass('active');

//Modal form update producto
$('.update-product').on('click', function () {
    var idProducto = $('input[name="id"]', this).val();
    var category = $(this).parents("tr").find(".categoria").html();
    var producto = $(this).parents("tr").find(".producto").html();
    var valorVenta = $(this).parents("tr").find(".valor").html();

    $('#up-id-product').val(idProducto);
    $('input[name="up-product"]').val(producto);
    $('input[name="up-cost"]').val(valorVenta);
    $('#sl-category').filter(function() {
        return this.text == category;
    }).attr('selected', true);
});

//Modal form update imagen
$('.update-image').on('click', function () {
    var idProducto = $(this).attr("id");
    $('input[name="up-id-product-img"]').val(idProducto);
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

//select all
$("input[type=text]").focus(function(){
    this.select();
});
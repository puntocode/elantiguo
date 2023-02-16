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


//Confirmar Eliminar
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    var id =  $(this).find('.btn-ok').attr('href');
});
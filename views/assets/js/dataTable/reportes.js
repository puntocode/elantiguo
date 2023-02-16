/**
 * Created by FedeXavier on 23/02/2017.
 */
/*$(window).on('load', function(){
    // Animate loader off screen
    $(".loader2").fadeOut("slow");
});*/
$('.datatable').dataTable({
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'excel',
            text: 'EXCEL',
            className: 'btn-pdf',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'pdfHtml5',
            download: 'open',
            className: 'btn-pdf',
            pageSize: 'LEGAL',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'colvis',
            text: 'COLUMNAS',
            className: 'btn-pdf'
        }
    ]
}, $(".datatable").fadeIn("slow"));
/**
 * Created by Fede on 15/09/2017.
 */

$('.side-menu li:eq(17)').addClass('active');

today = new Date();
var url = window.location.pathname;
var hostname = "/detalle-pedido/";

    /*y = today.getFullYear();
    m = today.getMonth();
    d = today.getDate();

    var obj = jQuery.parseJSON( '{ "title": "Masaje", "start":"2016-07-14 00:00:00", "paciente":"Fede Lopez - 0981.789483" }' );
    console.log(obj);*/

$('#calendar').fullCalendar({
    header:{
        left:   'today prev,next',
        center: 'title',
        right:  'month,listWeek,listDay'
    },
    views: {
        listWeek: { buttonText: 'Semana' },
        listDay: { buttonText: 'Día' }
    },
    eventColor: '#FA2A00',
    eventTextColor: '#fff',
    eventRender: function(event, element) {
        element.popover({
            title: event.title,
            placement:'auto',
            html:true,
            trigger : 'click',
            animation : 'true',
            content: event.content + ' <a href="'+hostname+event.idpedidos+'#deudas" class="btn-sm btn-success cobro">PAGAR</a>',
            container:'body'
        });
        $('body').on('click', function (e) {
            if (!element.is(e.target) && element.has(e.target).length === 0 && $('.popover').has(e.target).length === 0)
                element.popover('hide');
        });
    },
    eventDrop: function(event, delta, revertFunc) { // si changement de position
        edit(event);
    },
    eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur
        edit(event);
    },
    events: {
        url: url+'/datos-calendario',
        cache: true
    }
});




/**
 * Created by FedeXavier on 14/07/2016.
 */
/*$(document).ready(function() {

    today = new Date();
    y = today.getFullYear();
    m = today.getMonth();
    d = today.getDate();
    var url = window.location.pathname+ '/datos-calendario';

    $('#calendar').fullCalendar({
        header: {
            left: 'title',
            center: 'month,agendaWeek,agendaDay',
            right: 'prev,next today'
        },
        defaultDate: today,
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        select: function(start, end) {
            $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
            $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
            $('#ModalAdd').appendTo("body").modal('show');
        },
        eventRender: function(event, element) {
            element.bind('dblclick', function() {
                $('#ModalEdit #id').val(event.id);
                $('#ModalEdit #titleEdit').val(event.title);
                $('#ModalEdit #colorEdit').val(event.className);
                $('#ModalEdit').appendTo("body").modal('show');
            });
            element.popover({
                title: "Cita",
                placement:'auto',
                html:true,
                trigger : 'click',
                animation : 'true',
                content: event.paciente,
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
            url: url,
            cache: true
        }

    });


});*/


/*
$(document).ready(function() {

    today = new Date();
    y = today.getFullYear();
    m = today.getMonth();
    d = today.getDate();

    var obj = jQuery.parseJSON( '{ "title": "Masaje", "start":"2016-07-14 00:00:00", "paciente":"Fede Lopez - 0981.789483" }' );
    console.log(obj);

    $('#calendar').fullCalendar({
        header: {
            left: 'title',
            center: 'month,agendaWeek,agendaDay',
            right: 'prev,next today'
        },
        defaultView: 'agendaDay',
        defaultDate: today,
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        select: function(start, end) {

            $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
            $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
            $('#ModalAdd').appendTo("body").modal('show');
        },
        eventRender: function(event, element) {
            element.bind('dblclick', function() {
                $('#ModalEdit #id').val(event.id);
                $('#ModalEdit #title').val(event.title);
                $('#ModalEdit #color').val(event.color);
                $('#ModalEdit').appendTo("body").modal('show');
            });
            element.popover({
                title: "Cita",
                placement:'auto',
                html:true,
                trigger : 'click',
                animation : 'true',
                content: event.paciente,
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

        events: [
            {
                title: 'All Day Event',
                start: new Date(y, m, 14),
                paciente: 'Jose Perez - 0981.'
            },
            {
                id: 999,
                title: 'Repeating Event',
                start: new Date(y, m, d-4, 6, 0),
                allDay: false,
                className: 'event-blue'
            },
            {
                id: 999,
                title: 'Repeating Event',
                start: new Date(y, m, d+3, 6, 0),
                allDay: false,
                className: 'event-blue'
            },
            {
                title: 'Meeting',
                start: new Date(y, m, d-1, 10, 30),
                allDay: false,
                className: 'event-green'
            },
            {
                title: 'Lunch',
                start: new Date(y, m, d+7, 12, 0),
                end: new Date(y, m, d+7, 14, 0),
                allDay: false,
                className: 'event-red'
            },
            {
                title: 'LBD Launch',
                start: new Date(y, m, d-2, 12, 0),
                allDay: true,
                className: 'event-azure'
            },
            {
                title: 'Birthday Party',
                start: new Date(y, m, d+1, 19, 0),
                end: new Date(y, m, d+1, 22, 30),
                allDay: false,
            },
            {
                title: 'Click for Creative Tim',
                start: new Date(y, m, 21),
                end: new Date(y, m, 22),
                url: 'http://www.creative-tim.com/',
                className: 'event-orange'
            },
            {
                title: 'Click for Google',
                start: new Date(y, m, 21),
                end: new Date(y, m, 22),
                url: 'http://www.creative-tim.com/',
                className: 'event-orange'
            }
        ]
});

*/


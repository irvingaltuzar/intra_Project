var carouselGallery = document.getElementById('carouselGallery');
carouselGallery.addEventListener('slid.bs.carousel', function () {
  $('.gallery .active-slide').text($('#carouselGallery div.active').index() + 1);
});

$(document).ready(function(){
	$('.gallery .total').text($('.carousel-item').length);
});

function generateEventsToCalendar(){
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      locale: 'es',
      initialDate: ths.current_date,
      navLinks: true, // can click day/week names to navigate views
      selectable: false,
      selectMirror: true,
      select: function(arg) {
        var title = prompt('Event Title:');
        if (title) {
          calendar.addEvent({
            title: title,
            start: arg.start,
            end: arg.end,
            allDay: arg.allDay
          })
        }
        calendar.unselect()
      },
      eventClick: function(arg) {
        ths.searchEvent(arg.event._def.extendedProps.event_id);
      },
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events:ths.general_data.calendar_events
    });

    calendar.render();
  });
}

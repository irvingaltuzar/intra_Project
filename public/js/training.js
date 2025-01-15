var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();
today = yyyy + '-' + mm + '-' + dd;
//today = new Date(yyyy,mm,dd)

document.addEventListener('DOMContentLoaded', function() {
    var calendarTraining = document.getElementById('calendar-training');

    var calendar = new FullCalendar.Calendar(calendarTraining, {
      headerToolbar: {
        left: 'title',
        center: 'dayGridMonth,timeGridWeek,timeGridDay',
        right: 'prev,next today'
      },
      locale: 'es',
      initialDate: today,
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectMirror: true,
      select: function(arg) {
        var title = prompt('Nombre de la cita:');
        if (title) {
          calendar.addEvent({
            title: title,
            start: arg.start,
            end: arg.end,
            allDay: arg.allDay
          })
        }
        //calendar.unselect()
		$('#modal_training').modal('show');
      },
      eventClick: function(arg) {
        if (confirm('¿Está seguro de que desea eliminar esta cita?')) {
          arg.event.remove()
        }
		$('#modal_training').modal('show');
      },
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: [
      	{
      		title:"All Day Event",
      		start:new Date(2022,1,1),
      		className:"event-orange"
      	},
      	{
      		title:"Click for Creative Tim",
      		start:new Date(2022,1,21),
      		end:new Date(2022,1,22),url:"http://www.creative-tim.com/",
      		className:"event-orange"
      	},
      	{
      		title:"Click for Google",
      		start:new Date(2022,1,21),
      		end:new Date(2022,1,22),url:"http://www.creative-tim.com/",
      		className:"event-orange"
      	},
      	{
      		title:"Repeating Event",
      		start:new Date(2022,1,18,6,0),
      		allDay:false,
      		className:"event-rose"
      	},
      	{
      		title:"Md-pro Launch",
      		start:new Date(2022,1,29,12,0),
      		allDay:true,
      		className:"event-azure"
      	},
      	{
      		title:"Meeting",
      		start:new Date(2022,1,30,10,35),
      		end:new Date(2022,1,30,12,35),
      		allDay:false,
      		className:"event-green"
      	},
      	{
      		title:"Birthday Party",
      		start:new Date(2022,2,1,19,0),
      		end:new Date(2022,2,1,22,30),
      		allDay:false,
      		className:"event-azure"
      	},
      	{
      		title:"Repeating Event",
      		start:new Date(2022,2,3,6,0),
      		allDay:false,
      		className:"event-rose"
      	},
      	{
      		title:"Lunch",
      		start:new Date(2022,2,7,12,0),
      		end:new Date(2022,2,7,14,0),
      		allDay:false,
      		className:"event-red"
      	},
      	{
      		title:"Lunch",
      		start:new Date(2022,2,8,12,0),
      		end:new Date(2022,2,8,14,0),
      		allDay:false,
      		//className:"event-red"
      	},
		{
			title:"Repeating Eventttt",
      		start:new Date(2022,0,3,6,0),
      		allDay:false,
      		className:"event-rose"
		}
      ]
    });

    calendar.render();
});

$(function () {
	$('#datetimepicker1').datetimepicker();
});

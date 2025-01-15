var ths = this;
var general_data = {
	trainings:{},
	calendar_trainings:[]
}

/* Start - Temas de configuracion para el calendario */

function generateTrainingToCalendar(){
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar-training');

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
            allDay: arg.allDay,
            color:"#465565",
          })
        }
        calendar.unselect()
      },
      eventClick: function(arg) {
        ths.searchTraining(arg.event._def.extendedProps.training_id);
      },
      editable: false,
      dayMaxEvents: true, // allow "more" link when too many events
      events:ths.general_data.calendar_trainings,
      eventColor: '#465565'
    });

    calendar.render();
  });
  /* document.querySelector('.fc-event').style.background="#465565" */
}

/* End - Temas de configuracion para el calendario */

function addEventsToCalendar(){

	ths.general_data.trainings.forEach(training =>{

		let start_training = training.start_time != null ? (training.start_date+'T'+training.start_time) : training.start_date;
		ths.general_data.calendar_trainings.push({
			'title':training.title,
			'start':start_training,
            'training_id':training.id
		});

	})
}

function searchTraining(_training_id){

    ths.general_data.trainings.forEach( training =>{
        if(training.id == _training_id){
            ths.openModal(training);
        }
    })

}


function openModal(_training){
    $("#modal-training").modal("show");
    ths.visualisity_loading('loading',"modal_body");

    document.querySelector("#modal_title").innerHTML = _training.title;
    document.querySelector('#modal-description').innerHTML = _training.description != null ? _training.description : " - ";
    document.querySelector('#modal-date').innerHTML = _training.start_date != null ? _training.start_date : " - ";
    document.querySelector('#modal-time').innerHTML = _training.start_time != null ? _training.start_time : " - ";
    document.querySelector('#modal-place').innerHTML = _training.place != null ? _training.place : " - ";
    document.querySelector('#modal-link').innerHTML = _training.link != null ? _training.link : " - ";

    if(_training.photo != null){
        document.querySelector("#modal-img-full").src = url_base+'/'+_training.photo;
        document.querySelector("#modal-img-full").classList.remove("d-none")

    }else{
        document.querySelector("#modal-img-full").classList.add("d-none")
    }

    let link_html="";

    //Se dividen para agregar imagenes, videos y documentos
    document.querySelector("#list_files").innerHTML = "";
    _training.files.forEach( (img,key) =>{
        link_html += `
            <div class="col-md-4 col-sm-12">
                <a class="download_files text-link" target="_blank" href="${img.file}"><i class="fas fa-paperclip text-dmi"></i> ${img.name}</a>
            </div>
        `;

        document.querySelector("#list_files").innerHTML = link_html;
    })


    ths.visualisity_loading('data',"modal_body");

}

var ths = this;
var general_data = {
	type_events:{},
	events:{},
	calendar_events:[]
}

function generateEventType(){

	document.querySelector("#list_type_events").innerHTML = ""

	if(ths.general_data.type_events.length > 0 ){
		let type_events_HTML = "";
		ths.general_data.type_events.forEach(type =>{

			type_events_HTML += `<div class="about-colors fc-event" style="background-color:${type.color}">${type.name}</div>`;

		});

		document.querySelector("#list_type_events").innerHTML = type_events_HTML;

	}

}

function addEventsToCalendar(){



	ths.general_data.events.forEach(event =>{

		let start_event = event.time != null ? (event.date+'T'+event.time) : event.date;
		ths.general_data.calendar_events.push({
			'title':event.title,
			'start':event.date,
			'color':event.type_event.color,
            'event_id':event.id
		});

	})
}

function searchEvent(_event_id){

    ths.general_data.events.forEach( event =>{
        if(event.id == _event_id){
            ths.openEvent(event);
        }
    })

}


function openEvent(_event){
    $("#modal-event").modal("show");
    console.log(_event);
    ths.visualisity_loading('loading',"modal_body");

    let txt_locations ="";
    _event.bucket_location.forEach(bucket =>{
        txt_locations += bucket.location.name+" - ";

    })
    document.querySelector("#modal_title").innerHTML = _event.title;
    document.querySelector('#modal_location').innerHTML = txt_locations;
    document.querySelector('#modal-description').innerHTML = _event.description != null ? _event.description : " - ";
    document.querySelector('#modal-date').innerHTML = _event.date != null ? _event.date : " - ";
    document.querySelector('#modal-time').innerHTML = _event.time != null ? _event.time : " - ";
    document.querySelector('#modal-place').innerHTML = _event.place != null ? _event.place : " - ";

    //Cargar imagenes al carrusel del modal
    let img_html = "";
    document.querySelector("#modal-carrusel-inner").innerHTML ="";
    document.querySelector("#modal-carousel-indicators").innerHTML ="";

    let exist_photo = 0;
    let key = 0
    if(_event.photo != null){
        img_html = `
                    <div class="carousel-item active">
                        <img src="${ths.url_base+'/'+_event.photo}" class="d-block w-100" alt="...">
                    </div>
        `;
        $("#modal-carrusel-inner").append(img_html);
        $("#modal-carousel-indicators").append(`<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>`);
        exist_photo = 1;
        key = 1
    }else{
        exist_photo = 0;
        key = 0
    }


    let extension = ['mp4','webm','mpeg'];
    let img_and_video = ['png','jpeg','jpg','mp4','webm','mpeg'];
    let link_html="";

    //Se dividen para agregar imagenes, videos y documentos
    document.querySelector("#list_files").innerHTML = "";


    _event.files.forEach( (img) =>{
        if(img_and_video.indexOf(img.extension) != -1){

            if(extension.indexOf(img.extension) != -1){
                img_html = `
                        <div class="carousel-item ${exist_photo == 0 && key==0 ? 'active' : ''}">
                            <video controls="controls" class="d-block w-100 m-2 modal_video" >
                                <source src="${url_base+'/'+img.file}" alt="${img.name}"  type="video/${img.extension}">
                                Vídeo no es soportado...
                            </video>
                        </div>
                        `;
            }else{
                img_html = `
                        <div class="carousel-item ${exist_photo == 0 && key==0 ? 'active' : ''}">
                            <img src="${ths.url_base+'/'+img.file}" class="d-block w-100" alt="...">
                        </div>
                        `;
            }
        }else{
            link_html += `
                <div class="col-md-4 col-sm-12">
                    <a class="download_files text-link" target="_blank" href="${img.file}"><i class="fas fa-paperclip text-dmi"></i> ${img.name}</a>
                </div>
            `;
        }

        document.querySelector("#list_files").innerHTML = link_html;

        $("#modal-carrusel-inner").append(img_html);

        if(key == 0 && _event.photo == null){
            $("#modal-carousel-indicators").append(`<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>`);
            console.log("entra al if");

        }else{
            $("#modal-carousel-indicators").append(`<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="${key}" aria-label="Slide ${key}"></button>`);
        }

        key++;
    })

    if(_event.photo == null && _event.files == null){
        document.querySelector("#carrusel_portada").classList.add("d-none");
    }else{
        document.querySelector("#carrusel_portada").classList.remove("d-none");
    }

    document.querySelector("#modal-type-event").innerHTML= _event.type_event.name;
    document.querySelector("#modal-type-event").style.background= _event.type_event.color;

    ths.visualisity_loading('data',"modal_body");

}

$(document).ready(function() {
    $('#modal-event').on('hidden.bs.modal', function(e) {
        document.querySelector('.modal_video').src="";
    });
});

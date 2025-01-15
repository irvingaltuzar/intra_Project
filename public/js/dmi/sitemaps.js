var ths = this;
var general_data = {
    organization_back: {},
    title:'',

}
var organization_html ="";
var level = 0;
var sitemaps = [{
    "id": 1,
    "page_title": "Home",
    "description": "Description",
    "icon": `<i class="m-2 icon-sitemap fas fa-home"></i>`,
    "children": [
      {
        "id": 2,
        "page_title": "Inicio",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-home"></i>`,
        "children": []
      },
      {
        "id": 201,
        "page_title": "Sistemas Administrativos",
        "description": "Description",
        "icon": `<i class="fas fa-user-shield icon-sitemap"></i>`,
        "children": []
      },
      {
        "id": 3,
        "page_title": "Quienes somos",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-user-friends"></i>`,
        "children": [
            {
                "id": 4,
                "page_title": "Nuestro Manifiesto",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 6,
                "page_title": "Conoce nuestros Proyectos",
                "description": "Description",
                "icon":"",
                "children": [
                    {
                        "id": 5,
                        "page_title": "Nuestros Inicios",
                        "description": "Description",
                        "icon":"",
                        "children": []
                    },
                    {
                        "id": 5,
                        "page_title": "Línea del Tiempo",
                        "description": "Description",
                        "icon":"",
                        "children": []
                    },
                    {
                        "id": 5,
                        "page_title": "Arquitectura de Marcas",
                        "description": "Description",
                        "icon":"",
                        "children": []
                    },
                    {
                        "id": 5,
                        "page_title": "Nuestros Proyectos",
                        "description": "Description",
                        "icon":"",
                        "children": []
                    }
                ]
            },
            {
                "id": 7,
                "page_title": "Virtudes y Valores",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 8,
                "page_title": "Ética en la Empresa",
                "description": "Description",
                "icon":"",
                "children": []
            }

        ]
      },
      {
        "id": 9,
        "page_title": "Organigrama",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-sitemap"></i>`,
        "children": [
            {
                "id": 10,
                "page_title": "DMI Bienes Raíces",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 11,
                "page_title": "DMI Desarollo de Negocios",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 12,
                "page_title": "DMI Responsabilidad Social",
                "description": "Description",
                "icon":"",
                "children": []
            },
        ]
      },
      {
        "id": 13,
        "page_title": "Comunicados",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-bullhorn"></i>`,
        "children": [
            {
                "id": 14,
                "page_title": "Mensajes de Consejo",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 15,
                "page_title": "Movimientos Organizacionales",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 16,
                "page_title": "Campañas Institucionales",
                "description": "Description",
                "icon":"",
                "children": []
            },
        ]
      },
      {
        "id": 17,
        "page_title": "Colaboradores",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-users"></i>`,
        "children": [
            {
                "id": 18,
                "page_title": "Cumpleaños",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 19,
                "page_title": "Ascensos",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 20,
                "page_title": "Nuevos Ingresos",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 21,
                "page_title": "Aniversarios",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 22,
                "page_title": "Nacimientos",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 23,
                "page_title": "Condolencias",
                "description": "Description",
                "icon":"",
                "children": []
            },
        ]
      },
      {
        "id": 24,
        "page_title": "Noticias",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-newspaper"></i>`,
        "children": [
            {
                "id": 25,
                "page_title": "Fechas Conmemorativas",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 26,
                "page_title": "Posteo Interno",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 27,
                "page_title": "Encuestas",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 28,
                "page_title": "Avisos de Área",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 29,
                "page_title": "Políticas",
                "description": "Description",
                "icon":"",
                "children": []
            },
        ]
      },
      {
        "id": 30,
        "page_title": "Blog",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-blog"></i>`,
        "children": [
            {
                "id": 31,
                "page_title": "Revista Digital",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 32,
                "page_title": "Notas de Blog",
                "description": "Description",
                "icon":"",
                "children": []
            },
        ]
      },
      {
        "id": 33,
        "page_title": "Eventos",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-calendar"></i>`,
        "children": []
      },
      {
        "id": 36,
        "page_title": "Fundación DMI",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-hands"></i>`,
        "children": [
            /* {
                "id": 37,
                "page_title": "Cápsulas de Información",
                "description": "Description",
                "icon":"",
                "children": []
            }, */
            {
                "id": 38,
                "page_title": "Información General",
                "description": "Description",
                "icon":"",
                "children": []
            },
        ]
      },
      {
        "id": 39,
        "page_title": "Capacitación",
        "description": "Description",
        "icon":`<i class="m-2 icon-sitemap fas fa-chalkboard-teacher"></i>`,
        "children": []
      },
      {
        "id": 40,
        "page_title": "Beneficios y Prestaciones",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-hand-holding-usd"></i>`,
        "children": [
            {
                "id": 41,
                "page_title": "Beneficios",
                "description": "Description",
                "icon":"",
                "children": []
            },
            {
                "id": 42,
                "page_title": "Prestaciones",
                "description": "Description",
                "icon":"",
                "children": []
            },
        ]
      },
      {
        "id": 44,
        "page_title": "Mi Perfil",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-id-card"></i>`,
        "children": []
      },
      {
        "id": 45,
        "page_title": "Mapa del Sitio",
        "description": "Description",
        "icon": `<i class="m-2 icon-sitemap fas fa-project-diagram"></i>`,
        "children": []
      },
    ]
}];

function general_organization_chart(){
    ths.organization_html = `<ul id="ul1" name="ul1">
                                <li>
                                    `;

        ths.sitemaps.forEach( branch =>{
            ths.organization_html += `
                                        <div class="org-chart-center">
                                            <div class="org-chart-director">
                                                <label class="label-director">
                                                    ${branch.icon != '' ? (branch.icon+' <br>') : ''}
                                                    <span class="org-chart-name"> ${branch.page_title}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <ul>
                                            <li>`;

            if(branch.children.length > 0){
                ths.add_branch(branch.children);
            }
            ths.organization_html+=`        </li>
                                        </ul>`;

        });

    document.querySelector('#organigrama').innerHTML = ths.organization_html;
}

/*                                                  */

function add_branch(branch){
    ths.organization_html += `<ul class="branch-center">`;
    branch.forEach( items =>{

        //
        ths.organization_html += `<li>
                                    <div class="org-chart-center">
                                        <div class="org-chart-director">
                                            <label class="label-director">
                                                ${items.icon != '' ? (items.icon+' <br>') : ''}
                                                <span class="org-chart-name"> ${items.page_title}</span>
                                            </label>
                                        </div>
                                    </div>
                                    `;

        if(items.children.length > 0){
            ths.level++;
            ths.add_branch(items.children);
            ths.level--;
        }

        ths.organization_html+=` </li>`;

    });

    ths.organization_html+=`</ul>`;
}

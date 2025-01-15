var ths = this;
var general_data = {
    organization_back: {},
    title:'',

}
var organization_html ="";
var level = 0;


function general_organization_chart(){
    let isVacante = "";
    ths.organization_html = `<ul id="ul1" name="ul1">
                                <li>
                                    `;



        ths.general_data.organization_back.forEach( branch =>{

            isVacante = branch.name != null ? (branch.name+' '+branch.last_name) : 'Vacante'

            ths.organization_html += `
                                        <a class="org-chart-title-owner" href="#">
                                            <span class="org-chart-tooltiptext">
                                                <label>
                                                    <span class="org-chart-name-owner"><i class="fas fa-user-circle" style="font-size:18px;margin-right: 5px;"></i>${isVacante}</span>
                                                    <br>${branch.position_company_full}
                                                    <br>
                                                </label>
                                            </span>
                                        </a>
                                        <ul>
                                            <li>`;

            if(branch.organigrama.length > 0){
                ths.add_branch(branch.organigrama);
            }
            ths.organization_html+=`        </li>
                                        </ul>`;

        });

    document.querySelector('#organigrama').innerHTML = ths.organization_html;
}

/*                                                  */

function add_branch(branch){
    let isVacante = "";
    ths.organization_html += `<ul class="branch-center">`;
    branch.forEach( items =>{

        isVacante = items.name != null ? (items.name+' '+items.last_name) : 'Vacante'
        //
        if(ths.level == 0){
            ths.organization_html += `<li>
                                    <div class="org-chart-center">
                                        <div class="org-chart-director">
                                            <label class="label-director">
                                                <span class="org-chart-name"><i class="fas fa-user-circle" style="font-size:18px;margin-right: 5px;"></i> ${isVacante}</span>
                                                <br>${items.position_company_full}
                                                <br>${items.deparment}
                                            </label>
                                        </div>
                                    </div>
                                    `;
        }else{
            ths.organization_html += `<li>
                                        <div class="org-chart-center">
                                            <div class="org-chart-circle">
                                                <span class="org-chart-tooltiptext">
                                                    <label>
                                                        <span class="org-chart-name"><i class="fas fa-user-circle" style="font-size:18px;margin-right: 5px;"></i> ${isVacante}</span>
                                                        <br>${items.position_company_full}
                                                        <br>${items.deparment}

                                                    </label>
                                                </span>
                                            </div>
                                        </div>
                                        `;
        }

        if(items.organigrama.length > 0){
            ths.level++;
            ths.add_branch(items.organigrama);
            ths.level--;
        }

        ths.organization_html+=` </li>`;

    });

    ths.organization_html+=`</ul>`;
}

/* <br><span class=""><i class="fas fa-phone-alt tel-extension"></i> ${items.extension != null ? items.extension : ''}</span> */

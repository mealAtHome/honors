class MBank
{
    constructor(dat)
    {
        /* field */
        this.bankcode                 = dat.bankcode;
        this.bankname                 = dat.bankname;
        this.maintenance_start        = dat.maintenance_start;
        this.maintenance_end          = dat.maintenance_end;
        this.maintenance_hecto_start  = dat.maintenance_hecto_start;
        this.maintenance_hecto_end    = dat.maintenance_hecto_end;
        this.maintenance_fixedterm    = dat.maintenance_fixedterm;
    }

    getBankcode()               { return this.bankcode; }
    getBankname()               { return this.bankname; }
    getMaintenanceStart()       { return this.maintenance_start; }
    getMaintenanceEnd()         { return this.maintenance_end; }
    getMaintenanceHectoStart()  { return this.maintenance_hecto_start; }
    getMaintenanceHectoEnd()    { return this.maintenance_hecto_end; }
    getMaintenanceFixedterm()   { return this.maintenance_fixedterm; }
}

class MBanks extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MBank(dat));
        }
    }

    /* ================================ */
    /* 은행 선택용 */
    /*
        el : 엘리먼트
        chosenBankcode : 이미 선택된 은행코드
     */
    /* ================================ */
    makeBankForChoose(el, chosenBankcode)
    {
        /* opt validation */
        let html = this.validation();
        if(html != "")
            return html;

        /* ------------------------ */
        /* set variables */
        /* ------------------------ */
        /* set header */
        html +=
        `
            <table class="MBank-makeBankForChoose-tbl-forChoose commonEvent-tbl-tab">
                <tbody>
        `;

        /* 행 반복 */
        let i = 0;
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            if(i % 2 == 0) html += `<tr>`;

            html +=
            `
                <td
                    class="common-tap"
                    tab=""
                    bankcode="${model.getBankcode()}"
                >
                    ${model.getBankname()}
                </td>
            `;

            if(i % 2 == 1) html += `</tr>`;
            i++;
        }
        html += `</tbody></table>`;


        /* ------------------------ */
        /* set html */
        /* ------------------------ */
        $(el).html(html);

        return true;
    }
}
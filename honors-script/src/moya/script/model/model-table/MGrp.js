class MGrp
{
    constructor(dat)
    {
        /* data */    this.grpno                = GGC.Common.char(dat.grpno);
        /* data */    this.grpmanager           = GGC.Common.char(dat.grpmanager);
        /* data */    this.grpimg               = GGC.Common.char(dat.grpimg);
        /* data */    this.grpname              = GGC.Common.char(dat.grpname);
        /* data */    this.modidt               = GGC.Common.datetime(dat.modidt);
        /* data */    this.regidt               = GGC.Common.datetime(dat.regidt);
        /* data */    this.grpmanager_name      = GGC.Common.varchar(dat.grpmanager_name);
        /* data */    this.grpmanager_phone     = GGC.Common.varchar(dat.grpmanager_phone);
        /* data */    this.bacctype             = GGC.Common.enum(dat.bacctype);
        /* data */    this.bacckey              = GGC.Common.char(dat.bacckey);
        /* data */    this.baccno               = GGC.Common.int(dat.baccno);
        /* data */    this.baccnickname         = GGC.Common.char(dat.baccnickname);
        /* data */    this.bacccode             = GGC.Common.char(dat.bacccode);
        /* data */    this.baccacct             = GGC.Common.char(dat.baccacct);
        /* data */    this.baccname             = GGC.Common.char(dat.baccname);
        /* data */    this.bankname             = GGC.Common.char(dat.bankname);
        /* custom */  this.grpimgPath           = GGC.Grp.grpimgPath(this.getGrpno(), this.getGrpimg(), false);
        /* custom */  this.pk                   = `grpno="${this.grpno}"`;
    }

    /* ========================= */
    /* getter */
    /* ========================= */

    /* data */
    getGrpno() { return this.grpno; }
    getGrpmanager() { return this.grpmanager; }
    getGrpimg() { return this.grpimg; }
    getGrpname() { return this.grpname; }
    getModidt() { return this.modidt; }
    getRegidt() { return this.regidt; }
    getGrpmanagerName() { return this.grpmanager_name; }
    getGrpmanagerPhone() { return this.grpmanager_phone; }
    getBacctype() { return this.bacctype; }
    getBacckey() { return this.bacckey; }
    getBaccno() { return this.baccno; }
    getBaccnickname() { return this.baccnickname; }
    getBacccode() { return this.bacccode; }
    getBaccacct() { return this.baccacct; }
    getBaccname() { return this.baccname; }
    getBankname() { return this.bankname; }

    /* custom */
    getGrpimgPath() { return this.grpimgPath; }

    /* custom */
    getPk() { return this.pk; }

    /* ========================= */
    /* make */
    /* ========================= */

    make()
    {
        let html = "";
        html +=
        `
            <div class="Mgrp-make-div-top" grpno="${this.getGrpno()}" grpmanager="${this.getGrpmanager()}">
                <table class="Mgrp-make-tbl-top">
                    <tbody>
                        <tr>
                            <td><div class="Mgrp-make-div-image" style="background-image:url('${this.getGrpimgPath()}')"></div></td>
                            <td>
                                <span class="common-tag-block common-tag-fontsize11">${this.getGrpname()}</span>
                                <span class="common-tag-block">
                                    <span class="common-tag-block common-tag-fontsize10">대표 ${this.getGrpmanagerName()}</span>
                                    <span class="common-tag-block common-tag-fontsize09">TEL. ${this.getGrpmanagerPhone()}</span>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        return html;
    }

}


class MGrps extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrp(dat));
        }
    } /* constructor */



    /* ========================= */
    /* make */
    /* ========================= */
    makeForView(el)      { this.make("makeForView", el); }
    makeForChooseGrp(el) { this.make("makeForChooseGrp", el); }
    make(option, el)
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            let buttonHtml = "";
            switch(option)
            {
                case "makeForChooseGrp":
                {
                    buttonHtml = `<button class="common-btn-inline Mgrp-make-btn-login" grpno="${model.getGrpno()}">선택하기</button>`;
                    break;
                }
                case "makeForView":
                {
                    buttonHtml = `<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.D10DetailGrp}" hyperlink-viewmode="page" ${model.getPk()}>상세보기</button>`;
                    break;
                }
            }
            html +=
            `
                <div class="Mgrp-make-div-top common-div-card" grpno="${model.getGrpno()}" grpmanager="${model.getGrpmanager()}">
                    <table class="Mgrp-make-tbl-top">
                        <tbody>
                            <tr>
                                <td><div class="Mgrp-make-div-image" style="background-image:url('${model.getGrpimgPath()}')"></div></td>
                                <td>
                                    <span class="common-tag-block common-tag-fontsize11">${model.getGrpname()}</span>
                                    <span class="common-tag-block">
                                        <span class="common-tag-block common-tag-fontsize10">대표 ${model.getGrpmanagerName()}</span>
                                        <span class="common-tag-block common-tag-fontsize09">TEL. ${model.getGrpmanagerPhone()}</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="common-tag-fontsize09" style="text-align:right;">
                                    ${buttonHtml}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }
        $(el).html(html);

        $(`${el} .Mgrp-make-btn-login`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            Navigation.moveFrontPage(Navigation.Page.B11ManagerMainHome, {grpno: grpno});
        });
    }


}
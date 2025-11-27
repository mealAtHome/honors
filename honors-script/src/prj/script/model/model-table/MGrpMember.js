class MGrpMember
{
    constructor(dat)
    {
        /* data */      this.grpno              = GGC.Common.char(dat.grpno);
        /* data */      this.userno             = GGC.Common.char(dat.userno);
        /* data */      this.grpmtype           = GGC.Common.enum(dat.grpmtype);
        /* data */      this.grpmposition       = GGC.Common.char(dat.grpmposition);
        /* data */      this.grpmstatus         = GGC.Common.enum(dat.grpmstatus);
        /* data */      this.point              = GGC.Common.int(dat.point);
        /* data */      this.deletedt           = GGC.Common.datetime(dat.deletedt);
        /* data */      this.regidt             = GGC.Common.datetime(dat.regidt);
        /* data */      this.grpmanagerid       = GGC.Common.char(dat.grpmanagerid);
        /* custom */    this.mUser              = _MCommon.fromDat(dat, MUser);
        /* custom */    this.pointWon           = GGC.Common.priceWon(dat.point);
        /* custom */    this.grpmtypeCvrt       = GGC.GrpMember.grpmtypeCvrt(this.grpmtype);
        /* custom */    this.pk                 = `grpno="${this.grpno}" userno="${this.userno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    getGrpno() { return this.grpno; }
    getUserno() { return this.userno; }
    getGrpmtype() { return this.grpmtype; }
    getGrpmposition() { return this.grpmposition; }
    getGrpmstatus() { return this.grpmstatus; }
    getDeletedt() { return this.deletedt; }
    getPoint() { return this.point; }
    getRegidt() { return this.regidt; }
    getGrpmanagerid() { return this.grpmanagerid; }

    /* custom */
    getMUser() { return this.mUser; }
    getPointWon() { return this.pointWon; }
    getGrpmtypeCvrt() { return this.grpmtypeCvrt; }
    getPk() { return this.pk; }

    /* custom > custom */
    getRegidtDate() { return this.regidt.substring(0, 10); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isBattingflgY() { return this.getBattingflg() === GGF.Y; }
    // hasApplyer()    { return !Common.isEmpty(this.getUsername()); }
    isGrpmstatusDelete() { return this.getGrpmstatus() === GGF.GrpMember.Grpmstatus.DELETE; }
    isUsertypeTemp() { return this.getMUser().isUsertypeTemp(); }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClstypeCvrt()   { return GGC.Cls.clstypeCvrt(this.getClstype()); }
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

    make(buttonHtml="")
    {
        let model = this;
        let mUser = model.getMUser();

        /* buttonHtml */
        let buttonHtmlFinal = "";
        if(buttonHtml != "")
        {
            buttonHtmlFinal =
            `
                <span class="common-tag-block common-tag-marginUD common-tag-fontsize09">
                    ${buttonHtml}
                </span>
            `;
        }

        /* final html */
        let html =
        `
            <div class="MGrpMembers-make-div-modelTop common-div-card">
                <span class="common-tag-block common-tag-strong">
                    ${model.getGrpmtypeCvrt()}
                    ${Common.isEmpty(model.getGrpmposition()) ? "" : ` - ${model.getGrpmposition()}`}
                    ${mUser.isUsertypeTemp() ? "[임시]" : ""}</span>
                <span class="common-tag-block">${mUser.getName()} ${mUser.getBirthyear() != "" ? `(${mUser.getBirthyearShort()})` : ""}</span>
                <span class="common-tag-block common-tag-fontsize09">
                    ${mUser.getPhone()         != "" ? `<span class="common-tag-block common-tag-colorGrey">${mUser.getPhone()}</span>` : ""}
                    ${mUser.getAddress()       != "" ? `<span class="common-tag-block common-tag-colorGrey">${mUser.getAddress()}</span>` : ""}
                    ${mUser.getHascarflgCvrt() != "" ? `<span class="common-tag-block common-tag-colorGrey">${mUser.getHascarflgCvrt()}</span>` : ""}
                </span>
                ${buttonHtmlFinal}
            </div>
        `;
        return html;
    }

}

class MGrpMembers extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MGrpMember(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeForChoose(el) { this.make("makeForChoose", el); }
    make(option="", el="")
    {
        /* overloading */
        if(option != "" && el == "")
        {
            el = option;
            option = "";
        }

        /* =============== */
        /* loop models */
        /* =============== */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let mUser = model.getMUser();
            let isManager = GGstorage.Prj.isManagerOfGrp(model.getGrpno());

            /* --------------- */
            /* button */
            /* --------------- */
            let buttonHtml = "";
            switch(option)
            {
                case "":
                {
                    if(isManager)
                    {
                        buttonHtml += `<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.B71GrpMemberDetail}" hyperlink-viewmode="page" ${model.getPk()}>멤버상세</button>&nbsp;`;

                        if(model.isUsertypeTemp())
                        {
                            buttonHtml += `<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.B72GrpMemberMergeTemp}" hyperlink-viewmode="page" ${model.getPk()}>멤버병합</button>&nbsp;`;
                        }
                    }
                    break;
                }
                case "makeForChoose":
                {
                    buttonHtml += `<button class="common-btn-inline  MGrpMember-make-btn-choose" ${model.getPk()}>선택하기</button>&nbsp;`;
                    break;
                }
            }

            /* --------------- */
            /* html */
            /* --------------- */
            html += model.make(buttonHtml);
        }
        $(el).html(html);
    }


}
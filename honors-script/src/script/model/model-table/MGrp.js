class MGrp
{
    constructor(dat)
    {
        /* data */    this.grpno                = GGC.Common.char(dat.grpno);
        /* data */    this.grpmanager           = GGC.Common.char(dat.grpmanager);
        /* data */    this.grpimg               = GGC.Common.char(dat.grpimg);
        /* data */    this.grpname              = GGC.Common.char(dat.grpname);
        /* data */    this.grpintro             = GGC.Common.char(dat.grpintro);
        /* data */    this.backnumberlength     = GGC.Common.int(dat.backnumberlength);
        /* data */    this.grpbaseaddrcode      = GGC.Common.bigint(dat.grpbaseaddrcode);
        /* data */    this.grpbaselat           = GGC.Common.double(dat.grpbaselat);
        /* data */    this.grpbaselng           = GGC.Common.double(dat.grpbaselng);
        /* data */    this.grpbaseaddrstr       = GGC.Common.varchar(dat.grpbaseaddrstr);
        /* data */    this.grpmcnt              = GGC.Common.int(dat.grpmcnt);
        /* data */    this.grpclstermunit       = GGC.Common.enum(dat.grpclstermunit);
        /* data */    this.grpclstermvalue      = GGC.Common.int(dat.grpclstermvalue);
        /* data */    this.grplastclsregisted   = GGC.Common.datetime(dat.grplastclsregisted);
        /* data */    this.grpclsapplybillavg   = GGC.Common.int(dat.grpclsapplybillavg);
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
    getGrpintro() { return this.grpintro; }
    getBacknumberlength() { return this.backnumberlength; }
    getGrpbaseaddrcode() { return this.grpbaseaddrcode; }
    getGrpbaselat() { return this.grpbaselat; }
    getGrpbaselng() { return this.grpbaselng; }
    getGrpbaseaddrstr() { return this.grpbaseaddrstr; }
    getGrpmcnt() { return this.grpmcnt; }
    getGrpclstermunit() { return this.grpclstermunit; }
    getGrpclstermvalue() { return this.grpclstermvalue; }
    getGrplastclsregisted() { return this.grplastclsregisted; }
    getGrpclsapplybillavg() { return this.grpclsapplybillavg; }
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

    /* custom : 활동주기 텍스트 (예: "주 1회") */
    getGrpclstermText()
    {
        if(Common.isEmpty(this.getGrpclstermunit()) || Common.isEmpty(this.getGrpclstermvalue()))
            return "정보없음";
        let unitText = "";
        switch(this.getGrpclstermunit())
        {
            case GGF.Grp.Clstermunit.DAY   : unitText = "일"; break;
            case GGF.Grp.Clstermunit.WEEK  : unitText = "주"; break;
            case GGF.Grp.Clstermunit.MONTH : unitText = "월"; break;
            case GGF.Grp.Clstermunit.YEAR  : unitText = "연"; break;
        }
        return `${unitText} ${this.getGrpclstermvalue()}회`;
    }

    /* custom : 마지막활동 텍스트 (예: "3일 전") */
    getGrplastclsregistedText() { return GGC.Date.datePretty(this.getGrplastclsregisted()); }

    /* custom : 일정평균비용 텍스트 (예: "15,000원") */
    getGrpclsapplybillavgWon() { return GGC.Common.priceWon(this.getGrpclsapplybillavg()); }

    /* ========================= */
    /* make */
    /* ========================= */
    make(btnHtml="")
    {
        if(btnHtml != "")
        {
            btnHtml =
            `
                <tr>
                    <td colspan="2" class="common-fonts09" style="text-align:right;">
                        ${btnHtml}
                    </td>
                </tr>
            `;
        }

        let html = "";
        html +=
        `
            <div class="Mgrp-make-div-top common-div-card" grpno="${this.getGrpno()}" grpmanager="${this.getGrpmanager()}">
                <table class="Mgrp-make-tbl-top">
                    <tbody>
                        <tr>
                            <td><div class="MakeGrpCardNorm-profileImg" style="background-image:url('${this.getGrpimgPath()}')"></div></td>
                            <td>
                                <span class="common-block common-fonts11">${this.getGrpname()}</span>
                                <span class="common-block">
                                    <span class="common-block">대표 ${this.getGrpmanagerName()}</span>
                                    <span class="common-block common-fonts09">TEL. ${this.getGrpmanagerPhone()}</span>
                                </span>
                            </td>
                        </tr>
                        ${btnHtml}
                    </tbody>
                </table>
            </div>
        `;
        return html;
    }

    /* ========================= */
    /* make (모임 카드 - Norm : 지표 스트립형, 목록 스캔용) */
    /* ========================= */
    makeGrpCardNorm(btnHtml="")
    {
        return `
            <div class="Mgrp-cardNorm-top common-div-card" grpno="${this.getGrpno()}" grpmanager="${this.getGrpmanager()}">
                <div class="common-flexCenter">
                    <div class="MakeGrpCardNorm-profileImg" style="background-image:url('${this.getGrpimgPath()}')"></div>
                    <div class="common-flexVertical" style="flex:1; min-width:0;">
                        <span class="common-strong common-fonts11">${this.getGrpname()}</span>
                        <span class="common-fonts08 common-colorCmmt">모임장 ${this.getGrpmanagerName()}</span>
                    </div>
                </div>
                <div class="MakeGrpCardNorm-statStrip common-buttonsForCardTop">
                    <div class="MakeGrpCardNorm-statItem"><i class="ti ti-users"></i><span>${this.getGrpmcnt()}명</span></div>
                    <div class="MakeGrpCardNorm-statItem"><i class="ti ti-map-pin"></i><span>0km</span></div>
                    <div class="MakeGrpCardNorm-statItem"><i class="ti ti-repeat"></i><span>${this.getGrpclstermText()}</span></div>
                    <!-- <div class="MakeGrpCardNorm-statItem"><i class="ti ti-cash"></i><span>${this.getGrpclsapplybillavgWon()}</span></div> -->
                    <div class="MakeGrpCardNorm-statItem"><i class="ti ti-clock"></i><span>${this.getGrplastclsregistedText()}</span></div>
                </div>
                ${btnHtml != "" ? `<div class="common-buttonsForCardTop">${btnHtml}</div>` : ""}
            </div>
        `;
    }

    /* ========================= */
    /* make (모임장 연락처 카드) */
    /* ========================= */
    makeManagerContactCard()
    {
        return `
            <div class="common-div-card">
                <div class="common-header"><i class="ti ti-phone"></i><span>모임장 연락처</span></div>
                <div class="common-cushionHalfUp common-flexCenterSm">
                    <div class="common-inline common-fonts09 common-colorBody">${this.getGrpmanagerName()}</div>
                    <div class="common-inline common-fonts09 common-colorSide commonEvent-tag-phoneCall" phone-call="${this.getGrpmanagerPhone()}" style="cursor:pointer;">
                        <span>${this.getGrpmanagerPhone()}</span>
                    </div>
                </div>
            </div>
        `;
    }

    /* ========================= */
    /* make (모임 카드 - Main : 배지 히어로형, 단독 강조용) */
    /* ========================= */
    makeGrpCardMain(btnHtml="")
    {
        return `
            <div class="MakeGrpCardMain-top common-div-card" grpno="${this.getGrpno()}" grpmanager="${this.getGrpmanager()}">
                <div class="MakeGrpCardMain-bandTop">
                    <span class="MakeGrpCardMain-bandLabel">GROUP</span>
                </div>
                <div class="MakeGrpCardMain-bodyTop">
                    <div class="common-flexCenter">
                        <div class="MakeGrpCardMain-logoWrap">
                            <div class="MakeGrpCardNorm-profileImg MakeGrpCardMain-logoMain" style="background-image:url('${this.getGrpimgPath()}')"></div>
                        </div>
                        <div class="common-flexVertical">
                            <span class="common-strong common-fonts11">${this.getGrpname()}</span>
                            <span class="common-fonts08 common-colorCmmt">모임장 ${this.getGrpmanagerName()}</span>
                        </div>
                    </div>
                    <div class="MakeGrpCardMain-statRow common-buttonsForCardTop">
                        <div class="MakeGrpCardMain-statCol"><span class="common-fonts08 common-colorCmmt">인원</span><span class="common-bold common-fonts09">${this.getGrpmcnt()}명</span></div>
                        <div class="MakeGrpCardMain-statCol"><span class="common-fonts08 common-colorCmmt">거리</span><span class="common-bold common-fonts09">0km</span></div>
                        <div class="MakeGrpCardMain-statCol"><span class="common-fonts08 common-colorCmmt">활동주기</span><span class="common-bold common-fonts09">${this.getGrpclstermText()}</span></div>
                        <div class="MakeGrpCardMain-statCol"><span class="common-fonts08 common-colorCmmt">마지막활동</span><span class="common-bold common-fonts09">${this.getGrplastclsregistedText()}</span></div>
                    </div>
                    ${btnHtml != "" ? `<div class="common-buttonsForCardTop">${btnHtml}</div>` : ""}
                </div>
            </div>
        `;
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
    makeGrpForView(el)      { this.make("makeGrpForView", el); }
    makeGrpForChoose(el)    { this.make("makeGrpForChoose", el); }
    makeGrpForChoose2(el)   { this.make("makeGrpForChoose2", el); }
    make(option, el)
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            let buttonHtml = "";
            switch(option)
            {
                case "makeGrpForView"     : { buttonHtml = `<div></div><div class="commonEvent-tag-hyperlink common-flexCenterSm common-colorSide common-fonts08" hyperlink="${Navigation.Page.D10DetailGrp}" hyperlink-viewmode="page" ${model.getPk()}><span>상세보기</span><i class="ti ti-chevron-right"></i></div>`; break; }
                case "makeGrpForChoose"   : { buttonHtml = `<button class="common-btn-inner Mgrp-make-btn-login" grpno="${model.getGrpno()}">선택하기</button>`; break; }
                case "makeGrpForChoose2"  : { buttonHtml = `<button class="CUDE-btn-chooseGrp commonEvent-btn-radio common-btn-radio" radio_name="CUDE-btn-chooseGrp" tab="" grpno="${model.getGrpno()}">선택</button>`; break; }
            }
            html += model.makeGrpCardNorm(buttonHtml);
        }
        $(el).html(html);

        $(`${el} .Mgrp-make-btn-login`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            Navigation.moveFrontPage(Navigation.Page.B11ManagerMainHome, {grpno: grpno});
        });
    }


}
class MGrpMember
{
    constructor(dat)
    {
        /* data */      this.grpno              = GGC.Common.char(dat.grpno);
        /* data */      this.userno             = GGC.Common.char(dat.userno);
        /* data */      this.useraddridx        = GGC.Common.int(dat.useraddridx);
        /* data */      this.useraddrstr        = GGC.Common.varchar(dat.useraddrstr);
        /* data */      this.grpmbacknum        = GGC.Common.char(dat.grpmbacknum);
        /* data */      this.grpmtype           = GGC.Common.enum(dat.grpmtype);
        /* data */      this.grpmposition       = GGC.Common.char(dat.grpmposition);
        /* data */      this.grpmfinauth        = GGC.Common.enum(dat.grpmfinauth);
        /* data */      this.grpmstatus         = GGC.Common.enum(dat.grpmstatus);
        /* data */      this.point              = GGC.Common.int(dat.point);
        /* data */      this.backnumupdatedt    = GGC.Common.datetime(dat.backnumupdatedt);
        /* data */      this.deletedt           = GGC.Common.datetime(dat.deletedt);
        /* data */      this.regidt             = GGC.Common.datetime(dat.regidt);
        /* data */      this.grpname            = GGC.Common.char(dat.grpname);
        /* data */      this.grpmanagerid       = GGC.Common.char(dat.grpmanagerid);
        /* data */      this.priv_phone         = GGC.Common.enum(dat.priv_phone);
        /* custom */    this.mUser              = _MCommon.fromDat(dat, MUser);
        /* custom */    this.pointWon           = GGC.Common.priceWon(dat.point);
        /* custom */    this.grpmtypeCvrt       = GGC.GrpMember.grpmtypeCvrt(this.grpmtype);
        /* custom */    this.pk                 = `grpno="${this.grpno}" userno="${this.userno}"`;
        /* custom */    this.mGrpmtagas         = (dat.tags != undefined ? dat.tags : []).map(tag => new MGrpmtaga(tag));
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    getGrpno() { return this.grpno; }
    getUserno() { return this.userno; }
    getUseraddridx() { return this.useraddridx; }
    getUseraddrstr() { return this.useraddrstr; }
    getGrpmbacknum() { return this.grpmbacknum; }
    getGrpmtype() { return this.grpmtype; }
    getGrpmposition() { return this.grpmposition; }
    getGrpmfinauth() { return this.grpmfinauth; }
    getGrpmstatus() { return this.grpmstatus; }
    getDeletedt() { return this.deletedt; }
    getPoint() { return this.point; }
    getBacknumupdatedt() { return this.backnumupdatedt; }
    getRegidt() { return this.regidt; }
    getGrpname() { return this.grpname; }
    getGrpmanagerid() { return this.grpmanagerid; }
    getPrivPhone() { return this.priv_phone; }

    /* custom */
    getMUser() { return this.mUser; }
    getPointWon() { return this.pointWon; }
    getGrpmtypeCvrt() { return this.grpmtypeCvrt; }
    getPk() { return this.pk; }
    getMGrpmtagas() { return this.mGrpmtagas; }

    /* custom > custom */
    getRegidtDate() { return this.regidt.substring(0, 10); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isBattingflgY() { return this.getBattingflg() === GGF.Y; }
    // hasApplyer()    { return !Common.isEmpty(this.getUsername()); }
    isGrpmstatusDelete() { return this.getGrpmstatus() === GGF.GrpMember.Grpmstatus.DELETE; }
    isUsertypeTemp() { return this.getMUser().isUsertypeTemp(); }
    hasGrpmfinauth() { return this.getGrpmfinauth() === GGF.Y || this.getMUser().isAdmin(); }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

    makeGrpMember(detailFlg=false, buttonHtml="")
    {
        let model = this;
        let mUser = model.getMUser();

        /* backnumber */
        let backnumberHtml = GGC.GrpMember.backnumberSpan(model.getGrpmbacknum());

        /* 태그 pill */
        let tagsHtml = "";
        if(model.getMGrpmtagas().length > 0)
        {
            let pillsHtml = "";
            for(let i in model.getMGrpmtagas())
                pillsHtml += model.getMGrpmtagas()[i].makePill();
            if(pillsHtml != "")
                tagsHtml = `<div class="common-div-cushionUD common-fonts08">${pillsHtml}</div>`;
        }

        /* 푸터 : 아이콘(전화/자차) + 상세보기(또는 전달받은 buttonHtml) */
        let iconsHtml = "";
        if(mUser.getPhone()     != ""    ) iconsHtml += `<i class="ti ti-phone commonEvent-tag-phoneCall" phone-call="${mUser.getPhone()}"></i>`;
        if(mUser.getHascarflg() == GGF.Y ) iconsHtml += `<i class="ti ti-car"></i>`;

        let detailHtml = `<div class="commonEvent-tag-hyperlink common-flexCenterSm common-colorSide common-fonts08" hyperlink="${Navigation.Page.B71GrpMemberDetail}" hyperlink-viewmode="page" ${model.getPk()}><span>상세보기</span><i class="ti ti-chevron-right"></i></div>`;

        /* final html */
        console.log(detailFlg);
        let html =
        `
            <div class="MGrpMembers-make-div-modelTop common-div-card">
                <div class="common-flexCenter">
                    ${mUser.makeProfile()}
                    <div class="common-flexVertical">
                        <div class="common-strong">${backnumberHtml}<span>${mUser.getName()}</span>${mUser.getBirthyearFont()}</div>
                        <div class="common-fonts08 common-colorBody">${model.getGrpmtypeCvrt()}${Common.isEmpty(model.getGrpmposition()) ? "" : ` · ${model.getGrpmposition()}`}</div>
                    </div>
                </div>
                ${tagsHtml}
                <div class="common-buttonsForCardTop">
                    <div class="common-flexCenter common-colorCmmt">${iconsHtml}</div>
                    ${buttonHtml}
                    ${detailFlg ? detailHtml : ""}
                </div>
            </div>
        `;
        return html;
    }

    /*
        let mGrpMemberDummy = MGrpMember.makeDummy();
        $("#GFSU-div-sponsorMember").html(mGrpMemberDummy);
     */
    static makeDummy()
    {
        let dat =
        {
            /* grpmember */
            grpno: "G0000000001",
            userno: "U0000000001",
            grpmtype: GGF.GrpMember.Grpmtype.MEMBER,
            grpmposition: "",
            grpmstatus: GGF.GrpMember.Grpmstatus.ACTIVE,
            point: 10000,
            deletedt: "2024-01-01 00:00:00",
            regidt: "2024-01-01 00:00:00",
            grpmanagerid: "U0000000001",

            /* user */
            name: "홍길동",
            birthyear: "1990",
            phone: "010-1234-5678",
            address: "서울시 강남구",
            hascarflg: GGF.Y,
            usertype: GGF.User.Usertype.NORMAL,
        };
        let model = new MGrpMember(dat);
        return model.makeGrpMember();
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
    makeForChoose(el) { this.makeGrpMembers("makeForChoose", el); }
    makeGrpMembers(option="", el="")
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

            /* --------------- */
            /* button (기본 상태는 카드 자체의 "상세보기"를 사용하므로 비워둠) */
            /* --------------- */
            let buttonHtml = "";
            switch(option)
            {
                case "makeForChoose":
                {
                    buttonHtml += `<button class="common-btn-inner  MGrpMember-make-btn-choose" ${model.getPk()}>선택하기</button>&nbsp;`;
                    html += model.makeGrpMember(false, buttonHtml);
                    break;
                }
                default:
                {
                    html += model.makeGrpMember(true, buttonHtml);
                    break;
                }
            }
        }
        /* html = this.mergeCushionLR(html); */
        $(el).html(this.mergePagenation(html));
    }


}
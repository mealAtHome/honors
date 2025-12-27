class MClssettle
{
    constructor(dat)
    {
        /* data */      this.grpno                  = GGC.Common.char(dat.grpno);
        /* data */      this.clsno                  = GGC.Common.char(dat.clsno);
        /* data */      this.userno                 = GGC.Common.char(dat.userno);
        /* data */      this.billstandard           = GGC.Common.int(dat.billstandard);
        /* data */      this.billaddition           = GGC.Common.int(dat.billaddition);
        /* data */      this.billadjustment           = GGC.Common.int(dat.billadjustment);
        /* data */      this.billpointed            = GGC.Common.int(dat.billpointed);
        /* data */      this.billfinal              = GGC.Common.int(dat.billfinal);
        /* data */      this.billmemo               = GGC.Common.varchar(dat.billmemo);
        /* data */      this.memberdepositflg       = GGC.Common.enum(dat.memberdepositflg);
        /* data */      this.memberdepositflgdt     = GGC.Common.datetime(dat.memberdepositflgdt);
        /* data */      this.managerdepositflg      = GGC.Common.enum(dat.managerdepositflg);
        /* data */      this.managerdepositflgdt    = GGC.Common.datetime(dat.managerdepositflgdt);
        /* data */      this.regdt                  = GGC.Common.datetime(dat.regdt);
        /* data */      this.username               = GGC.Common.char(dat.username);
        /* data */      this.userid                 = GGC.Common.char(dat.userid);
        /* data */      this.clstitle               = GGC.Common.varchar(dat.clstitle);
        /* data */      this.clsstartdt             = GGC.Common.datetime(dat.clsstartdt);
        /* data */      this.clsclosedt             = GGC.Common.datetime(dat.clsclosedt);
        /* data */      this.clsground              = GGC.Common.varchar(dat.clsground);
        /* data */      this.grpmanagerid           = GGC.Common.char(dat.grpmanagerid);
        /* data */      this.grpname                = GGC.Common.char(dat.grpname);
        /* data */      this.bankname               = GGC.Common.char(dat.bankname);
        /* data */      this.baccacct               = GGC.Common.char(dat.baccacct);
        /* data */      this.baccname               = GGC.Common.char(dat.baccname);
        /* custom */    this.memberdepositflgCvrt   = GGC.Clssettle.memberdepositflg(dat.memberdepositflg);
        /* custom */    this.managerdepositflgCvrt  = GGC.Clssettle.managerdepositflg(dat.managerdepositflg);
        /* custom */    this.pk                     = `grpno="${this.grpno}" clsno="${this.clsno}" userno="${this.userno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */
    getGrpno() { return this.grpno; }
    getClsno() { return this.clsno; }
    getUserno() { return this.userno; }
    getBillstandard() { return this.billstandard; }
    getBilladdition() { return this.billaddition; }
    getBilladjustment() { return this.billadjustment; }
    getBillpointed() { return this.billpointed; }
    getBillfinal() { return this.billfinal; }
    getBillmemo() { return this.billmemo; }
    getMemberdepositflg() { return this.memberdepositflg; }
    getMemberdepositflgdt() { return this.memberdepositflgdt; }
    getManagerdepositflg() { return this.managerdepositflg; }
    getManagerdepositflgdt() { return this.managerdepositflgdt; }
    getRegdt() { return this.regdt; }
    getUsername() { return this.username; }
    getUserid() { return this.userid; }
    getClstitle() { return this.clstitle; }
    getClsstartdt() { return this.clsstartdt; }
    getClsclosedt() { return this.clsclosedt; }
    getClsground() { return this.clsground; }
    getGrpmanagerid() { return this.grpmanagerid; }
    getGrpname() { return this.grpname; }
    getBankname() { return this.bankname; }
    getBaccacct() { return this.baccacct; }
    getBaccname() { return this.baccname; }

    /* custom */
    getMemberdepositflgCvrt() { return this.memberdepositflgCvrt; }
    getManagerdepositflgCvrt() { return this.managerdepositflgCvrt; }
    getPk() { return this.pk; }

    /* custom > custom */
    getBillstandardWon() { return GGC.Common.priceWon(this.billstandard); }
    getBilladditionWon() { return GGC.Common.priceWon(this.billaddition); }
    getBilladjustmentWon() { return GGC.Common.priceWon(this.billadjustment); }
    getBillpointedWon() { return GGC.Common.priceWon(this.billpointed); }
    getBillfinalWon() { return GGC.Common.priceWon(this.billfinal); }
    getClsPeriod() { return GGdate.period(this.getClsstartdt(), this.getClsclosedt()); }
    getClssettleStatusPretty() { return GGC.Clssettle.clssettleStatusPretty(this.getMemberdepositflg(), this.getManagerdepositflg()); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isManagerdepositflgYes() { return this.getManagerdepositflg() === GGF.Y; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClstypeCvrt()   { return GGC.Cls.clstypeCvrt(this.getClstype()); }
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MClssettles extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClssettle(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {
        let userid = GGstorage.getUserid();
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let isManager = GGstorage.Prj.isManagerOfGrp(model.getGrpno());

            /* ----- */
            /* 상태에 따른 버튼표시 */
            /* ----- */
            let isMe      = model.getUserid() == userid;
            let btnHtml   = "";
            let baccHtml  = "";

            /* 나의 미입금이면서, 미입금상태라면, 완료된 상태가 아니라면 */
            if(isMe && model.getMemberdepositflg() != GGF.Y && model.getManagerdepositflg() != GGF.Y)
                btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-memberdepositflgYes" ${model.getPk()}>입금완료(임시)</button> `;

            /* 관리자면서, 미임금상태라면 */
            if(isManager && model.getManagerdepositflg() != GGF.Y)
                btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-managerdepositflgYes" ${model.getPk()}>입금완료</button> `;

            /* 미입금상태라면, 입금계좌표시 */
            if(model.getManagerdepositflg() != GGF.Y)
            {
                baccHtml +=
                `
                    <span class="common-tag-block common-tag-marginUp06 common-tag-alignL common-tag-fontsize09">
                        입금계좌<br>
                        <span class="common-tag-block">
                            <span style="vertical-align:middle">${model.getBankname()} ${model.getBaccacct()} ${model.getBaccname()}</span>
                            <button class="common-btn-outline MClssettle-make-btn-copyBacc" copytext="${model.getBankname()} ${model.getBaccacct()}" style="margin-left:0.2em;">복사</button>
                        </span>
                    </span>
                `;
            }

            /* ----- */
            /* make html */
            /* ----- */
            html +=
            `
                <div class="MGrpMembers-make-div-modelTop common-div-card">
                    <span class="common-tag-block common-tag-strong common-tag-cushionDw03">정산상세 - ${model.getUsername()}</span>
                    <span class="common-tag-block ">${model.getGrpname()}</span>
                    <span class="common-tag-block ">${model.getClstitle()}</span>
                    <span class="common-tag-block common-tag-colorGrey common-tag-fontsize08">${model.getClsPeriod()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-bold">${model.getBillfinalWon()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">기준금액:${model.getBillstandardWon()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">할인금액:${model.getBilladjustmentWon()}</span>
                    ${model.getBillpointed() >= 1  ? `<span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">사전정산:${model.getBillpointedWon()}</span>` : ""}
                    ${model.getBillmemo()    != "" ? `<span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">할인사유:${model.getBillmemo()}</span>` : ""}
                    <span class="common-tag-block common-tag-marginUp common-tag-fontsize09">
                        <button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F00Class000Detail}" hyperlink-viewmode="page" ${model.getPk()}>일정상세</button>
                        ${btnHtml}
                    </span>
                    ${baccHtml}
                    <div class="common-tag-positionAbsUR">${model.getClssettleStatusPretty()}</div>
                </div>
            `;
        }
        $(el).html(html);

        /* 입금완료(임시) */
        $(`${el} .MClssettle-make-btn-memberdepositflgYes`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateMemberdepositflgYesForUsr(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("입금완료(임시) 처리는 유저 본인이 입금한 후에 임시로 완료처리하는 것으로, 완전히 완료된 것은 아닙니다. 계속하시겠습니까?", process);
        });

        /* 입금완료 */
        $(`${el} .MClssettle-make-btn-managerdepositflgYes`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateManagerdepositflgYesForMng(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("입금이 확인되셨습니까?", process);
        });

        /* 입금계좌 복사 */
        $(`${el} .MClssettle-make-btn-copyBacc`).off("click").on("click", function()
        {
            let copytext = $(this).attr("copytext");
            Common.copyToClipboard(copytext);
        });
    }

}
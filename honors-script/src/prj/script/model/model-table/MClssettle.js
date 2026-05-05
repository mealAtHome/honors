class MClssettle
{
    constructor(dat)
    {
        /* data */      this.grpno                  = GGC.Common.char(dat.grpno);
        /* data */      this.clsno                  = GGC.Common.char(dat.clsno);
        /* data */      this.userno                 = GGC.Common.char(dat.userno);
        /* data */      this.billstandard           = GGC.Common.int(dat.billstandard);
        /* data */      this.billprepaid            = GGC.Common.int(dat.billprepaid);
        /* data */      this.billadjustment         = GGC.Common.int(dat.billadjustment);
        /* data */      this.billdiscount           = GGC.Common.int(dat.billdiscount);
        /* data */      this.billpointed            = GGC.Common.int(dat.billpointed);
        /* data */      this.billfinal              = GGC.Common.int(dat.billfinal);
        /* data */      this.billmemo               = GGC.Common.varchar(dat.billmemo);
        /* data */      this.settlestatus           = GGC.Common.char(dat.settlestatus);
        /* data */      this.settlemembdt           = GGC.Common.datetime(dat.settlemembdt);
        /* data */      this.settledonedt           = GGC.Common.datetime(dat.settledonedt);
        /* data */      this.settlelossdt           = GGC.Common.datetime(dat.settlelossdt);
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
        /* data */      this.grpm_point             = GGC.Common.int(dat.grpm_point);
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
    getBillprepaid() { return this.billprepaid; }
    getBilladjustment() { return this.billadjustment; }
    getBilldiscount() { return this.billdiscount; }
    getBillpointed() { return this.billpointed; }
    getBillfinal() { return this.billfinal; }
    getBillmemo() { return this.billmemo; }
    getSettlestatus() { return this.settlestatus; }
    getSettlemembdt() { return this.settlemembdt; }
    getSettledonedt() { return this.settledonedt; }
    getLossflg() { return this.lossflg; }
    getSettlelossdt() { return this.settlelossdt; }
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
    getGrpmPoint() { return this.grpm_point; }

    /* custom */
    getPk() { return this.pk; }

    /* custom > custom */
    getBillstandardWon() { return GGC.Common.priceWon(this.billstandard); }
    getBillprepaidWon() { return GGC.Common.priceWon(this.billprepaid); }
    getBilladjustmentWon() { return GGC.Common.priceWon(this.billadjustment); }
    getBilldiscountWon() { return GGC.Common.priceWon(this.billdiscount); }
    getBillpointedWon() { return GGC.Common.priceWon(this.billpointed); }
    getBillfinalWon() { return GGC.Common.priceWon(this.billfinal); }
    getGrpmPointWon() { return GGC.Common.priceWon(this.grpm_point); }
    getClsPeriod() { return GGdate.period(this.getClsstartdt(), this.getClsclosedt()); }
    getGrpmPointAfterSettle() { return this.getGrpmPoint() + this.getBillpointed();}
    getGrpmPointAfterSettleWon() { return GGC.Common.priceWon(this.getGrpmPointAfterSettle()); }

    /* ========================= */
    /* fields - cvrt */
    /* ========================= */
    getSettlestatusCvrt() { return GGC.Clssettle.settlestatusCvrt(this.getSettlestatus()); }
    getSettlestatusFont() { return GGC.Clssettle.settlestatusFont(this.getSettlestatus()); }
    getSettleStatusCard() { return GGC.Clssettle.settlestatusCard(this.getSettlestatus()); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isIn5MinWhenSettledonedt() { return Common.isEmpty(this.getSettledonedt()) ? false : GGdate.isIn5MinFromNow(this.getSettledonedt()); }
    hasBillprepaid() { return this.getBillprepaid() >= 1; }

    /* ========================= */
    /* fields - is */
    /* ========================= */
    isSettlestatusWait() { return this.getSettlestatus() === GGF.Clssettle.Settlestatus.WAIT; }
    isSettlestatusMemb() { return this.getSettlestatus() === GGF.Clssettle.Settlestatus.MEMB; }
    isSettlestatusDone() { return this.getSettlestatus() === GGF.Clssettle.Settlestatus.DONE; }
    isSettlestatusLoss() { return this.getSettlestatus() === GGF.Clssettle.Settlestatus.LOSS; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
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
            let isMe = model.getUserid() == userid;
            let btnHtml   = "";
            let baccHtml  = "";

            /* 입금완료 : 나의 미입금이면서, 미입금상태라면, 완료된 상태가 아니라면 */
            /* 입금확인 : 관리자면서, 미임금상태라면 */
            /* 입금확인취소 : 관리자면서, 입금완료상태인데, 입금완료한지 5분 이내라면 */
            /* 손실처리 : 관리자면서, 입금완료 상태가 아니며, 손실처리상태가 아니라면 */
            switch(model.getSettlestatus())
            {
                case GGF.Clssettle.Settlestatus.WAIT :
                {
                    if(isMe)
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToMemb" ${model.getPk()} btn-type="normal">입금완료</button> `;

                    if(isManager)
                    {
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToDone" ${model.getPk()} btn-type="normal">입금확인</button> `;
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToLoss" ${model.getPk()} btn-type="delete">손실처리</button> `;
                    }
                    break;
                }
                case GGF.Clssettle.Settlestatus.MEMB :
                {
                    if(isManager)
                    {
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToDone" ${model.getPk()} btn-type="normal">입금확인</button> `;
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToLoss" ${model.getPk()} btn-type="delete">손실처리</button> `;
                    }
                    break;
                }
                case GGF.Clssettle.Settlestatus.DONE :
                {
                    if(isManager)
                    {
                        if(model.isIn5MinWhenSettledonedt())
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToWait" ${model.getPk()} btn-type="cancel">입금확인취소</button> `;
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToLoss" ${model.getPk()} btn-type="delete">손실처리</button> `;
                    }
                    break;
                }
                case GGF.Clssettle.Settlestatus.LOSS :
                {
                    if(isManager)
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToDone" ${model.getPk()} btn-type="normal">입금확인</button> `;
                    break;
                }
            }

            /* 미입금상태라면, 입금계좌표시 */
            if(model.isSettlestatusDone() == false)
            {
                baccHtml +=
                `
                    <div class="common-tag-marginUp08 common-tag-alignL common-tag-fontsize09">
                        <span class="common-tag-block">입금계좌</span>
                        <span class="common-tag-block">
                            <span style="vertical-align:middle">${model.getBankname()} ${model.getBaccacct()} ${model.getBaccname()}</span>
                            <button class="common-btn-outline MClssettle-make-btn-copyBacc" copytext="${model.getBankname()} ${model.getBaccacct()}" style="margin-left:0.2em;">복사</button>
                        </span>
                    </div>
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
                    <span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">사전정산:${model.getBillprepaidWon()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">청구추가:${model.getBilladjustmentWon()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">청구차감:${model.getBilldiscountWon()}</span>
                    ${model.getBillpointed() >= 1  ? `<span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">사전정산:${model.getBillpointedWon()}</span>` : ""}
                    ${model.getBillmemo()    != "" ? `<span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">보정사유:${model.getBillmemo()}</span>` : ""}
                    <span class="common-tag-block common-tag-marginUp common-tag-fontsize09">
                        <button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F00Class000Detail}" hyperlink-viewmode="page" ${model.getPk()}>일정상세</button>
                        ${btnHtml}
                    </span>
                    ${baccHtml}
                    <div class="common-tag-positionAbsUR">${model.getSettleStatusCard()}</div>
                </div>
            `;
        }
        $(el).html(this.mergePagenation(html));

        /* 입금완료 */
        $(`${el} .MClssettle-make-btn-settlestatusToMemb`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateSettlestatusToMembForUsr(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("입금을 완료하셨습니까?", process);
        });

        /* 입금확인완료 */
        $(`${el} .MClssettle-make-btn-settlestatusToDone`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateSettlestatusToDoneForFnc(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("입금이 확인되셨습니까?", process);
        });

        /* 입금확인취소 */
        $(`${el} .MClssettle-make-btn-settlestatusToWait`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateSettlestatusToWaitForFnc(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("입금확인을 취소하시겠습니까?", process);
        });

        /* 손실처리 */
        $(`${el} .MClssettle-make-btn-settlestatusToLoss`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateSettlestatusToLossForFnc(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("손실처리를 진행하시겠습니까?", process);
        });

        /* 입금계좌 복사 */
        $(`${el} .MClssettle-make-btn-copyBacc`).off("click").on("click", function()
        {
            let copytext = $(this).attr("copytext");
            Common.copyToClipboard(copytext);
        });
    }

}
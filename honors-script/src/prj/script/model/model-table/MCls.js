class MCls
{
    constructor(dat)
    {
        /* data */      this.grpno                  = GGC.Common.char(dat.grpno);
        /* data */      this.clsno                  = GGC.Common.char(dat.clsno);
        /* data */      this.clsstatus              = GGC.Common.char(dat.clsstatus);
        /* data */      this.clstype                = GGC.Common.char(dat.clstype);
        /* data */      this.clstitle               = GGC.Common.char(dat.clstitle);
        /* data */      this.clscontent             = GGC.Common.varchar(dat.clscontent);
        /* data */      this.clsstartdt             = GGC.Common.datetime(dat.clsstartdt);
        /* data */      this.clsclosedt             = GGC.Common.datetime(dat.clsclosedt);
        /* data */      this.clsground              = GGC.Common.char(dat.clsground);
        /* data */      this.clsgroundaddr          = GGC.Common.char(dat.clsgroundaddr);
        /* data */      this.clsbillapplyprice      = GGC.Common.int(dat.clsbillapplyprice);
        /* data */      this.clsbillapplyunit       = GGC.Common.char(dat.clsbillapplyunit);
        /* data */      this.clsapplystartdt        = GGC.Common.datetime(dat.clsapplystartdt);
        /* data */      this.clsapplyclosedt        = GGC.Common.datetime(dat.clsapplyclosedt);
        /* data */      this.clssettleflg           = GGC.Common.enum(dat.clssettleflg);
        /* data */      this.clsmodidt              = GGC.Common.datetime(dat.clsmodidt);
        /* data */      this.clsregdt               = GGC.Common.datetime(dat.clsregdt);
        /* data */      this.grpmanagerid           = GGC.Common.char(dat.grpmanagerid);
        /* data */      this.grpimg                 = GGC.Common.char(dat.grpimg);
        /* data */      this.grpname                = GGC.Common.char(dat.grpname);
        /* data */      this.clsusernoregname       = GGC.Common.char(dat.clsusernoregname);
        /* data */      this.clsusernoadmname       = GGC.Common.char(dat.clsusernoadmname);
        /* data */      this.clsusernosubname       = GGC.Common.char(dat.clsusernosubname);
        /* data */      this.clscancelreason        = GGC.Common.char(dat.clscancelreason);
        /* custom */    this.grpimgPath             = GGC.Grp.grpimgPath(this.getGrpno(), this.getGrpimg(), false);
        /* custom */    this.pk                     = `grpno="${this.grpno}" clsno="${this.clsno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getClsno() { return this.clsno; }
    /* data */      getClsstatus() { return this.clsstatus; }
    /* data */      getClstype() { return this.clstype; }
    /* data */      getClstitle() { return this.clstitle; }
    /* data */      getClscontent() { return this.clscontent; }
    /* data */      getClsstartdt() { return this.clsstartdt; }
    /* data */      getClsclosedt() { return this.clsclosedt; }
    /* data */      getClsground() { return this.clsground; }
    /* data */      getClsgroundaddr() { return this.clsgroundaddr; }
    /* data */      getClsbillapplyprice() { return this.clsbillapplyprice; }
    /* data */      getClsbillapplyunit() { return this.clsbillapplyunit; }
    /* data */      getClsapplystartdt() { return this.clsapplystartdt; }
    /* data */      getClsapplyclosedt() { return this.clsapplyclosedt; }
    /* data */      getClssettleflg() { return this.clssettleflg; }
    /* data */      getClsmodidt() { return this.clsmodidt; }
    /* data */      getClsregdt() { return this.clsregdt; }
    /* data */      getGrpmanagerid() { return this.grpmanagerid; }
    /* data */      getGrpimg() { return this.grpimg; }
    /* data */      getGrpname() { return this.grpname; }
    /* data */      getClsusernoregname() { return this.clsusernoregname; }
    /* data */      getClsusernoadmname() { return this.clsusernoadmname; }
    /* data */      getClsusernosubname() { return this.clsusernosubname; }
    /* data */      getClscancelreason() { return this.clscancelreason; }
    /* custom */    getGrpimgPath() { return this.grpimgPath; }
    /* custom */    getPk() { return this.pk; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    getClstypeCvrt()             { return GGC.Cls.clstypeCvrt(this.getClstype()); }
    getClsstatusCard()           { return GGC.Cls.clsstatusCard(this.getClsstatus()); }
    getClsPeriod()               { return GGdate.period(this.getClsstartdt(), this.getClsclosedt()); }
    getClsapplyPeriod()          { return GGdate.period(this.getClsapplystartdt(), this.getClsapplyclosedt()); }
    getClsapplybillpriceWon()    { return this.getClsbillapplyunit() + " 당, " + GGC.Common.priceWon(this.getClsbillapplyprice()); }

    /* ========================= */
    /* is */
    /* ========================= */
    isEndsettle() { return this.getClsstatus() === GGF.Cls.Clsstatus.ENDSETTLE; }
    isCancel()    { return this.getClsstatus() === GGF.Cls.Clsstatus.CANCEL; }

    /* ========================= */
    /* make with buttons */
    /* ========================= */
    makeWithBtnHtml(btnHtml="")
    {
        let html = "";
        let model = this;
        html +=
        `
            <div class="MClss-make-div-modelTop common-div-card">
                <table class="common-tbl-label" label-size="3rd">
                    <tbody>
                        <tr>
                            <td><div class="common-img-label" style="background-image:url('${model.getGrpimgPath()}')"></div></td>
                            <td><span class="common-tag-block common-tag-fontsize10">${model.getGrpname()}</span></td>
                        </tr>
                    </tbody>
                </table>
                <div class="common-tag-block">
                    <span class="common-tag-fontsize11 common-tag-strong">일정</span>
                    <span class="common-tag-fontsize09">${model.getClstypeCvrt()}</span>
                </div>
                <span class="common-tag-block">${model.getClstitle()}</span>
                <div class="common-div-cushionUD">
                    <span class="common-tag-inlineBlock">${model.getClsstatusCard()}</span>
                </div>
                <div class="common-div-cushionUD common-div-btnList common-tag-fontsize09">
                    ${btnHtml}
                </div>
            </div>
        `;
        return html;
    }


}

class MClss extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MCls(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let isManager = GGstorage.Prj.isManagerOfGrp(model.getGrpno());

            /* ----- */
            /* clsstatus 에 따른 버튼표시 */
            /* ----- */
            let btnHtml = `<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F00Class000Detail}" hyperlink-viewmode="page" ${model.getPk()}>상세보기</button>`;
            if(isManager)
            {
                switch(model.getClsstatus())
                {
                    case GGF.Cls.Clsstatus.EDIT:
                    {
                        btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate000Default}" hyperlink-viewmode="page" option="update" ${model.getPk()}>일정수정</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate010TypeLineup}" hyperlink-viewmode="page" option="update" ${model.getPk()}>참가설정</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline MClss-make-btn-editToIng" ${model.getPk()}>일정공개</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline MClss-make-btn-deleteCls" btn-type="delete" ${model.getPk()}>일정삭제</button>`;
                        break;
                    }
                    case GGF.Cls.Clsstatus.ING:
                    {
                        btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate010TypeLineup}" hyperlink-viewmode="page" option="update" ${model.getPk()}>참가설정</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline MClss-make-btn-ingToEndcls" ${model.getPk()}>정산중으로 변경</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate030Cancel}" hyperlink-viewmode="page" btn-type="cancel" ${model.getPk()}>일정취소</button>`;
                        break;
                    }
                    case GGF.Cls.Clsstatus.ENDCLS:
                    {
                        btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate020Settle}" hyperlink-viewmode="page" option="update" ${model.getPk()}>정산</button>`;
                        break;
                    }
                }
            }

            /* make html */
            html += model.makeWithBtnHtml(btnHtml);
        }
        $(el).html(html);

        /* 일정공개 */
        $(`${el} .MClss-make-btn-editToIng`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse = Api.Cls.updateClsstatusEditToIng(grpno, clsno);
                    if(mApiResponse.isSuccess())
                    {
                        Common.hideProgress();
                        Navigation.executeShow();
                        return;
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("해당 일정이 멤버에게 공개됩니다. 계속하시겠습니까?", process);
        });

        /* 일정삭제 */
        $(`${el} .MClss-make-btn-deleteCls`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse = Api.Cls.deleteByPkForMng(grpno, clsno);
                    if(mApiResponse.isSuccess())
                    {
                        Common.hideProgress();
                        Navigation.executeShow();
                        return;
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("일정이 삭제됩니다. 계속하시겠습니까?", process);
        });

        /* 정산중 */
        $(`${el} .MClss-make-btn-ingToEndcls`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse = Api.Cls.updateClsstatusIngToEndcls(grpno, clsno);
                    if(mApiResponse.isSuccess())
                    {
                        Common.hideProgress();
                        Navigation.executeShow();
                        return;
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("정산상태로 변경합니다. 계속하시겠습니까?", process);
        });
    }

    /* ========================= */
    /* 선택용 */
    /* ========================= */
    makeForChoose(el="")
    {
        /* html */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let btnHtml = `<button class="common-btn-inline MClss-make-btn-choose" ${model.getPk()}>선택하기</button>`;
            html += model.makeWithBtnHtml(btnHtml);
        }

        /* pagenation */
        if(this.getPagecnt() > 1)
        {
            let pagenation = this.getPagenation();
            html = pagenation + html + pagenation;
        }
        $(el).html(html);
    }

}
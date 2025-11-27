class MBankaccount
{
    constructor(dat)
    {
        /* data */   this.bacctype            = GGC.Common.enum(dat.bacctype);
        /* data */   this.bacckey             = GGC.Common.char(dat.bacckey);
        /* data */   this.baccno              = GGC.Common.int(dat.baccno);
        /* data */   this.baccnickname        = GGC.Common.char(dat.baccnickname);
        /* data */   this.bacccode            = GGC.Common.char(dat.bacccode);
        /* data */   this.baccacct            = GGC.Common.char(dat.baccacct);
        /* data */   this.baccname            = GGC.Common.char(dat.baccname);
        /* data */   this.usable              = GGC.Common.enum(dat.usable);
        /* data */   this.defaultflg          = GGC.Common.enum(dat.defaultflg);
        /* data */   this.modidt              = GGC.Common.datetime(dat.modidt);
        /* data */   this.regidt              = GGC.Common.datetime(dat.regidt);
        /* data */   this.bankname            = GGC.Common.char(dat.bankname);
        /* data */   this.baccnodefault_user  = GGC.Common.int(dat.baccnodefault_user);
        /* data */   this.baccnodefault_grp   = GGC.Common.int(dat.baccnodefault_grp);
        /* custom */ this.pk                  = `bacctype="${this.bacctype}" bacckey="${this.bacckey}" baccno="${this.baccno}"`; /* pk */
    }

    getBacctype() { return this.bacctype; }
    getBacckey() { return this.bacckey; }
    getBaccno() { return this.baccno; }
    getBaccnickname() { return this.baccnickname; }
    getBacccode() { return this.bacccode; }
    getBaccacct() { return this.baccacct; }
    getBaccname() { return this.baccname; }
    getUsable() { return this.usable; }
    getDefaultflg() { return this.defaultflg; }
    getModidt() { return this.modidt; }
    getRegidt() { return this.regidt; }
    getBankname() { return this.bankname; }
    getPk() { return this.pk; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    getDefaultflgCard() { return GGC.Bankaccount.defaultflgCard(this.getDefaultflg()); }

    /* ========================= */
    /* default html */
    /* ========================= */
    make(mode="")
    {
        let btnHtml = ``;
        if(this.getDefaultflg() == GGF.Bankaccount.Defaultflg.Y)
            btnHtml += `<span class="common-tag-inlineBlock">${this.getDefaultflgCard()}</span>`;
        switch(mode)
        {
            case "choose":
            {
                btnHtml += `<button class="common-btn-outline MBankaccount-make-btn-choose" ${this.getPk()} el="${chooseEl}">선택</button>`;
                break;
            }
            default:
            {
                if(this.getDefaultflg() == GGF.Bankaccount.Defaultflg.N || this.getDefaultflg() == "")
                    btnHtml += `<button class="common-btn-outline MBankaccount-make-btn-delete" btn-type="cancel" ${this.getPk()}>삭제</button>`;
                break;
            }
        }

        /* html */
        let html =
        `
            <div class="MBankaccount-make-div-top common-div-card">
                <div class="common-tag-block">
                    <span class="common-tag-strong">계좌</span>
                    <span>${this.getBaccnickname()}</span>
                </div>
                <span class="common-tag-block"></span>
                <span class="common-tag-block">${this.getBankname()}&nbsp;${this.getBaccacct()}</span>
                <span class="common-tag-block">예금주명 : ${this.getBaccname()}</span>
                <div class="common-tag-block common-tag-fontsize09 common-tag-paddingUD02">
                    ${btnHtml}
                </div>
            </div>
        `;
        return html;
    }

}

class MBankaccounts extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MBankaccount(dat));
        }
    }

    makeSelectOption()
    {
        let html = `<option value="">선택</option>`;
        for(let i in this.models)
        {
            let model = this.models[i];
            html +=
            `
                <option
                    value="${model.getBaccno()}"
                    baccno="${model.getBaccno()}"
                    baccnickname="${model.getBaccnickname()}"
                    bacccode="${model.getBacccode()}"
                    baccacct="${model.getBaccacct()}"
                    baccname="${model.getBaccname()}"
                    bankname="${model.getBankname()}"

                >
                    ${model.getBankname()}
                    ${model.getBaccacct()}
                    ${model.getBaccnickname()}
                </option>`;
        }
        return html;
    }

    /* ========================= */
    /* 등록된 계좌가 없다고 안내 */
    /* ========================= */
    static makeAnnounceIfEmpty()
    {
        let html =
        `
            <div class="common-div-card" card-type="warning">
                <div class="common-div-cushionD">
                    정산/환불용 계좌를 등록하여주시기 바랍니다.
                </div>
                <button class="common-btn-outline common-tag-block" btn-type="warning" onclick="Navigation.moveFrontPage(Navigation.Page.B32UserBaccnoRefundUpdate);">계좌등록</button>
            </div>
        `;
        return html;
    }

    make(el, chooseEl="")
    {
        /* ========================= */
        /* make html */
        /* ========================= */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html += model.make(chooseEl);
        }

        /* ========================= */
        /* set html */
        /* ========================= */
        $(el).html(html);

        /* ========================= */
        /* set event */
        /* ========================= */
        $(`${el} .MBankaccount-make-btn-delete`).off("click");
        $(`${el} .MBankaccount-make-btn-choose`).off("click");

        /* --------------- */
        /* choose entity */
        /* --------------- */
        // $(`${el} .MBankaccount-make-btn-choose`).on("click", function()
        // {
        //     let el            = $(this).attr("el");
        //     let bankaccountNo = $(this).attr("baccno");
        //     let mBankaccounts = Api.Bankaccount.selectByPk(bankaccountNo, "none", "toast");
        //     let mBankaccount  = mBankaccounts.getModel();
        //     let html          = mBankaccount.makeForView();
        //     $(el).html(html);
        //     Navigation.moveBack();
        // });

        /* --------------- */
        /* delete entity */
        /* --------------- */
        $(`${el} .MBankaccount-make-btn-delete`).on("click", function()
        {
            let bacctype = $(this).attr("bacctype");
            let bacckey  = $(this).attr("bacckey");
            let baccno   = $(this).attr("baccno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse =Api.Bankaccount.deleteByPk(bacctype, bacckey, baccno, "toast", "toast");
                    if(mApiResponse.isSuccess())
                        Navigation.showLastPage();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("삭제하시겠습니까?", process);
        });
    }
}
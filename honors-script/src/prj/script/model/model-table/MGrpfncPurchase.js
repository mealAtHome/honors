class MGrpfncPurchase
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.purchaseidx = GGC.Common.int(dat.purchaseidx);
        /* data */      this.purchaseitem = GGC.Common.varchar(dat.purchaseitem);
        /* data */      this.purchasecost = GGC.Common.int(dat.purchasecost);
        /* data */      this.purchasecomment = GGC.Common.varchar(dat.purchasecomment);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* data */      this.username = GGC.Common.varchar(dat.username);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getPurchaseidx() { return this.purchaseidx; }
    /* data */      getPurchaseitem() { return this.purchaseitem; }
    /* data */      getPurchasecost() { return this.purchasecost; }
    /* data */      getPurchasecomment() { return this.purchasecomment; }
    /* data */      getRegdt() { return this.regdt; }
    /* data */      getUsername() { return this.username; }

    /* ========================= */
    /* make */
    /* ========================= */
    /* custom */    getPurchasecostWonColor() { return GGC.Common.wonColor(this.getPurchasecost()); }
    /* custom */    getPk() { return `grpno="${this.getGrpno()}" purchaseidx="${this.getPurchaseidx()}"`; }

}


class MGrpfncPurchases extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpfncPurchase(dat));
        }
    } /* constructor */

    makeTable(el="")
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            html +=
            `
                <tr>
                    <td col="delete"                ><button class="MGrpfncPurchase-makeTable-btn-delete common-btn-outline" btn-type="cancel" ${model.getPk()}>삭제</td>
                    <td col="purchaseidx"           >${model.getPurchaseidx()}</td>
                    <td col="regdt"                 >${model.getRegdt()}</td>
                    <td col="purchaseitem"          >${model.getPurchaseitem()}</td>
                    <td col="purchasecost"          >${model.getPurchasecostWonColor()}</td>
                    <td col="purchasecomment"       >${model.getPurchasecomment()}</td>
                </tr>
            `;
        }

        html =
        `
            <div class="common-div-scrollX">
                <table class="MGrpfncPurchase-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                    <thead>
                        <tr>
                            <th>삭제</th>
                            <th>번호</th>
                            <th>등록일</th>
                            <th>품목</th>
                            <th>금액</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${html}
                    </tbody>
                </table>
            </div>
        `;

        /* pagenation */
        let pagenation = this.getPagenation();
        html = pagenation + html + pagenation;
        $(el).html(html);

        /* event */
        $(`${el} .MGrpfncPurchase-makeTable-btn-delete`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let purchaseidx = $(this).attr("purchaseidx");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    try
                    {
                        let mApiResponse = Api.GrpfncPurchase.deleteByPk(grpno, purchaseidx);
                        if(mApiResponse.isSuccess())
                            Navigation.executeShow();
                    }
                    catch(e)
                    {
                        console.error(e);
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("구매 내역을 삭제하시겠습니까?", process);
        });
    }

}
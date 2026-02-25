class MGrpfncLoss
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.lossidx = GGC.Common.int(dat.lossidx);
        /* data */      this.lossitem = GGC.Common.varchar(dat.lossitem);
        /* data */      this.losscost = GGC.Common.int(dat.losscost);
        /* data */      this.losscomment = GGC.Common.varchar(dat.losscomment);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* data */      this.username = GGC.Common.varchar(dat.username);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getLossidx() { return this.lossidx; }
    /* data */      getLossitem() { return this.lossitem; }
    /* data */      getLosscost() { return this.losscost; }
    /* data */      getLosscomment() { return this.losscomment; }
    /* data */      getRegdt() { return this.regdt; }
    /* data */      getUsername() { return this.username; }

    /* ========================= */
    /* make */
    /* ========================= */
    /* custom */    getLosscostPriceColor() { return GGC.Common.priceColor(this.getLosscost()); }
    /* custom */    getPk() { return `grpno="${this.getGrpno()}" lossidx="${this.getLossidx()}"`; }

}


class MGrpfncLosses extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpfncLoss(dat));
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
                    <td col="delete"            ><button class="MGrpfncLoss-makeTable-btn-delete common-btn-outline" btn-type="cancel" ${model.getPk()}>삭제</td>
                    <td col="lossidx"           >${model.getLossidx()}</td>
                    <td col="regdt"             >${model.getRegdt()}</td>
                    <td col="lossitem"          >${model.getLossitem()}</td>
                    <td col="losscost"          >${model.getLosscostPriceColor()}</td>
                    <td col="losscomment"       >${model.getLosscomment()}</td>
                </tr>
            `;
        }

        html =
        `
            <div class="common-div-scrollX">
                <table class="MGrpfncLoss-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                    <thead>
                        <tr>
                            <th>삭제</th>
                            <th>번호</th>
                            <th>등록일</th>
                            <th>손실품목</th>
                            <th>손실금액</th>
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
        $(`${el} .MGrpfncLoss-makeTable-btn-delete`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let lossidx = $(this).attr("lossidx");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    try
                    {
                        let mApiResponse = Api.GrpfncLoss.deleteByPk(grpno, lossidx);
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
            Common.confirm2("손실 내역을 삭제하시겠습니까?", process);
        });
    }

}
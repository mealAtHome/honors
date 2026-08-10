class _MCommon
{
    constructor(ajax)
    {
        this.isSucceed       = GGvalid.Api.isSucceed(ajax);
        this.code            = ajax.CODE;
        this.msg             = ajax.MSG;
        this.data            = ajax.DATA != undefined ? ajax.DATA : [];
        this.models          = [];
        this.pageflg         = ajax.PAGEFLG != undefined ? ajax.PAGEFLG : GGF.N;
        this.pagenum         = ajax.PAGENUM != undefined ? ajax.PAGENUM : Api.defaultPagenum;
        this.pagecnt         = ajax.PAGECNT != undefined ? ajax.PAGECNT : 0;
        this.perpage         = ajax.PERPAGE != undefined ? ajax.PERPAGE : Api.defaultPerpage;
        this.allcnt          = ajax.ALLCNT  != undefined ? ajax.ALLCNT  : 0;
        this.cnt             = ajax.CNT     != undefined ? ajax.CNT     : 0;
    }
    isSuccess() { return this.isSucceed; }
    getSucceed() { return this.isSucceed; }
    getCode() { return this.code; }
    getMsg() { return this.msg; }
    getData() { return this.data; }
    getPageflg() { return this.pageflg; }
    getPagenum() { return this.pagenum; }
    getPagecnt() { return this.pagecnt; }
    getPerpage() { return this.perpage; }
    getAllcnt() { return this.allcnt; }
    getCnt() { return this.cnt; }

    isPagenation() { return this.getPageflg() === GGF.Y; }

    static getAjaxSucceed(arr=[])
    {
        let ajax =
        {
            CODE  : Api.succeed,
            MSG   : "",
            COUNT : arr.length,
            DATA  : arr,
        }
        return ajax;
    }
    static getFailed(message="error")
    {
        let ajax =
        {
            CODE  : Api.failed,
            MSG   : message,
            COUNT : 0,
            DATA  : [],
        }
        return ajax;
    }
    static fromArr   (arr=[] , clz=null) { return new clz(_MCommon.getAjaxSucceed(arr)); }
    static fromDat   (dat={} , clz=null) { return new clz(dat); }
    static fromModel (model  , clz=null) { return new clz(model); }

    getModels()
    {
        if(this.models == undefined || this.models == null)
            this.models = [];
        return this.models;
    }
    hasModels() { return this.getModels().length > 0; }
    getModel() { return this.getModels().length == 0 ? null : this.getModels()[0]; }
    hasModel()
    {
        if(this.getModels().length == 0)  return false;
        return true;
    }

    /* ================================ */
    /* make pagenation */
    /* ================================ */
    mergePagenation(html)
    {
        /* pagenation */
        if(this.getPagecnt() > 1 && this.getPageflg() === GGF.Y)
        {
            let pagenation = this.getPagenation();
            html = pagenation + html + pagenation;
        }
        return html;
    }
    getPagenation()
    {
        let btncnt = 5; /* btn per page */
        let pagenum = this.pagenum;
        let pagecnt = this.pagecnt;
        let allcnt = this.allcnt;

        let startPage = pagenum - ((pagenum - 1) % btncnt);

        if(allcnt <= 1)
            return "";

        let pagenationHtml =
        `
            <div class="common-div-pagenationTop">
                ${startPage > 1 ? `<div class="common-btn-pagenationBtn common-tap" to_page="${(startPage-1) < 1 ? 1 : (startPage-1)}">&lt;</div>` : ""}
                <div class="common-div-pagenationBtnTop">
        `;
        for(let i = startPage; i <= pagecnt && i < startPage+btncnt; i++)
        {
            let isTab = i == pagenum ? `tab="tab"` : "";
            pagenationHtml += `<div class="common-btn-pagenationBtn commonEvent-div-pagenationBtn common-tap" to_page="${i}" ${isTab}>${i}</div>`;
        }
        pagenationHtml +=
        `
                </div>
                ${startPage + btncnt <= pagecnt ? `<div class="common-btn-pagenationBtn common-tap" to_page="${(startPage+btncnt) > pagecnt ? pagecnt : (startPage+btncnt)}">&gt;</div>` : ""}
            </div>
        `;
        return pagenationHtml;
    }
    static getToPage(el)
    {
        let toPage = el.attr("to_page");
        return toPage;
    }

}
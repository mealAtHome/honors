class _MCommon
{
    constructor(ajax)
    {
        this.isSucceed       = GGvalid.Api.isSucceed(ajax);
        this.code            = ajax.CODE;
        this.msg             = ajax.MSG;
        this.data            = ajax.DATA != undefined ? ajax.DATA : [];
        this.models          = [];
        this.pageflg         = ajax.PAGEFLG != undefined ? ajax.PAGEFLG : GGF.N;                /* 현재 페이지 */
        this.pagenum         = ajax.PAGENUM != undefined ? ajax.PAGENUM : Api.defaultPagenum;   /* 현재 페이지 */
        this.pagecnt         = ajax.PAGECNT != undefined ? ajax.PAGECNT : 0;                    /* 페이지 수 */
        this.perpage         = ajax.PERPAGE != undefined ? ajax.PERPAGE : Api.defaultPerpage;   /* 페이지 당 표시 수 */
        this.allcnt          = ajax.ALLCNT  != undefined ? ajax.ALLCNT  : 0;                    /* 총 모델 수 */
        this.cnt             = ajax.CNT     != undefined ? ajax.CNT     : 0;                    /* 현재 조회 수 */
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



    /* 변환 함수 */
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
    static fromArr   (arr=[] , clz=null) { return new clz(_MCommon.getAjaxSucceed(arr)); }
    static fromDat   (dat={} , clz=null) { return new clz(dat); }
    static fromModel (model  , clz=null) { return new clz(model); }

    /* 모델 반환 및 체크 */
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
    /* 페이지네이션 html을 반환한다 */
    /* ================================ */
    getPagenation()
    {
        let pagenum = this.pagenum;
        let pagecnt = this.pagecnt;
        let allcnt = this.allcnt;

        let startPage = pagenum - (pagenum % 5) + 1;

        if(allcnt <= 1)
            return "";

        let pagenationHtml =
        `
            <div class="common-div-pagenationTop">
                ${startPage > 1 ? `<div class="common-btn-pagenationBtn common-tap" to_page="${(startPage-1) < 1 ? 1 : (startPage-1)}">&lt;</div>` : ""}
                <div class="common-div-pagenationBtnTop">
        `;
        for(let i = startPage; i <= pagecnt && i < startPage+5; i++)
        {
            let isTab = i == pagenum ? `tab="tab"` : "";
            pagenationHtml += `<div class="common-btn-pagenationBtn commonEvent-div-pagenationBtn common-tap" to_page="${i}" ${isTab}>${i}</div>`;
        }
        pagenationHtml +=
        `
                </div>
                ${startPage + 5 <= pagecnt ? `<div class="common-btn-pagenationBtn common-tap" to_page="${(startPage+5) > pagecnt ? pagecnt : (startPage+5)}">&gt;</div>` : ""}
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
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
    getCount() { return this.count; }
    getCode() { return this.code; }
    getMsg() { return this.msg; }
    getData() { return this.data; }
    getPageflg() { return this.pageflg; }
    getPagenum() { return this.pagenum; }
    getPagecnt() { return this.pagecnt; }
    getPerpage() { return this.perpage; }
    getAllcnt() { return this.allcnt; }
    getCnt() { return this.cnt; }

    isSuccess() { return this.isSucceed; }

    /**
     * 클래스 안에서 클래스를 정의할 때 사용
     *
     * @param {*} arr
     * @param {*} clz
     * @returns
     */
    static makeInstance(dat, propname, clz=null)
    {
        let arr = [];
        if(dat[propname] != undefined)
            arr = dat[propname];

        let ajax =
        {
            CODE  : Api.succeed,
            MSG : "",
            COUNT : arr.length,
            DATA  : arr,
        }
        let rslt = new clz(ajax);
        return rslt;
    }

    /**
     * 클래스 안에서 클래스를 정의할 때 사용
     * 조회한 레코드 자체에 다른 인스턴스를 정의할 수 있을 때 사용
     * @param {*} arr
     * @param {*} clz
     * @returns
     */
    static makeInstanceFromDat(dat, clz=null)
    {
        let rslt = new clz(dat);
        return rslt;
    }

    /* 모델만 단독으로 리턴 (확정적으로 1레코드를 조회하였을 때 사용) - 데이터가 없다면 null 반환 */
    static makeInstanceModel(dat, propname, clz=null)
    {
        let models = _MCommon.makeInstance(dat, propname, clz);
        let model  = models.getModel();
        return model;
    }

    /**
     * 클래스 안에서 클래스를 정의할 때 사용
     *
     * @param {*} arr
     * @param {*} clz
     * @returns
     */
    static makeInstanceArr(arr, clz=null)
    {
        if(arr == undefined)
            arr = [];

        let ajax =
        {
            CODE  : Api.succeed,
            MSG : "",
            COUNT : arr.length,
            DATA  : arr,
        }
        let rslt = new clz(ajax);
        return rslt;
    }

    getModels()
    {
        if(this.models == undefined || this.models == null)
            this.models = [];
        return this.models;
    }
    getModel()
    {
        if(this.getModels() == null)     return null;
        if(this.getModels().length == 0) return null;
        return this.getModels()[0];
    }
    hasModels() { return this.hasModel(); }
    hasModel()
    {
        if(this.getModels() == null)      return false;
        if(this.getModels().length == 0)  return false;
        return true;
    }

    /* ================================ */
    /* API 결과 확인 */
    /* ================================ */
    validation()
    {
        if(!this.isSucceed)
            return GGhtml.getCard('failed', this.msg);
        return "";
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
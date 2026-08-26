class MAddrcode
{
    constructor(dat)
    {
        this.addrcode    = GGC.Common.bigint(dat.addrcode);
        this.addrstrfull = GGC.Common.varchar(dat.addrstrfull);
        this.addrdepth   = GGC.Common.int(dat.addrdepth);
    }

    getAddrcode()    { return this.addrcode; }
    getAddrstrfull() { return this.addrstrfull; }
    getAddrdepth()   { return this.addrdepth; }
}


class MAddrcodes extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MAddrcode(dat));
        }
    } /* constructor */

    /* ========================= */
    /* 지역검색 결과 목록 */
    /* ========================= */
    make(el="")
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html += `<div class="MAddrcode-make-div-row" addrcode="${model.getAddrcode()}" addrstrfull="${model.getAddrstrfull()}">${model.getAddrstrfull()}</div>`;
        }
        $(el).html(html);
    }

}

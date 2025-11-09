class MYearMonth
{
    constructor(dat)
    {
        this.year  = dat.year;
        this.month = dat.month;
        this.cnt   = dat.cnt;
    }
    getYear()   { return this.year; }
    getMonth()  { return this.month; }
    getCnt()    { return this.cnt; }
}

class MYearMonths extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MYearMonth(dat));
        }
    }

    /* ================================ */
    /* 연월 옵션 */
    /* ================================ */
    makeOption()
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.models[i];
            let year = model.getYear();
            let month = model.getMonth();
            let monthStr = month*1 < 10 ? "0"+month : month;
            let selected = "";
            if(i == this.getModels().length - 1)
                selected = "selected";

            html +=
            `
                <option value="${year}/${month}" year="${year}" month="${month}" ${selected}>
                    ${year}${$.i18n('(common)year')} ${monthStr}${$.i18n('(common)month')} (${model.getCnt()})
                </option>
            `;
        }
        return html;
    }


}
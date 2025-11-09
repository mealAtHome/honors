class MYearVO
{
    constructor(dat)
    {
        this.year = GGC.Common.data(dat.year , GGF.Server.FieldType.int, 0); /* data */
        this.cnt  = GGC.Common.data(dat.cnt  , GGF.Server.FieldType.int, 0); /* data */
    }
    getYear() { return this.year; } /* data */
    getCnt()  { return this.cnt; } /* data */
}

class MYearVOs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MYearVO(dat));
        }
    }

    makeOption()
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.models[i];
            let year = model.getYear();
            let selected = "";
            if(i == this.getModels().length - 1)
                selected = "selected";

            html +=
            `
                <option value="${year}" year="${year}" ${selected}>
                    ${year}년 (${model.getCnt()})
                </option>
            `;
        }
        return html;
    }
}
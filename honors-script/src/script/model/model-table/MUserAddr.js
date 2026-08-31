class MUserAddr
{
    constructor(dat)
    {
        /* data */ this.userno         = GGC.Common.char(dat.userno);
        /* data */ this.useraddridx    = GGC.Common.int(dat.useraddridx);
        /* data */ this.useraddrtitle  = GGC.Common.char(dat.useraddrtitle);
        /* data */ this.useraddrcode   = GGC.Common.bigint(dat.useraddrcode);
        /* data */ this.useraddrlat    = GGC.Common.double(dat.useraddrlat);
        /* data */ this.useraddrlng    = GGC.Common.double(dat.useraddrlng);
        /* data */ this.useraddrdefflg = GGC.Common.enum(dat.useraddrdefflg);
        /* data */ this.useraddrstr    = GGC.Common.varchar(dat.useraddrstr);
        /* data */ this.usinggrpcnt    = GGC.Common.int(dat.usinggrpcnt);
        /* data */ this.modidt         = GGC.Common.datetime(dat.modidt);
        /* data */ this.regdt          = GGC.Common.datetime(dat.regdt);
        /* custom */ this.pk           = `useraddridx="${this.useraddridx}"`;
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    getUserno() { return this.userno; }
    getUseraddridx() { return this.useraddridx; }
    getUseraddrtitle() { return this.useraddrtitle; }
    getUseraddrcode() { return this.useraddrcode; }
    getUseraddrlat() { return this.useraddrlat; }
    getUseraddrlng() { return this.useraddrlng; }
    getUseraddrdefflg() { return this.useraddrdefflg; }
    getUseraddrstr() { return this.useraddrstr; }
    getUsinggrpcnt() { return this.usinggrpcnt; }
    getModidt() { return this.modidt; }
    getRegdt() { return this.regdt; }

    /* custom */
    getPk() { return this.pk; }
    isDefault() { return this.getUseraddrdefflg() == GGF.Y; }
}


class MUserAddrs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MUserAddr(dat));
        }
    } /* constructor */

    /* ========================= */
    /* 주소관리 목록 */
    /* ========================= */
    make(el="")
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html +=
            `
                <div class="MUserAddr-make-div-top common-div-card" ${model.getPk()}>
                    <div class="common-flexBetween">
                        <div class="common-flexCenterSm">
                            <span class="common-strong">${model.getUseraddrtitle()}</span>
                            ${model.isDefault() ? `<span class="common-card" card-type="mini" card-color="pstv">기본주소</span>` : ""}
                        </div>
                        <div class="common-flexCenterSm">
                            ${model.isDefault() ? "" : `<button class="MUserAddr-make-btn-setDefault common-btn-outer common-fonts08" ${model.getPk()}>기본으로 설정</button>`}
                            <button class="MUserAddr-make-btn-edit common-btn-outer common-fonts08" ${model.getPk()}>수정</button>
                            <button class="MUserAddr-make-btn-delete common-btn-outer common-fonts08" btn-type="cancel" ${model.getPk()}>삭제</button>
                        </div>
                    </div>
                    <div class="common-cushionHalfUp common-colorBody common-fonts09">${model.getUseraddrstr()}</div>
                    ${model.getUsinggrpcnt() > 0 ? `<div class="common-cushionHalfUp common-colorCmmt common-fonts08">이 주소를 사용중인 모임 ${model.getUsinggrpcnt()}개</div>` : ""}
                </div>
            `;
        }
        $(el).html(html);
    }

    /* ========================= */
    /* 주소 select 옵션 (모임관리 화면용) */
    /* ========================= */
    makeOption(el="")
    {
        let html = `<option value="">선택안함</option>`;
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html += `<option value="${model.getUseraddridx()}">${model.getUseraddrtitle()} (${model.getUseraddrstr()})</option>`;
        }
        $(el).html(html);
    }

}

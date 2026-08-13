class MGrpmtaga
{
    constructor(dat)
    {
        /* data */      this.grpno          = GGC.Common.char(dat.grpno);
        /* data */      this.tagidx         = GGC.Common.int(dat.tagidx);
        /* data */      this.tagname        = GGC.Common.char(dat.tagname);
        /* data */      this.tagcolorfont   = GGC.Common.char(dat.tagcolorfont);
        /* data */      this.tagcolorback   = GGC.Common.char(dat.tagcolorback);
        /* data */      this.tagregcnt      = GGC.Common.int(dat.tagregcnt);
        /* data */      this.modidt         = GGC.Common.datetime(dat.modidt);
        /* data */      this.regdt          = GGC.Common.datetime(dat.regdt);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getTagidx() { return this.tagidx; }
    /* data */      getTagname() { return this.tagname; }
    /* data */      getTagcolorfont() { return this.tagcolorfont; }
    /* data */      getTagcolorback() { return this.tagcolorback; }
    /* data */      getTagregcnt() { return this.tagregcnt; }
    /* data */      getModidt() { return this.modidt; }
    /* data */      getRegdt() { return this.regdt; }
    /* custom */    getPk() { return `grpno="${this.getGrpno()}" tagidx="${this.getTagidx()}"`; }

    /* ========================= */
    /* convert */
    /* ========================= */
    getTagStyle() { return `color:#${this.getTagcolorfont()};background-color:#${this.getTagcolorback()};`; }

    /* ========================= */
    /* make */
    /* ========================= */
    makePill()
    {
        return `<div class="MGrpmtaga-makePill-div common-card" card-type="mini" style="${this.getTagStyle()}" ${this.getPk()}>${this.getTagname()}</div>`;
    }

    make(btnHtml="")
    {
        return `
            <div class="MGrpmtaga-make-div-modelTop common-div-card">
                <div class="common-flexParentLR">
                    <div class="common-fonts09">
                        <div class="common-card" card-type="mini" style="${this.getTagStyle()}" ${this.getPk()}>${this.getTagname()}</div>
                    </div>
                    <div class="common-fonts09">
                        <div class="common-alertBadge">${this.getTagregcnt()}명</div>
                        ${btnHtml}
                    </div>
                </div>
            </div>
        `;
    }
}


class MGrpmtagas extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpmtaga(dat));
        }
    } /* constructor */

    /* ========================= */
    /* 태그관리 목록 (페이지네이션 불필요) */
    /* ========================= */
    make(el="")
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let btnHtml =
            `
                <button class="common-btn-outer MGrpmtaga-make-btn-bulk" ${model.getPk()}>멤버</button>
                <button class="common-btn-outer MGrpmtaga-make-btn-update" ${model.getPk()}>수정</button>
                <button class="common-btn-outer MGrpmtaga-make-btn-delete" btn-type="cancel" ${model.getPk()}>삭제</button>
            `;
            html += model.make(btnHtml);
        }
        $(el).html(html);

        /* 삭제 */
        $(`${el} .MGrpmtaga-make-btn-delete`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let tagidx = $(this).attr("tagidx");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse = Api.Grpmtaga.deleteByPk(grpno, tagidx);
                    if(mApiResponse.isSuccess())
                    {
                        Common.hideProgress();
                        Navigation.executeShow();
                        return;
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            };
            Common.confirm2("이 태그를 삭제하시겠습니까? 태그에 등록된 멤버 정보도 함께 사라집니다.", process);
        });

        /* 수정 */
        $(`${el} .MGrpmtaga-make-btn-update`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let tagidx = $(this).attr("tagidx");
            Navigation.moveFrontPage(Navigation.Page.B75GrpMemberTagUpdate, {option:"update", grpno:grpno, tagidx:tagidx});
        });

        /* 일괄처리(멤버지정) */
        $(`${el} .MGrpmtaga-make-btn-bulk`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let tagidx = $(this).attr("tagidx");
            Navigation.moveFrontPage(Navigation.Page.B76GrpMemberTagBulk, {grpno:grpno, tagidx:tagidx});
        });
    }

    /* ========================= */
    /* 멤버화면 상단, 태그 알약 목록 (멤버검색용) */
    /* ========================= */
    makePillForSearchGrpm(el="")
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html +=
            `
                <div class="MGrpmtaga-makePillForSearchGrpm-pill common-flexCenter common-pill" pill-type="mini" pill-color="main" ${model.getPk()} style="gap:0.3em;">
                    <div class="common-inline" style="width:0.7em; height:0.7em; border: 0.28em solid #${model.getTagcolorback()}; background-color:#${model.getTagcolorfont()}; border-radius:100%;"></div>
                    <span>${model.getTagname()}</span>
                </div>
            `;
        }
        $(el).html(html);
    }



}

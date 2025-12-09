class MSystemBoard
{
    constructor(dat)
    {
        /* dat */     this.sb_index       = GGC.Common.int(dat.sb_index);
        /* dat */     this.sb_level       = GGC.Common.enum(dat.sb_level);
        /* dat */     this.sb_title       = GGC.Common.char(dat.sb_title);
        /* dat */     this.is_open        = GGC.Common.enum(dat.is_open);
        /* dat */     this.is_cus         = GGC.Common.enum(dat.is_cus);
        /* dat */     this.is_biz         = GGC.Common.enum(dat.is_biz);
        /* dat */     this.is_main        = GGC.Common.enum(dat.is_main);
        /* dat */     this.url            = GGC.Common.char(dat.url);
        /* dat */     this.modidt         = GGC.Common.datetime(dat.modidt);
        /* dat */     this.regidt         = GGC.Common.datetime(dat.regidt);
        /* custom */  this.fullUrl        = `${ServerInfo.getServerHost()}/src/z-res/_system_board/${this.getUrl()}`;
        /* custom */  this.regidtPretty   = GGdate.toYMDDHI(new Date(this.getRegidt()));
        /* pk */      this.pk             = `sb_index="${this.sb_index}"`;
    }

    /* dat */     getSbIndex()          { return this.sb_index; }
    /* dat */     getSbLevel()          { return this.sb_level; }
    /* dat */     getSbTitle()          { return this.sb_title; }
    /* dat */     getIsOpen()           { return this.is_open; }
    /* dat */     getIsCus()            { return this.is_cus; }
    /* dat */     getIsBiz()            { return this.is_biz; }
    /* dat */     getIsMain()           { return this.is_main; }
    /* dat */     getUrl()              { return this.url; }
    /* dat */     getModidt()           { return this.modidt; }
    /* dat */     getRegidt()           { return this.regidt; }
    /* custom */  getFullUrl()          { return this.fullUrl; }
    /* custom */  getRegidtPretty()     { return this.regidtPretty; }
    /* pk */      getPk()               { return this.pk; }

    makeMainBanner()
    {
        let html =
        `
            <div
                class="MSystemBoard-makeMainBanner-div-top commonEvent-tag-hyperlink common-tap"
                hyperlink="${Navigation.Page.Z21SystemBoardDetail}"
                hyperlink-viewmode="page"
                ${this.getPk()}
            >
                <div class="common-div-cushionD">
                    <span class="common-span-subcontent" style="margin-left:0.1em;">${this.getRegidtPretty()}</span><br>
                    <span class="common-span-content">${this.getSbTitle()}</span>
                </div>
                <img
                    class="MSystemBoard-makeMainBanner-img"
                    src="${this.getFullUrl()}/banner.png"
                >
            </div>
        `;
        return html;
    }

    makeHorizon()
    {
        let html =
        `
            <div
                class="MSystemBoard-makeHorizon-div-top commonEvent-tag-hyperlink common-tap"
                hyperlink="${Navigation.Page.Z21SystemBoardDetail}"
                hyperlink-viewmode="page"
                ${this.getPk()}
            >
                <table class="entity-common-tbl MSystemBoard-makeHorizon-table-info">
                    <tbody>
                        <tr>
                            <td>
                                <div class="MSystemBoard-makeHorizon-div-image" style="background-image:url('${this.getFullUrl()}/thumbnail.png')"></div>
                            </td>
                            <td>
                                <span class="common-span-block common-span-content">${this.getSbTitle()}</span>
                                <span class="common-span-block common-span-subcontent">${this.getRegidtPretty()}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        return html;
    }

}

class MSystemBoards extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MSystemBoard(dat));
        }
    }

    makeMainBanners(el="")
    {
        /* =============== */
        /* get each model's html */
        /* =============== */
        let html = "";
        for(let i in this.getModels())
            html += this.getModels()[i].makeMainBanner();

        /* =============== */
        /* set html */
        /* =============== */
        html =
        `
            <div class="MSystemBoards-makeMainBanners-top">
                ${html}
                <button class="MSystemBoards-makeMainBanners-btn-more common-btn-outline common-btn-more">공지사항 더 보기</button>
            </div>
        `;
        $(el).html(html);

        /* =============== */
        /* set event */
        /* =============== */

        /* 공지사항 더 보기 */
        $(`${el} .MSystemBoards-makeMainBanners-btn-more`).click(function()
        {
            Navigation.moveFrontPage(Navigation.Page.Z21SystemBoardList, {});
        });

    }

    makeHorizon(el="")
    {
        /* =============== */
        /* get each model's html */
        /* =============== */
        let html = "";
        for(let i in this.getModels())
            html += this.getModels()[i].makeHorizon();

        /* =============== */
        /* set html */
        /* =============== */
        html =
        `
            <div class="MSystemBoards-makeHorizon-div-top">
                ${html}
            </div>
        `;
        $(el).html(html);
    }
}
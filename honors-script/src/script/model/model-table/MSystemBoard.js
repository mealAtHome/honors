class MSystemBoard
{
    constructor(dat)
    {
        /* dat */     this.sbindex       = GGC.Common.int(dat.sbindex);
        /* dat */     this.sblevel       = GGC.Common.enum(dat.sblevel);
        /* dat */     this.sbtitle       = GGC.Common.char(dat.sbtitle);
        /* dat */     this.isopen        = GGC.Common.enum(dat.isopen);
        /* dat */     this.ismain        = GGC.Common.enum(dat.ismain);
        /* dat */     this.url           = GGC.Common.char(dat.url);
        /* dat */     this.modidt        = GGC.Common.datetime(dat.modidt);
        /* dat */     this.regidt        = GGC.Common.datetime(dat.regidt);
        /* custom */  this.fullUrl       = `${ServerInfo.getServerHost()}/src/z-res/_system_board/${this.getUrl()}`;
        /* custom */  this.regidtPretty  = GGdate.toYMDDHI(new Date(this.getRegidt()));
        /* pk */      this.pk            = `sbindex="${this.sbindex}"`;
    }

    /* dat */     getSbindex()          { return this.sbindex; }
    /* dat */     getSblevel()          { return this.sblevel; }
    /* dat */     getSbtitle()          { return this.sbtitle; }
    /* dat */     getIsopen()           { return this.isopen; }
    /* dat */     getIsmain()           { return this.ismain; }
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
            <div class="common-div-flex common-div-card commonEvent-tag-hyperlink common-tap"
                card-type="notice"
                hyperlink="${Navigation.Page.Z22SystemBoardDetail}"
                hyperlink-viewmode="page"
                ${this.getPk()}
            >
                <div class="common-div-dot"></div>
                <div>
                    <div class="common-content">${this.getSbtitle()}</div>
                    <div class="common-subcontent">${this.getRegidtPretty()}</div>
                </div>
            </div>
        `;
        /* <img class="MSystemBoard-makeMainBanner-img" src="${this.getFullUrl()}/banner.png"> */
        return html;
    }

    makeHorizon()
    {
        let html =
        `
            <div
                class="MSystemBoard-makeHorizon-div-top common-div-card common-tap commonEvent-tag-hyperlink common-flexBetween"
                hyperlink="${Navigation.Page.Z22SystemBoardDetail}"
                hyperlink-viewmode="page"
                ${this.getPk()}
            >
                <div class="common-flexVertical">
                    <span class="common-content">${this.getSbtitle()}</span>
                    <span class="common-subcontent">${this.getRegidtPretty()}</span>
                </div>
                <i class="ti ti-chevron-right common-colorCmmt"></i>
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
        let html = "";
        for(let i in this.getModels())
            html += this.getModels()[i].makeMainBanner();
        $(el).html(html);
    }

    makeHorizon(el="")
    {
        let html = "";
        for(let i in this.getModels())
            html += this.getModels()[i].makeHorizon();
        $(el).html(html);
    }
}
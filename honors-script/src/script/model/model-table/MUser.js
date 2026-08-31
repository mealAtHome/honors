class MUser
{
    constructor(dat)
    {
        /* data */      this.userno         = GGC.Common.char(dat.userno);
        /* data */      this.usertype       = GGC.Common.enum(dat.usertype);
        /* data */      this.id             = GGC.Common.char(dat.id);
        /* data */      this.pw             = GGC.Common.char(dat.pw);
        /* data */      this.img            = GGC.Common.char(dat.img);
        /* data */      this.name           = GGC.Common.char(dat.name);
        /* data */      this.birthyear      = GGC.Common.date(dat.birthyear);
        /* data */      this.phone          = GGC.Common.char(dat.phone);
        /* data */      this.email          = GGC.Common.char(dat.email);
        /* data */      this.hascarflg      = GGC.Common.enum(dat.hascarflg);
        /* data */      this.address        = GGC.Common.char(dat.address);
        /* data */      this.userloginedlat = GGC.Common.double(dat.userloginedlat);
        /* data */      this.userloginedlng = GGC.Common.double(dat.userloginedlng);
        /* data */      this.point          = GGC.Common.int(dat.point);
        /* data */      this.adminflg       = GGC.Common.enum(dat.adminflg);
        /* data */      this.modidt         = GGC.Common.datetime(dat.modidt);
        /* data */      this.regidt         = GGC.Common.datetime(dat.regidt);
        /* data */      this.priv_phone     = GGC.Common.enum(dat.priv_phone);
        /* custom */    this.hascarflgCvrt  = GGC.User.hascarflg(dat.hascarflg);
        /* custom */    this.img_           = GGC.User.img_(this.userno, this.img, false);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */
    getUserno() { return this.userno; }
    getUsertype() { return this.usertype; }
    getId() { return this.id; }
    getPw() { return this.pw; }
    getImg() { return this.img; }
    getName() { return this.name; }
    getBirthyear() { return this.birthyear; }
    getPhone() { return this.phone; }
    getEmail() { return this.email; }
    getHascarflg() { return this.hascarflg; }
    getAddress() { return this.address; }
    getUserloginedlat() { return this.userloginedlat; }
    getUserloginedlng() { return this.userloginedlng; }
    getPoint() { return this.point; }
    getAdminflg() { return this.adminflg; }
    getModidt() { return this.modidt; }
    getRegidt() { return this.regidt; }
    getPrivPhone() { return this.priv_phone; }

    /* custom */
    getHascarflgCvrt() { return this.hascarflgCvrt; }
    getImg_() { return this.img_; }
    getUsertypePill() { return GGC.User.usertypePill(this.usertype); }
    getBirthyearFont() { return GGC.User.birthyearFont(this.birthyear); }
    getAgeByBirthYear() { return GGC.User.getAgeByBirthYear(this.birthyear); }

    /* ========================= */
    /* is ? */
    /* ========================= */
    isUsertypeTemp() { return this.getUsertype() === GGF.User.Usertype.TEMP; }
    isAdmin() { return this.getAdminflg() === GGF.Y; }

    /* ========================= */
    /* make */
    /* ========================= */
    makeProfile()
    {
        let html = `<div class="MUser-profile-main" profile="none"></div>`;
        if(this.isUsertypeTemp())
            html = `<div class="MUser-profile-tempPrnt">${html}<span class="MUser-profile-tempSpan">임시</span></div>`;
        return html;
    }

}

class MUsers extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(var i in this.data)
        {
            var dat = this.data[i];
            this.models.push(new MUser(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html +=
            `
                <div class="MUser-make-div-modelTop common-div-card">
                    <span class="common-block common-strong">유저</span>
                    <span class="common-block"><span>${model.getName()}</span>${model.getBirthyearFont()}</span>
                    <span class="common-block common-fonts09">
                        ${model.getPhone()         != "" ? `<span class="common-block common-colorBody commonEvent-tag-phoneCall" phone-call="${model.getPhone()}">${model.getPhone()}</span>` : ""}
                        ${model.getHascarflgCvrt() != "" ? `<span class="common-block common-colorBody">${model.getHascarflgCvrt()}</span>` : ""}
                    </span>
                </div>
            `;
        }
        $(el).html(html);
    }
}
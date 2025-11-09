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
        /* data */      this.point          = GGC.Common.int(dat.point);
        /* data */      this.modidt         = GGC.Common.datetime(dat.modidt);
        /* data */      this.regidt         = GGC.Common.datetime(dat.regidt);
        /* custom */    this.hascarflgCvrt  = GGC.User.hascarflg(dat.hascarflg);
        /* custom */    this.img_           = GGC.User.img_(this.userno, this.img, false);
        /* custom */    this.usertypeCvrt   = GGC.User.usertypeCvrt(this.usertype);
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
    getPoint() { return this.point; }
    getModidt() { return this.modidt; }
    getRegidt() { return this.regidt; }

    /* custom */
    getHascarflgCvrt() { return this.hascarflgCvrt; }
    getImg_() { return this.img_; }
    getUsertypeCvrt() { return this.usertypeCvrt; }

    /* birthyear short ver */
    getBirthyearShort()
    {
        let rslt = "출생연도없음";
        if(this.birthyear != "")
            rslt = this.birthyear.substring(2, 4);
        return rslt;
    }

    /* ========================= */
    /* is ? */
    /* ========================= */
    isUsertypeTemp() { return this.usertype === GGF.User.Usertype.TEMP; }

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
                    <span class="common-tag-block common-tag-strong">유저</span>
                    <span class="common-tag-block">${model.getName()} ${model.getBirthyear() != "" ? `(${model.getBirthyearShort()})` : ""}</span>
                    <span class="common-tag-block common-tag-fontsize09">
                        ${model.getPhone()         != "" ? `<span class="common-tag-block common-tag-colorGrey">${model.getPhone()}</span>` : ""}
                        ${model.getHascarflgCvrt() != "" ? `<span class="common-tag-block common-tag-colorGrey">${model.getHascarflgCvrt()}</span>` : ""}
                    </span>
                </div>
            `;
        }
        $(el).html(html);
    }
}
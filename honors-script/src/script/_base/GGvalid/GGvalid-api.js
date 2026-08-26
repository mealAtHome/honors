GGvalid.Api =
{
    isSucceedAndHasData(ajax)
    {
        let rslt = true;
        if(!this.isSucceed(ajax)) rslt = false;
        if(!this.hasData(ajax))   rslt = false;
        return rslt;
    },
    isSuccess(ajax) { return this.isSucceed(ajax); },
    isSucceed(ajax)
    {
        let rslt = false;
        if(ajax.CODE == Api.succeed)
            rslt = true;

        return rslt;
    },
    hasData(ajax)
    {
        let rslt = false;
        if(ajax.DATA != undefined && ajax.DATA.length > 0)
            rslt = true;

        return rslt;
    },
}
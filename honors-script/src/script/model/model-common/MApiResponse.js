/*
    let mApiResponse = Api....
    if(mApiResponse.isSuccess())
    {
        Navigation.executeShow();
        return;
    }
 */
class MApiResponse extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        this.userno = GGC.Common.char(ajax.userno);
    }

    getUserno() { return this.userno; }

    /* ========================= */
    /* additional methods */
    /* ========================= */
}
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
        this.grpno  = GGC.Common.char(ajax.grpno || (ajax.DATA && ajax.DATA.grpno)); /* updateGrp.php는 BO 결과를 DATA 안에 담는다 */
    }

    getUserno() { return this.userno; }
    getGrpno()  { return this.grpno; }

    /* ========================= */
    /* additional methods */
    /* ========================= */
}
/* ================================ */
/* 서버에서 받아온 API에서 성공여부와, 데이터가 존재하는지 확인 */
/* ================================ */
GGvalid.Api =
{
    /* ajaxResult.CODE가 성공했고, 데이터도 존재하는지? */
    isSucceedAndHasData(ajax)
    {
        let rslt = true;
        if(!this.isSucceed(ajax)) rslt = false;
        if(!this.hasData(ajax))   rslt = false;

        return rslt;
    },

    /* ajaxResult.CODE가 성공했는지 */
    isSuccess(ajax) { return this.isSucceed(ajax); },
    isSucceed(ajax)
    {
        let rslt = false;
        if(ajax.CODE == Api.succeed)
            rslt = true;

        return rslt;
    },

    /* ajaxResult.DATA 가 존재하는지? */
    hasData(ajax)
    {
        let rslt = false;
        if(ajax.DATA != undefined && ajax.DATA.length > 0)
            rslt = true;

        return rslt;
    },

} /* end obj */
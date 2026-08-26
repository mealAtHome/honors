var ApiPr = {};
$.ajax.promise = function(
    ajaxData,
    funcName,
    noticeOK="none",
    noticeFail="toast"
)
{
    /* -------- */
    /* variables */
    /* -------- */
    let deferred = $.Deferred();
    let rslt = {};
    let ajaxURL = Navigation.getApiUrlByFuncName(funcName);

    if(ajaxURL == "")
    {
        rslt = Api.getResultError();
        rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
        return deferred.reject(rslt);
    }

    /* -------- */
    /* 필수 파라메터 추가 */
    /* -------- */
    ajaxData.MODE           = LOCALMODE;
    ajaxData.VERSION        = VERSION;
    ajaxData.LANG           = GGstorage.getLang();
    ajaxData.APIKEY         = GGstorage.getApikey();
    ajaxData.SERVICE_LAYER  = GGstorage.getAppmode();

    /* --------------- */
    /* execute ajax */
    /* --------------- */
    $.ajax
    ({
        method : "POST",
        url : ajaxURL,
        data : ajaxData,
        dataType : "json",
    })
    .done(function (rslt, status, responseObj)
    {
        rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
        deferred.resolve(rslt);
    })
    .fail(function (rslt, status, responseObj)
    {
        if(rslt.CODE == undefined)
            rslt = Api.getResultError();
        rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
        deferred.reject(rslt);
    });
    return deferred.promise();
};

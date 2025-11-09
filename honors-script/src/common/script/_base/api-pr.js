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

    /* validation */
    if(ajaxURL == "")
    {
        Common.toast("url 이 무효입니다.");
        rslt =
        {
            CODE  : "AX-0001",
            MSG : $.i18n('(ajax)failed to connect'),
            DATA  : null,
        };
        deferred.reject(rslt);
    }

    /* -------- */
    /* 필수 파라메터 추가 */
    /* -------- */
    ajaxData.LANG           = GGstorage.getLang();
    ajaxData.APIKEY         = GGstorage.getApikey();
    ajaxData.SERVICE_LAYER  = GGstorage.getAppmode();

    /* --------------- */
    /* execute ajax */
    /* --------------- */
    $.ajax({
        method      : "POST",
        // contentType : false,
        // processData : false,
        url         : ajaxURL,
        data        : ajaxData,
        dataType    : "json",
    })
    .done(function (rslt, status, responseObj)
    {
        rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
        deferred.resolve(rslt);
    })
    .fail(function (result, status, responseObj)
    {
        let rslt = Api.getResultError();
        rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
        deferred.reject(rslt);
    });
    return deferred.promise();
};

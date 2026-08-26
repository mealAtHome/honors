var GGdialog =
{
    /* ==================== */
    /* 앞으로 이동 (페이지/다이어로그 -> 다이어로그) */
    /*
        page : 불러올 페이지의 실제 경로
     */
    /* ==================== */
    show(page)
    {
        /* 이미 다이어로그가 떠 있는 상태라면 페이크다이어로그를 끌어올린 후, 페이지 로드 */
        if(
            $("#index-dialog-container").hasClass("index-dialog-bringUp") ||
            $("#index-dialog-container").hasClass("index-dialog-bringUpFast")
        )
        {
            $("#index-dialog-containerFake").addClass("index-dialog-fakeBringUp");
            Common.showProgress();
            setTimeout(function()
            {
                $("#index-dialog-container").load(page, ()=>
                {
                    $("#index-dialog-containerFake").removeClass("index-dialog-fakeBringUp");
                    Common.hideProgress();
                });
            }, ajaxDelayTime);
            return;
        }

        /* 현재 다이어로그가 아니라면, 페이지 로드 후, 다이어로그 끌어올리기 */
        $("#index-dialog-container").load(page, ()=>
        {
            $("#index-dialog-mask").show();
            $("#index-dialog-container").removeClass("index-dialog-pullDown");
            $("#index-dialog-container").removeClass("index-dialog-pullDownFast");
            $("#index-dialog-container").addClass("index-dialog-bringUp");
        });
    },

    /* ==================== */
    /* 다이어로그에서 다이어로그로 뒤로가기 */
    /*
        page : 불러올 페이지의 실제 경로
     */
    /* ==================== */
    moveBack(page)
    {
        Common.showProgress();

        /* == 1 == */
        $("#index-dialog-container").addClass("index-dialog-pullDownFast");
        $("#index-dialog-container").load(page, ()=>
        {
            $("#index-dialog-container").removeClass("index-dialog-pullDownFast");
            $("#index-dialog-container").addClass("index-dialog-bringUpFast");
        });
    },

    /* ==================== */
    /* 다이어로그 닫기 */
    /* ==================== */
    hide()
    {
        /* 이미 다이어로그가 숨겨져 있는 상태라면 스킵 */
        if(
            $("#index-dialog-container").hasClass("index-dialog-pullDown") ||
            $("#index-dialog-container").hasClass("index-dialog-pullDownFast")
        )
            return;

        /* 다이어로그 끌어내리기 */
        $("#index-dialog-mask").hide();
        $("#index-dialog-container").removeClass("index-dialog-bringUp");
        $("#index-dialog-container").removeClass("index-dialog-bringUpFast");
        $("#index-dialog-container").addClass("index-dialog-pullDown");

        /* 내용 삭제 */
        $("#index-dialog-container").html("");

        /*  */
        Navigation.executeShow();
    },

}
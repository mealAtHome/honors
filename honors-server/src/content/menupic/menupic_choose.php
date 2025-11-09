<!DOCTYPE html>
    <head>
        <?php require_once '../_header/header.php'; ?>
        <script>
            var CSPC =
            {
                code : "CSPC",
                Data :
                {
                    viewMode                : <?php getParam("viewMode", "page"); ?>,
                    option                  : <?php getParam("option"); ?>,
                    storeno              : <?php getParam("storeno"); ?>,
                    element                 : <?php getParam("element"); ?>,
                    executeShowWhenClose    : <?php getParam("executeShowWhenClose"); ?>,
                },
            }

            $(document).ready(function()
            {
                /* ========================= */
                /* page event */
                /* ========================= */
                $("#CSPC-btn-close").click(function()
                {
                    Navigation.moveBack();
                });

                /* ========================= */
                /* page init */
                /* ========================= */
                MFrame.init(CSPC.Data.viewMode, function()
                {
                    /* set html */
                    let html = PMenupicChoose.makeElement();
                    $("#CSPC-div-mainDiv").html(html);
                    PMenupicChoose.setEvent();

                    // console.log(CSPC.Data);

                    /* init */
                    PMenupicChoose.Data.viewMode              = CSPC.Data.viewMode;
                    PMenupicChoose.Data.option                = CSPC.Data.option;
                    PMenupicChoose.Data.storeno            = CSPC.Data.storeno;
                    PMenupicChoose.Data.element               = CSPC.Data.element;
                    PMenupicChoose.Data.executeShowWhenClose  = CSPC.Data.executeShowWhenClose;
                    PMenupicChoose.init();
                });
            });
        </script>
        <style>
        </style>
    </head>
    <body style="display:none;">
        <table class="common-tbl-dialogtitle">
            <tbody>
                <tr>
                    <td><span data-i18n="">메뉴사진선택</span></td>
                    <td>
                        <button id="CSPC-btn-close" class="common-btn-outline" onclick="">닫기</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="CSPC-div-mainDiv"></div>
    </body>
</html>

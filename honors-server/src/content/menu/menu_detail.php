<!DOCTYPE html>
    <head>
        <?php require_once '../_header/header.php'; ?>
        <script>
            var PMND =
            {
                code : "PMND",
                Data :
                {
                    viewMode   : <?php getParam("viewMode", "page"); ?>,
                    option     : <?php getParam("option", ""); ?>,
                    storeno    : <?php getParam("storeno", ""); ?>,
                    cartIndex  : <?php getParam("cartIndex", ""); ?>,
                    menuno     : <?php getParam("menuno", ""); ?>,
                },
            }

            $(document).ready(function()
            {
                /* ========================= */
                /* page event */
                /* ========================= */

                /* ========================= */
                /* page init */
                /* ========================= */
                /* ========================= */
                /* page init */
                /* ========================= */
                MFrame.init(PMND.Data.viewMode, function()
                {
                    /* menuDetail html */
                    let html = PMenuDetail.makeElement();
                    $("#PMND-div-menuDetail").html(html);
                    PMenuDetail.setEvent();

                    /* PMenuDetail setting */
                    PMenuDetail.Data.option     = PMND.Data.option;
                    PMenuDetail.Data.storeno = PMND.Data.storeno;
                    PMenuDetail.Data.cartIndex  = PMND.Data.cartIndex;
                    PMenuDetail.Data.menuno  = PMND.Data.menuno;
                    PMenuDetail.init();
                });
            });
        </script>
        <style>
        </style>
    </head>
    <body style="display:none;">
        <table class="common-tbl-title">
            <tbody>
                <tr>
                    <td><span data-i18n="">메뉴상세</span></td>
                    <td>
                        <div id="PMenuDetail-div-updateMenu" style="display:none;">
                            <button id="PMenuDetail-btn-menuUpdate" class="common-btn-inline">메뉴수정</button>
                            <button id="PMenuDetail-btn-menuCopy"   class="common-btn-inline">메뉴복사</button>
                            <button id="PMenuDetail-btn-menuDelete" class="common-btn-inline" btn_type="delete">메뉴삭제</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- --------------- -->
        <!-- 메뉴상세 -->
        <!-- --------------- -->
        <div id="PMenuDetail-div-menuDetail"></div>

        <!-- --------------- -->
        <!-- 각 상황에 따른 버튼 -->
        <!-- --------------- -->

        <!-- 카트에 넣기 -->
        <div id="PMenuDetail-div-saveCart" style="display:none">
            <button id="PMenuDetail-btn-saveCart" class="common-btn-footer">
                카트에 넣기<span id="PMenuDetail-span-finalPrice"></span>
            </button>
            <div class="common-div-footerBtnCushion"></div>
        </div>

        <!-- 상품 수정하기 / 삭제하기 -->

    </body>
</html>

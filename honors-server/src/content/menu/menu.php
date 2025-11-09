<!DOCTYPE html>
    <head>
        <?php require_once '../_header/header.php'; ?>
        <script>
            var MN =
            {
                code : "MN",
                Data :
                {
                    viewMode   : <?php getParam("viewMode", "page"); ?>,
                    menustatus : "",
                    storeno : "",
                },
                El :
                {

                },

                search(tab)
                {
                    Common.showProgress();
                    setTimeout(function()
                    {
                        /* 내 매장 가져오기 */
                        let mStore = Api.Store.getMyStore();
                        if(mStore == null)
                        {
                            Common.hideProgress();
                            return;
                        }
                        MN.Data.storeno = mStore.getStoreno();


                        let ajaxData =
                        {
                            TAB       : tab,
                            MENU_NAME : $("#MN-inputText-menuname").val(),
                        }
                        let mMenus = Api.Menu.Store.selectMenuForStoreMain(ajaxData);
                        mMenus.makeMenuListTable(MN.code, "#MN-div-menuList");
                        Common.hideProgress();
                    }, ajaxDelayTime);
                }
            }

            $(document).ready(function()
            {
                /* ========================= */
                /* page event */
                /* ========================= */
                /* --------------- */
                /* 탭 선택 */
                /* --------------- */
                $(".MN-td-menustatus").click(function()
                {
                    let tab = $(this).attr("menustatus");
                    MN.search(tab);
                });

                /* --------------- */
                /* 검색버튼 클릭 */
                /* --------------- */
                $("#MN-btn-search").click(function()
                {
                    let tab = $(".MN-td-menustatus[tab=tab]").attr("menustatus");
                    MN.search(tab);
                });

                /* --------------- */
                /* 검색초기화 */
                /* --------------- */
                $("#MN-btn-searchClear").click(function()
                {
                    $("#MN-inputText-menuname").val("");
                    let tab = $(".MN-td-menustatus[tab=tab]").attr("menustatus");
                    MN.search(tab);
                });

                /* --------------- */
                /* 메뉴생성 */
                /* --------------- */
                $("#MN-btn-newMenu").click(function()
                {
                    let data =
                    {
                        option: "insert",
                        storeno: MN.Data.storeno,
                    }
                    let url = Navigation.moveFront("page", Navigation.Page.PR, data, true);
                    if(GGstorage.isWeb())
                        window.location.href = url;
                });

                /* ========================= */
                /* page init */
                /* ========================= */
                MFrame.init(MN.Data.viewMode, function()
                {
                    /* menustatus 선택 */
                    let menustatus = MN.Data.menustatus != "" ? MN.Data.menustatus : "all";
                    $(`.MN-td-menustatus[menustatus=${menustatus}]`).click();
                });
            });
        </script>
        <style>
        </style>
    </head>
    <body style="display:none;">
        <table class="common-tbl-title"><tbody><tr><td><span data-i18n="">주문</span></td></tr></tbody></table>

        <!-- 메뉴 리스트 -->
        <div class="common-div-cushion">
            <div class="common-div-scrollX">
                <table class="common-tbl-tab commonEvent-tbl-tab">
                    <tbody>
                        <tr>
                            <td tab="" class="MN-td-menustatus" menustatus="all"            >전체</td>
                            <td tab="" class="MN-td-menustatus" menustatus="displayY"       >표시</td>
                            <td tab="" class="MN-td-menustatus" menustatus="displayYstockN" >표시(재고없음)</td>
                            <td tab="" class="MN-td-menustatus" menustatus="displayN"       >미표시</td>
                            <td tab="space"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 메뉴 검색 -->
        <div class="common-div-cushion">
            <table>
                <tbody>
                    <tr>
                        <td style="white-space:nowrap;">
                            <span>메뉴명</span>
                            <input
                                id="MN-inputText-menuname"
                                type="text"
                                class="common-text-insertPage common-input-text"
                                style="min-width:10em;"
                                maxlength="20"
                            >
                            <button id="MN-btn-search"      class="common-btn-inline">검색</button>
                            <button id="MN-btn-searchClear" class="common-btn-noline">검색초기화</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="common-div-cushion">
            <div id="MN-div-menuList" class="common-div-scrollX"></div>
        </div>
        <div class="common-div-cushion">
            <button id="MN-btn-newMenu" class="common-btn-gradientBig">메뉴생성</button>
        </div>
    </body>
</html>

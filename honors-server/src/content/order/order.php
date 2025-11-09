<!DOCTYPE html>
    <head>
        <?php require_once '../_header/header.php'; ?>
        <script>
            var ODR =
            {
                code : "ODR",
                Data :
                {
                    viewMode   : <?php getParam("viewMode", "page"); ?>,
                },
                El :
                {

                },
            }

            $(document).ready(function()
            {
                /* ========================= */
                /* page event */
                /* ========================= */

                /* --------------- */
                /* 주문검색 */
                /* --------------- */
                $(".ODR-td-orderstatus").click(function()
                {
                    let orderstatus = $(this).attr("orderstatus");
                    let mOrderStores = Api.Ordering.Store.selectByOrderstatus(orderstatus);
                    mOrderStores.makeOrders(ODR.code, "#ODR-div-orderList");
                });

                /* ========================= */
                /* page init */
                /* ========================= */
                MFrame.init(ODR.Data.viewMode, function()
                {
                    $(".ODR-td-orderstatus[orderstatus=all]").click();
                });
            });
        </script>
        <style>
        </style>
    </head>
    <body style="display:none;">
        <table class="common-tbl-title"><tbody><tr><td><span data-i18n="">주문</span></td></tr></tbody></table>
        <div class="common-div-scrollX">
            <table class="common-tbl-tab commonEvent-tbl-tab">
                <tbody>
                    <tr>
                        <td tab="" class="ODR-td-orderstatus" orderstatus="all"      >전체</td>
                        <td tab="" class="ODR-td-orderstatus" orderstatus="confirm"  >미확인주문</td>
                        <td tab="" class="ODR-td-orderstatus" orderstatus="cook"     >요리중</td>
                        <td tab="" class="ODR-td-orderstatus" orderstatus="delivery" >배달중</td>
                        <td tab="" class="ODR-td-orderstatus" orderstatus="complete" >완료됨</td>
                        <td tab="" class="ODR-td-orderstatus" orderstatus="cancel"   >취소됨</td>
                        <td tab="space"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="common-div-scrollX common-div-cushionUD">
            <div id="ODR-div-orderList"></div>
        </div>
    </body>
</html>

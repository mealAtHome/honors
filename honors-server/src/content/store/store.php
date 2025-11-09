<!DOCTYPE html>
    <head>
        <?php require_once '../_header/header.php'; ?>
        <script>
            var STR =
            {
                code : "STR",
                Data :
                {

                },
                El :
                {

                },
            }

            $(document).ready(function()
            {
                /* ========================= */
                /* frames */
                /* ========================= */
                if(!MFrame.init())
                    return;

                /* ========================= */
                /* page init */
                /* ========================= */
                /* 내 매장 가져오기 */
                let mStore = Api.Store.getMyStore();
                if(mStore == null)
                {
                    Common.hideProgress();
                    return;
                }

                /* 매장 라벨 표시 */
                let html = mStore.makeLabel("2em");
                $("#SH-div-myStore").html(html);

                /* 매장 상태 표시 */
                mStore.makeStoreStatusDetail("#STR-div-storeStatusDetail");
            });
        </script>
        <style>
        </style>
    </head>
    <body style="display:none;">
        <div class="common-div-pageTop">
            <table class="common-tbl-title">
                <tbody>
                    <tr>
                        <td><span data-i18n="">매장</span></td>
                    </tr>
                </tbody>
            </table>
            <div id="STR-div-storeStatusDetail"></div>
        </div>
    </body>
</html>

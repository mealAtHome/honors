<!DOCTYPE html>
    <head>
        <?php require_once '../_header/header.php'; ?>
        <script>
            var MNU =
            {
                code : "MNU",
                Data :
                {
                    viewMode   : <?php getParam("viewMode", "page"); ?>,
                    option     : <?php getParam("option", ""); ?>,
                    storeno : <?php getParam("storeno", ""); ?>,
                    menuno  : <?php getParam("menuno", ""); ?>,
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
                MFrame.init(MNU.Data.viewMode, function()
                {
                    /* menuDetail html */
                    let html = PMenuUpdate.makeElement();
                    $("#MNU-div-topDiv").html(html);
                    PMenuUpdate.setEvent();

                    /* PMenuUpdate setting */
                    PMenuUpdate.Data.option     = MNU.Data.option;
                    PMenuUpdate.Data.storeno = MNU.Data.storeno;
                    PMenuUpdate.Data.menuno  = MNU.Data.menuno;
                    PMenuUpdate.init();
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
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="MNU-div-topDiv"></div>
    </body>
</html>

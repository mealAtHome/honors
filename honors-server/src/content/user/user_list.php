<!DOCTYPE html>
    <head>
        <?php require_once '../_header/header.php'; ?>

        <script>

            var UL =
            {
                code : "UL",
                Data :
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

                /* --------------- */
                /* init */
                /* --------------- */

                /* ----- */
                /* get user list */
                /* ----- */
                let mUsers = Api.User.selectAll();
                mUsers.makeUsersForUpdate(UL.code, "#UL-div-users");
            });
        </script>
    </head>
    <body style="display:none;">
        <table class="common-tbl-subtitle">
            <tbody>
                <tr>
                    <td><span data-i18n="">ユーザーリスト</span></td>
                </tr>
            </tbody>
        </table>
        <div id="UL-div-users" class="common-div-cushion"></div>

    </body>
</html>

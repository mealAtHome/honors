<!DOCTYPE html>
    <head>
        <?php require_once '../_header/header.php'; ?>
        <script>
            var LN =
            {
                code : "LN",
                Data : {},
            }
            $(document).ready(function()
            {
                /* ========================= */
                /* set event */
                /* ========================= */
                $("#LN-btn-login").click(function()
                {
                    /* get var */
                    let id = $(`#LN-input-id`).val();
                    let pw = $(`#LN-input-pw`).val();
                    let rslt = Api.User.login(id, pw);
                    if(rslt)
                    {
                        // alert(Navigation.getURL(Navigation.Page.Web.INDEX));
                        window.location.href = Navigation.getURL(Navigation.Page.Web.INDEX);
                    }
                });

                /* ========================= */
                /* frames */
                /* ========================= */
                MFrame.init(GGF.System.ViewMode.PAGE, null);
            });
        </script>
        <style>
            #LN-tbl-login
            {
                margin:1em auto;
            }
            #LN-tbl-login > tbody > tr > td { text-align:center; font-size:1em; padding:0.5em; }
            .LN-input-text                  { min-width: 16em; }
            #LN-btn-login                   { padding:0.8em 2em;}
        </style>
    </head>
    <body style="display:none;">
        <table class="common-tbl-subtitle">
            <tbody>
                <tr>
                    <td><span data-i18n="">로그인</span></td>
                </tr>
            </tbody>
        </table>

        <!-- LOGIN FORM -->
        <table id="LN-tbl-login">
            <tbody>
                <tr>
                    <td>아이디</td>
                </tr>
                <tr>
                    <td>
                        <input
                            id="LN-input-id"
                            type="text"
                            class="LN-input-text common-input-text"
                            max=255/>
                    </td>
                </tr>
                <tr>
                    <td>비밀번호</td>
                </tr>
                <tr>
                    <td>
                        <input
                            id="LN-input-pw"
                            type="password"
                            class="LN-input-text common-input-text"
                            max=255/>
                    </td>
                </tr>
                <tr>
                    <td colspan=2 style="text-align:center;">
                        <button id="LN-btn-login" class="common-btn-inline">로그인</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- LOGIN FORM END -->

        <p style="text-align:center;">

        </p>

    </body>
</html>

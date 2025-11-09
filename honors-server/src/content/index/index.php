<!DOCTYPE html>
    <head>
        <?php require_once '../_header/header.php'; ?>
        <script>
            var IDX =
            {
                code : "IDX",
                Data :
                {
                    pageno : 1,
                },
                El :
                {

                },
            }

            $(document).ready(function()
            {
                /* ========================= */
                /* page init */
                /* ========================= */
                MFrame.init(GGF.System.ViewMode.PAGE, null);
            });
        </script>
        <style>
            .IDX-tbl-normal { font-size:1.1em; }
            .IDX-tbl-normal > tbody > tr > td { text-align:center; }
        </style>
    </head>
    <body style="display:none;">
        <table class="common-tbl-title">
            <tbody>
                <tr>
                    <td><span data-i18n="">편리한 주문 어플리케이션</span></td>
                </tr>
            </tbody>
        </table>
        <ul>
            <li></li>
            <li></li>
        </ul>

        <button id="" class="common-btn-gradientBig">시작하기</button>
    </body>
</html>

var GGF =
{
    Y : 'y',
    N : 'n',
    SYSTEM: "system",

    none  : "none",
    toast : "toast",
    alert : "alert",
    NONE  : "none",
    TOAST : "toast",
    ALERT : "alert",

    insert : "insert",
    update : "update",

    /* ========================= */
    /* 서버관련 값 */
    /* ========================= */
    Server :
    {
        FieldType :
        {
            enum : "enum", /* GGF.Server.FieldType.enum */
            char : "char", /* GGF.Server.FieldType.char */
            varchar : "varchar", /* GGF.Server.FieldType.varchar */
            date : "date", /* GGF.Server.FieldType.date */
            datetime : "datetime", /* GGF.Server.FieldType.datetime */
            time : "time", /* GGF.Server.FieldType.time */
            int : "int", /* GGF.Server.FieldType.int */
            tinyint : "tinyint", /* GGF.Server.FieldType.tinyint */
            double : "double", /* GGF.Server.FieldType.double */
            float : "float", /* GGF.Server.FieldType.float */
        }
    },

    GGdate:
    {
        PointOfDate :
        {
            WITHIN   : "within",    /* GGF.GGdate.PointOfDate.WITHIN */
            PASSED   : "passed",    /* GGF.GGdate.PointOfDate.PASSED */
            UPCOMING : "upcoming",  /* GGF.GGdate.PointOfDate.UPCOMING */
        }
    },

    Color:
    {
        MAIN : "main", /* GGF.Color.MAIN */
        DARK : "dark", /* GGF.Color.DARK */
        HEAD : "head", /* GGF.Color.HEAD */
        SIDE : "side", /* GGF.Color.SIDE */
        BODY : "body", /* GGF.Color.BODY */
        CMMT : "cmmt", /* GGF.Color.CMMT */
        EDGE : "edge", /* GGF.Color.EDGE */
        LINE : "line", /* GGF.Color.LINE */
        PSTV : "pstv", /* GGF.Color.PSTV */
        ALLW : "allw", /* GGF.Color.ALLW */
        PROG : "prog", /* GGF.Color.PROG */
        NTCE : "ntce", /* GGF.Color.NTCE */
        INFO : "info", /* GGF.Color.INFO */
        DTIL : "dtil", /* GGF.Color.DTIL */
        HOLD : "hold", /* GGF.Color.HOLD */
        CNCL : "cncl", /* GGF.Color.CNCL */
        WARN : "warn", /* GGF.Color.WARN */
        ERRR : "errr", /* GGF.Color.ERRR */
        NGTV : "ngtv", /* GGF.Color.NGTV */
        ENDD : "endd", /* GGF.Color.ENDD */
        WHIT : "whit", /* GGF.Color.WHIT */
    },

    /* ========================= */
    /* DB아님, 시스템에서 사용하는 값 */
    /* ========================= */
    System :
    {
        Alert :
        {
            NONE  : "NONE",         /* GGF.System.Alert.NONE */
            TOAST : "TOAST",        /* GGF.System.Alert.TOAST */
            ALERT : "ALERT",        /* GGF.System.Alert.ALERT */
        },

        AlertIcon :
        {
            INFO     : "info",      /* GGF.System.AlertIcon.INFO */
            WARNING  : "warning",   /* GGF.System.AlertIcon.WARNING */
            ERROR    : "error",     /* GGF.System.AlertIcon.ERROR */
            SUCCESS  : "success",   /* GGF.System.AlertIcon.SUCCESS */
        },

        /* viewMode */
        ViewMode :
        {
            PAGE    : "page",       /* GGF.System.ViewMode.PAGE    */
            DIALOG  : "dialog",     /* GGF.System.ViewMode.DIALOG  */
        },

        /* 앱 접속모드 */
        AppMode :
        {
            CUS : "cus", /* GGF.Project.Appmode.CUS : customer */
            MNG : "mng", /* GGF.Project.Appmode.MNG : leader */
            ADM : "adm", /* GGF.Project.Appmode.ADM : admin */
        },

        /* 기기종류 > 대분류 */
        DeviceKind:
        {
            MOBILE  : "mobile",     /* GGF.System.DeviceKind.MOBILE */
            PC      : "pc",         /* GGF.System.DeviceKind.PC */
            WEB     : "web",        /* GGF.System.DeviceKind.WEB */
        },

        /* 기기종류 > 소분류 */
        DeviceKindSmall:
        {
            IOS : "ios",
            AND : "and",
            PC  : "pc",
            WEB : "web",
        },
    },
}
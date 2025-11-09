<?php

class GGF
{
    /* 주요필드 */
    const Y = "y";
    const N = "n";

    /* 시스템 */
    const EXECUTOR = "EXECUTOR";
    const CODE = "CODE";
    const MSG = "MSG";
    const DATA = "DATA";

    /* 공통 필드명 */
    const ID = "id";
    const USERNO = "userno";
    const APIKEY = "apikey";
    const GRPNO = "grpno"; /* 그룹번호 */
    const STORENO = "storeno";
    const RIDERNO = "riderno";
    const ORDERNO = "orderno";

    /* 권한명 */
    const PRIA = "pria"; /* 전체권한 */
    const PRIB = "prib"; /* 업체권한 */
    const PRIC = "pric"; /* 고객권한 */
    const PRIZ = "priz"; /* 외부권한 */

    /* 각 모델명 */
    const CARTMENU              = "cartmenu";
    const CARTMENUOPT           = "cartmenuopt";
    const CARTMENUOPT_DETAIL    = "cartmenuopt_detail";
    const CARTMENU_RECOMMEND    = "cartmenu_recommend";

    /* 기타 필드 */
    const OPTION = "OPTION";
    const SERVICE_LAYER__CUS = "cus";
    const SERVICE_LAYER__BIZ = "biz";
    const SERVICE_LAYER__ADM = "adm";

    /* 배치명 */
    const BATCH__RIDER_DELIVERYMATCH_PROCESS = "riderDeliverymatchProcess";
}
?>
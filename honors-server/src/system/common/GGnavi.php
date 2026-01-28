<?php
/* require_once GGnavi::getGGcoordinate(); */
class GGnavi
{
    /* Utils */
    static public function getGGcoordinate()                                        { require_once ROOT."/src/system/common/GGcoordinate.php"; }
    static public function getImageUtils()                                          { require_once ROOT."/src/system/utils/ImageUtils.php"; }

    /* ABO :  */
    static public function getPaymentABO()                                          { require_once ROOT."/src/system/bo-abstract/PaymentABO.php"; }

    /* BO : ref, system */
    static public function getAddressSidoBO()                                       { require_once ROOT."/src/system/bo-ref/AddressSidoBO.php"; }
    static public function getAddressSigunguBO()                                    { require_once ROOT."/src/system/bo-ref/AddressSigunguBO.php"; }
    static public function getBankBO()                                              { require_once ROOT."/src/system/bo-ref/BankBO.php"; }
    static public function getSystemBoardBO()                                       { require_once ROOT."/src/system/bo-ref/SystemBoardBO.php"; }
    static public function getRefHolidayBO()                                        { require_once ROOT.'/src/system/bo-ref/RefHolidayBO.php'; }
    static public function getSystemBatchBO()                                       { require_once ROOT."/src/system/bo-ref/SystemBatchBO.php"; }

    /* BO : for users */
    static public function getAddrBO()                                              { require_once ROOT."/src/system/bo-table/AddrBO.php"; }
    static public function getBankaccountBO()                                       { require_once ROOT."/src/system/bo-table/BankaccountBO.php"; }
    static public function getClsBO()                                               { require_once ROOT."/src/system/bo-table/ClsBO.php"; }
    static public function getClzcancelBO()                                         { require_once ROOT."/src/system/bo-table/ClzcancelBO.php"; }
    static public function getClslineup2BO()                                        { require_once ROOT."/src/system/bo-table/Clslineup2BO.php"; }
    static public function getClspurchaseBO()                                       { require_once ROOT."/src/system/bo-table/ClspurchaseBO.php"; }
    static public function getClspurchasehistBO()                                   { require_once ROOT."/src/system/bo-table/ClspurchasehistBO.php"; }
    static public function getClssettleBO()                                         { require_once ROOT."/src/system/bo-table/ClssettleBO.php"; }
    static public function getClssettlehistBO()                                     { require_once ROOT."/src/system/bo-table/ClssettlehistBO.php"; }
    static public function getClssettletmpBO()                                      { require_once ROOT."/src/system/bo-table/ClssettletmpBO.php"; }
    static public function getIdxBO()                                               { require_once ROOT."/src/system/bo-table/_IdxBO.php"; }
    static public function getGrpBO()                                               { require_once ROOT."/src/system/bo-table/GrpBO.php"; }
    static public function getGrpMemberBO()                                         { require_once ROOT."/src/system/bo-table/GrpMemberBO.php"; }
    static public function getGrpMemberPointhistBO()                                { require_once ROOT."/src/system/bo-table/GrpMemberPointhistBO.php"; }
    static public function getScheduleallBO()                                       { require_once ROOT."/src/system/bo-table/ScheduleallBO.php"; }
    static public function getSchedulebyweekBO()                                    { require_once ROOT."/src/system/bo-table/SchedulebyweekBO.php"; }
    static public function getSchedulebytimeBO()                                    { require_once ROOT."/src/system/bo-table/SchedulebytimeBO.php"; }
    static public function getUserAddrBO()                                          { require_once ROOT."/src/system/bo-table/UserAddrBO.php"; }
    static public function getUserBO()                                              { require_once ROOT."/src/system/bo-table/UserBO.php"; }
    static public function getUserEtcBO()                                           { require_once ROOT."/src/system/bo-table/UserEtcBO.php"; }
    static public function getUserSearchoptBO()                                     { require_once ROOT."/src/system/bo-table/UserSearchoptBO.php"; }
    static public function getUserStoreloveBO()                                     { require_once ROOT."/src/system/bo-table/UserStoreloveBO.php"; }

    /* DAO */
    static public function getCartDAO()                                             { require_once ROOT.'/src/system/bo-dao/CartDAO.php'; }
    static public function getOrdermenuDAO()                                        { require_once ROOT.'/src/system/bo-dao/OrdermenuDAO.php'; }
    static public function getCartmenuDAO()                                         { require_once ROOT.'/src/system/bo-dao/CartmenuDAO.php'; }

    /* VO */
    static public function getDeliverychargeBO()                                    { require_once ROOT."/src/system/bo-vo/DeliverychargeBO.php"; }
    static public function getOrderDeliverystatusCntForRiderBO()                    { require_once ROOT."/src/system/bo-vo/OrderDeliverystatusCntForRiderBO.php"; }
    static public function getYearVO()                                              { require_once ROOT."/src/system/bo-vo/YearVO.php"; }

    /* Batch */
    static public function getPer00BatchBase()                                      { require_once ROOT."/src/system/bo-batch/Per00BatchBase.php"; }
    static public function getPer10ApiInsertPaymentDepositedByList()                { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per10ApiInsertPaymentDepositedByList.php"; }
    static public function getPer20SecRiderDeliverymatch()                          { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per20SecRiderDeliverymatch.php"; }
    static public function getPer30MinOrderCancelNotConfirmed()                     { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per30MinOrderCancelNotConfirmed.php"; }
    static public function getPer30MinOrderCancelNotPaid()                          { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per30MinOrderCancelNotPaid.php"; }
    static public function getPer30MinOrderingToOrdera()                            { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per30MinOrderingToOrdera.php"; }
    static public function getPer30MinStoreSalestatusAutoProcess()                  { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per30MinStoreSalestatusAutoProcess.php"; }
    static public function getPer40HouPartitions()                                  { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per40HouPartitions.php"; }
    static public function getPer40HouStoreOrderproctimeSummaryToday()              { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per40HouStoreOrderproctimeSummaryToday.php"; }
    static public function getPer50DaySalesSummary()                                { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per50DaySalesSummary.php"; }
    static public function getPer50DayStoreOrderproctimeSummaryMonth()              { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per50DayStoreOrderproctimeSummaryMonth.php"; }
    static public function getPer50DaySummaryStoreorderRecent()                     { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per50DaySummaryStoreorderRecent.php"; }
    static public function getPer50DayUpdateReorderpct()                            { self::getPer00BatchBase(); require_once ROOT."/src/system/bo-batch/Per50DayUpdateReorderpct.php"; }

    /* Model */
    static public function getMOrder()                                              { require_once ROOT."/src/system/model/MOrder.php"; }

    /* validator */
    static public function getStoreValidator()                                      { require_once ROOT.'/src/system/validator/StoreValidator.php'; }


}
?>
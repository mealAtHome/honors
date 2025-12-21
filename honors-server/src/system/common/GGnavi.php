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
    static public function getRefFeeBO()                                            { require_once ROOT.'/src/system/bo-ref/RefFeeBO.php'; }
    static public function getRefHolidayBO()                                        { require_once ROOT.'/src/system/bo-ref/RefHolidayBO.php'; }
    static public function getSystemBatchBO()                                       { require_once ROOT."/src/system/bo-ref/SystemBatchBO.php"; }
    static public function getSystemStatusBO()                                      { require_once ROOT."/src/system/bo-ref/SystemStatusBO.php"; }
    static public function getLocationOfDeliveryChargeBO()                          { require_once ROOT."/src/system/bo-ref/LocationOfDeliveryChargeBO.php"; }
    static public function getInformationSchemaPartitionsBO()                       { require_once ROOT."/src/system/bo-ref/InformationSchemaPartitionsBO.php"; }
    static public function getRefDeliverychargeDiscountBO()                         { require_once ROOT.'/src/system/bo-ref/RefDeliverychargeDiscountBO.php'; }
    static public function getRefDeliverychargeWeatherBO()                          { require_once ROOT.'/src/system/bo-ref/RefDeliverychargeWeatherBO.php'; }

    /* BO : for users */
    static public function getAddrBO()                                              { require_once ROOT."/src/system/bo-table/AddrBO.php"; }
    static public function getBankaccountBO()                                       { require_once ROOT."/src/system/bo-table/BankaccountBO.php"; }
    static public function getClsBO()                                               { require_once ROOT."/src/system/bo-table/ClsBO.php"; }
    static public function getClzcancelBO()                                         { require_once ROOT."/src/system/bo-table/ClzcancelBO.php"; }
    static public function getClslineup2BO()                                        { require_once ROOT."/src/system/bo-table/Clslineup2BO.php"; }
    static public function getGrpfSettleBO()                                         { require_once ROOT."/src/system/bo-table/GrpfSettleBO.php"; }
    static public function getCartBO()                                              { require_once ROOT."/src/system/bo-table/CartBO.php"; }
    static public function getCartmenuBO()                                          { require_once ROOT."/src/system/bo-table/CartmenuBO.php"; }
    static public function getCartmenuoptBO()                                       { require_once ROOT."/src/system/bo-table/CartmenuoptBO.php"; }
    static public function getCartmenuoptDetailBO()                                 { require_once ROOT."/src/system/bo-table/CartmenuoptDetailBO.php"; }
    static public function getCartmenuRecommendBO()                                 { require_once ROOT."/src/system/bo-table/CartmenuRecommendBO.php"; }
    static public function getCategoryBO()                                          { require_once ROOT."/src/system/bo-table/CategoryBO.php"; }
    static public function getCntStoreOrderstatusBO()                               { require_once ROOT."/src/system/bo-table/CntStoreOrderstatusBO.php"; }
    static public function getCntYmUserorderBO()                                    { require_once ROOT."/src/system/bo-table/CntYmUserorderBO.php"; }
    static public function getDelivererBO()                                         { require_once ROOT."/src/system/bo-table/DelivererBO.php"; }
    static public function getIdxBO()                                               { require_once ROOT."/src/system/bo-table/_IdxBO.php"; }
    static public function getMenuBO()                                              { require_once ROOT."/src/system/bo-table/MenuBO.php"; }
    static public function getMenuCategoryBO()                                      { require_once ROOT."/src/system/bo-table/MenuCategoryBO.php"; }
    static public function getMenuoptBO()                                           { require_once ROOT."/src/system/bo-table/MenuoptBO.php"; }
    static public function getMenuoptDetailBO()                                     { require_once ROOT."/src/system/bo-table/MenuoptDetailBO.php"; }
    static public function getMenuPicBO()                                           { require_once ROOT."/src/system/bo-table/MenuPicBO.php"; }
    static public function getMenuRecommendBO()                                     { require_once ROOT."/src/system/bo-table/MenuRecommendBO.php"; }
    static public function getOrderaBO()                                            { self::getOrderBO(); require_once ROOT."/src/system/bo-table/OrderaBO.php"; }
    static public function getOrderBO()                                             { require_once ROOT."/src/system/bo-table/OrderBO.php"; }
    static public function getOrderingBO()                                          { self::getOrderBO(); require_once ROOT."/src/system/bo-table/OrderingBO.php"; }
    static public function getOrdermenuBO()                                         { require_once ROOT."/src/system/bo-table/OrdermenuBO.php"; }
    static public function getOrdermenuoptBO()                                      { require_once ROOT."/src/system/bo-table/OrdermenuoptBO.php"; }
    static public function getOrdermenuoptDetailBO()                                { require_once ROOT."/src/system/bo-table/OrdermenuoptDetailBO.php"; }
    static public function getOrdermenuRecommendBO()                                { require_once ROOT."/src/system/bo-table/OrdermenuRecommendBO.php"; }
    static public function getOrdersAddrBO()                                        { require_once ROOT."/src/system/bo-table/OrdersAddrBO.php"; }
    static public function getOrdersCancelBO()                                      { require_once ROOT."/src/system/bo-table/OrdersCancelBO.php"; }
    static public function getOrdersClaimBO()                                       { require_once ROOT."/src/system/bo-table/OrdersClaimBO.php"; }
    static public function getOrderzUserlastBO()                                    { require_once ROOT."/src/system/bo-table/OrderzUserlastBO.php"; }
    static public function getPaymentAccountBO()                                    { require_once ROOT."/src/system/bo-table/PaymentAccountBO.php"; }
    static public function getPaymentDepositedBO()                                  { require_once ROOT."/src/system/bo-table/PaymentDepositedBO.php"; }
    static public function getPaymentMissedreqBO()                                  { require_once ROOT."/src/system/bo-table/PaymentMissedreqBO.php"; }
    static public function getPaymentQueueBO()                                      { require_once ROOT."/src/system/bo-table/PaymentQueueBO.php"; }
    static public function getPaymentQueueFailedBO()                                { require_once ROOT."/src/system/bo-table/PaymentQueueFailedBO.php"; }
    static public function getPaymentLogBO()                                        { require_once ROOT."/src/system/bo-table/PaymentLogBO.php"; }
    static public function getReorderpctCalBO()                                     { require_once ROOT.'/src/system/bo-table/ReorderpctCalBO.php'; }
    static public function getReorderpctLogBO()                                     { require_once ROOT.'/src/system/bo-table/ReorderpctLogBO.php'; }
    static public function getReorderpctResultBO()                                  { require_once ROOT.'/src/system/bo-table/ReorderpctResultBO.php'; }
    static public function getReviewBO()                                            { require_once ROOT."/src/system/bo-table/ReviewBO.php"; }
    static public function getReviewMenuBO()                                        { require_once ROOT."/src/system/bo-table/ReviewMenuBO.php"; }
    static public function getRiderBO()                                             { require_once ROOT.'/src/system/bo-table/RiderBO.php'; }
    static public function getRiderDeliverymatchBO()                                { require_once ROOT."/src/system/bo-table/RiderDeliverymatchBO.php"; }
    static public function getSettleRiderBO()                                       { require_once ROOT."/src/system/bo-table/SettleRiderBO.php"; }
    static public function getSettleStoreBO()                                       { require_once ROOT."/src/system/bo-table/SettleStoreBO.php"; }
    static public function getSidedetailBO()                                        { require_once ROOT."/src/system/bo-table/SidedetailBO.php"; }
    static public function getSidemenuBO()                                          { require_once ROOT."/src/system/bo-table/SidemenuBO.php"; }
    static public function getGrpBO()                                               { require_once ROOT."/src/system/bo-table/GrpBO.php"; }
    static public function getGrpMemberBO()                                         { require_once ROOT."/src/system/bo-table/GrpMemberBO.php"; }
    static public function getGrpMemberPointhistBO()                                { require_once ROOT."/src/system/bo-table/GrpMemberPointhistBO.php"; }
    static public function getStoreDeliverychargeBO()                               { require_once ROOT."/src/system/bo-table/StoreDeliverychargeBO.php"; }
    static public function getStoreOrderproctimeLogBO()                             { require_once ROOT."/src/system/bo-table/StoreOrderproctimeLogBO.php"; }
    static public function getStoreOrderproctimeResultBO()                          { require_once ROOT."/src/system/bo-table/StoreOrderproctimeResultBO.php"; }
    static public function getStoreSalestatusBO()                                   { require_once ROOT."/src/system/bo-table/StoreSalestatusBO.php"; }
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
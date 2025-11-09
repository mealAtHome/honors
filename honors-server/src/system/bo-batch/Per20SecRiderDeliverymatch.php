<?php

/*
    rider_deliverymatch 테이블에 입력되어 있는 모든 데이터를 처리함 (매칭처리)
*/
class Per20SecRiderDeliverymatch extends Per00BatchBase
{
    /* ----- */
    /* singleton */
    /* ----- */
    private static $bo;
    public static function getInstance()
    {
        if(self::$bo == null)
            self::$bo = new static();
        return self::$bo;
    }
    public $batchname = "per-20-sec-riderDeliverymatch";

    public function __construct()
    {
        GGnavi::getRiderDeliverymatchBO();
        GGnavi::getOrderingBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();
        $orderingBO = OrderingBO::getInstance();
        $riderDeliverymatchBO = RiderDeliverymatchBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */
        $hasData = true;
        do
        {
            $data = Common::getData($riderDeliverymatchBO->selectForProcess());
            if(count($data) == 0)
            {
                $hasData = false;
                break;
            }
            foreach($data as $row)
            {
                $STORENO = $row[RiderDeliverymatchBO::FIELD__STORENO];
                $ORDERNO = $row[RiderDeliverymatchBO::FIELD__ORDERNO];
                $RIDERNO = $row[RiderDeliverymatchBO::FIELD__RIDERNO];

                /* 주문정보 조회 */
                $orderValidFlg = true;
                $orderDat = Common::getDataOne($orderingBO->selectByPkForInside($STORENO, $ORDERNO));
                if($orderDat[OrderBO::FIELD__DELIVERYSTATUS] != OrderBO::DELIVERYSTATUS__MATCHING ) $orderValidFlg = false;
                if($orderDat[OrderBO::FIELD__RIDERNO]        != null                              ) $orderValidFlg = false;

                /* 주문 업데이트 */
                if($orderValidFlg)
                    $orderingBO->updateDeliverystatusToTostoreForInside($STORENO, $ORDERNO, $RIDERNO);

                /* 매칭정보 삭제 */
                $riderDeliverymatchBO->deleteByPkForInside($STORENO, $ORDERNO, $RIDERNO);
            }
        }
        while($hasData);

        $this->afterProcess();
        return true;
    }

}
?>
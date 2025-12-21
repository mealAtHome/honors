<?php

/*
    각 BO의 부모가 될 클래스이다
    각 BO에서 처리할 공통적인 업무를 여기서 처리하도록 한다
*/

class _CommonBO
{
    /*
        selectByOption 의 기본 반환형
        validation 체크 등으로 인해 빈 값을 돌려줘야할 때도 쓴다
    */
    public function getRslt()
    {
        $rslt = array();
        $rslt[GGF::CODE]  = Common::SUCCEED;
        $rslt["COUNT"] = 0;
        $rslt["DATA"]  = array();
        return $rslt;
    }

    /*
        selectBy.. 의 각각 지정
    */
    const OPTION  = "OPTION";
    const selectByExecutor  = "selectByExecutor";
    const INSERT = "insert";
    const UPDATE = "update";
    const DELETE = "delete";

    /* 모든 BO의 공통함수 */
    public function selectByOption($options) { return $this->select($options); }
    public function updateByOption($options) { return $this->update($options); }

    /* ========================= */
    /* 랜덤인덱스 출력 (20241231-XXXXX) */
    /* ========================= */
    public function makeOrderno($EXECUTOR) { return $this->makeRandIndex("order", $EXECUTOR, 5); }
    public function makeRandIndex($entity, $key, $length = 5)
    {
        /* vars */
        $index  = "";

        /* get date */
        $dateStr = GGdate::getYMD();

        /* tried */
        $tried = 0;
        do
        {
            $tried++;
            $index = $dateStr."-".$this->getRandomString($length);

            /* 중복체크 */
            switch($entity)
            {
                case "order"            : { $query = "select coalesce(count(*),0) cnt from ordera            where userno  = '$key' and orderno = '$index'"; break; }
                case "paymentMissedreq" : { $query = "select coalesce(count(*),0) cnt from payment_missedreq where userno  = '$key' and reqno   = '$index'"; break; }
            }
            $cnt = GGsql::selectCnt($query);
            if($cnt == 0)
                return $index;
        }
        while($tried <= 10);
        throw new GGexception("새로운 키를 생성하는데 실패하였습니다. 다시 시도하여 주세요. 오류가 계속되면 시스템 관리자에게 문의하세요.");
    }

    public function getRandomString($length = 5)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}

?>
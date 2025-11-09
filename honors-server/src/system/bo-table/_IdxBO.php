<?php

class IdxBO extends _CommonBO
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
    function __construct() {
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__IDX     = "idx";       /* (PK) char(30) */
    const FIELD__ENTITY  = "entity";    /* char(50) */
    const FIELD__REGDT   = "regdt";     /* datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const ENTITY__USER = "user";
    const ENTITY__CLS  = "game";
    const ENTITY__PLOG = "payment_log";

    /* ========================= */
    /* update (sub > sub) */
    /* ========================= */
    public function makeUserno      () { return $this->makeNewIdx(30, self::ENTITY__USER, ""); }
    public function makeClsno       () { return $this->makeNewIdx(14, self::ENTITY__CLS, ""); }
    public function makePlogidx     ($USERNO, $PLOGD) { return $this->getNewIdx(self::ENTITY__PLOG, $USERNO, $PLOGD); }

    public function makeNewIdx($length=30, $entity="", $index="")
    {
        /* vars */
        $idx  = "";

        /* tried */
        $tried = 0;
        do
        {
            $tried++;
            $idx = "";

            $query = "";
            switch($entity)
            {
                case self::ENTITY__USER : $idx =                      $this->getRandomString($length  );  $query = "select coalesce(count(*),0) cnt from user where userno = '$idx'"; break;
                case self::ENTITY__CLS  : $idx = GGdate::getYMD()."-".$this->getRandomString($length-9);  $query = "select coalesce(count(*),0) cnt from cls  where clsno  = '$idx'"; break;
            }
            $cnt = intval(GGsql::selectCnt($query));
            if($cnt == 0)
                return $idx;
        }
        while($tried <= 100);

        throw new GGexception("새로운 키를 생성하는데 실패하였습니다. 다시 시도하여 주세요. 오류가 계속되면 시스템 관리자에게 문의하세요.");
    }

    public function getNewIdx($entity="", $key1="", $key2="")
    {
        $query = "";
        switch($entity)
        {
            case self::ENTITY__PLOG : $query = "select ifnull(max(plogidx),0) + 1 cnt from payment_log where userno = '$key1' and plogd = '$key2' group by userno, plogd"; break;
        }
        return intval(GGsql::selectCnt($query));
    }

} /* end class */
?>

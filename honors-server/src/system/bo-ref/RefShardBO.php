<?php

class RefShardBO extends _CommonBO
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

    public function setBO()
    {
    }

    /* ========================= */
    /* get mysqli from records */
    /* ========================= */
    public function getMysqli($record)
    {
        $option = array();
        $option[GGF::OPTION] = self::selectAll;
        $data = $this->selectRefShard($option);
        return Common::getData($data);
    }

    /* ========================= */
    /* sub select */
    /* ========================= */
    public function subSelectAll()
    {
        $option = array();
        $option[GGF::OPTION] = self::selectAll;
        $data = $this->selectRefShard($option);
        return Common::getData($data);
    }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectAll = "selectAll";
    public function selectRefShard($options)
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($options);

        /* --------------- */
        /* sql body */
        /* --------------- */
        $isPagenation = false;
        $query  = "";
        $select = "";
        $from   = "";
        $select =
        "
              rsd.shardno
            , rsd.shardname
            , rsd.latiy
            , rsd.longx
            , rsd.shardrange
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectAll:
            {
                $from = "(select * from ref_shard ) rsd";
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        } /* end switch($OPTION) */

        /* --------------- */
        /* exe query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
        ";
        return GGsql::select($query, $from, $options);
    }
} /* end class */
?>
